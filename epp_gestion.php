<?php
session_start();

// Incluir archivo común
require_once 'includes/config_common.php';

// Obtener conexión a BD
$pdo = getDBConnection();

$message = '';
$error = '';

// Procesar formularios
if ($_POST) {
    try {
        if (isset($_POST['action'])) {
            if ($_POST['action'] == 'entregar_epp') {
                $id_usuario = $_POST['id_usuario'];
                $id_epp_item = $_POST['id_epp_item'];
                $cantidad = $_POST['cantidad'];
                $fecha_entrega = $_POST['fecha_entrega'];
                $observaciones = trim($_POST['observaciones']);
                
                // Verificar stock disponible
                $stmt = $pdo->prepare("SELECT stock_actual, nombre FROM epp_items WHERE id = ?");
                $stmt->execute([$id_epp_item]);
                $epp = $stmt->fetch();
                
                if ($epp['stock_actual'] < $cantidad) {
                    $error = "Stock insuficiente. Disponible: " . $epp['stock_actual'];
                } else {
                    // Generar código único de entrega
                    $codigo_entrega = 'ENT-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                    
                    // Calcular fecha de devolución (basada en vida útil del EPP)
                    $stmt = $pdo->prepare("SELECT ce.vida_util_dias FROM epp_items ei 
                                          JOIN categorias_epp ce ON ei.id_categoria = ce.id 
                                          WHERE ei.id = ?");
                    $stmt->execute([$id_epp_item]);
                    $vida_util = $stmt->fetch()['vida_util_dias'] ?? 365;
                    
                    $fecha_devolucion = date('Y-m-d', strtotime($fecha_entrega . " + $vida_util days"));
                    
                    // Insertar entrega
                    $sql = "INSERT INTO epp_entregas (codigo_entrega, id_usuario, id_epp_item, cantidad_entregada, 
                            fecha_entrega, fecha_devolucion_programada, observaciones_entrega, entregado_por) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $codigo_entrega, $id_usuario, $id_epp_item, $cantidad,
                        $fecha_entrega, $fecha_devolucion, $observaciones, $_SESSION['user_id']
                    ]);
                    
                    $message = "EPP entregado exitosamente. Código: $codigo_entrega";
                }
            }
            
            if ($_POST['action'] == 'devolver_epp') {
                $entrega_id = $_POST['entrega_id'];
                $fecha_devolucion = $_POST['fecha_devolucion'];
                $condicion = $_POST['condicion'];
                $observaciones = trim($_POST['observaciones_devolucion']);
                
                $stmt = $pdo->prepare("UPDATE epp_entregas SET estado = 'devuelto', fecha_devolucion_real = ?, 
                                      condicion_devolucion = ?, observaciones_devolucion = ?, recibido_por = ? 
                                      WHERE id = ?");
                $stmt->execute([$fecha_devolucion, $condicion, $observaciones, $_SESSION['user_id'], $entrega_id]);
                
                $message = "EPP devuelto exitosamente";
            }
            
            if ($_POST['action'] == 'agregar_epp') {
                $codigo = trim($_POST['codigo']);
                $nombre = trim($_POST['nombre']);
                $marca = trim($_POST['marca']);
                $modelo = trim($_POST['modelo']);
                $talla = trim($_POST['talla']);
                $id_categoria = $_POST['id_categoria'];
                $stock_actual = $_POST['stock_actual'];
                $stock_minimo = $_POST['stock_minimo'];
                $costo_unitario = $_POST['costo_unitario'];
                $proveedor = trim($_POST['proveedor']);
                $descripcion = trim($_POST['descripcion']);
                
                // Validar código único
                $stmt = $pdo->prepare("SELECT id FROM epp_items WHERE codigo = ?");
                $stmt->execute([$codigo]);
                
                if ($stmt->rowCount() > 0) {
                    $error = "Ya existe un EPP con ese código";
                } else {
                    $sql = "INSERT INTO epp_items (codigo, nombre, descripcion, marca, modelo, talla, 
                            id_categoria, stock_actual, stock_minimo, costo_unitario, proveedor, creado_por) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $codigo, $nombre, $descripcion, $marca, $modelo, $talla,
                        $id_categoria, $stock_actual, $stock_minimo, $costo_unitario, 
                        $proveedor, $_SESSION['user_id']
                    ]);
                    
                    $message = "EPP agregado exitosamente";
                }
            }
        }
        
    } catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}

try {
    // Obtener categorías de EPP
    $stmt = $pdo->query("SELECT * FROM categorias_epp WHERE estado = 1 ORDER BY nombre");
    $categorias_epp = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener usuarios para entregas
    $stmt = $pdo->query("SELECT id, CONCAT(nombre, ' ', apellidos) as nombre_completo FROM usuarios WHERE estado = 1 ORDER BY nombre");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener EPPs disponibles
    $stmt = $pdo->query("SELECT e.*, c.nombre as categoria_nombre 
                        FROM epp_items e 
                        LEFT JOIN categorias_epp c ON e.id_categoria = c.id 
                        WHERE e.estado = 'activo' 
                        ORDER BY e.nombre");
    $epps_disponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener entregas activas
    $stmt = $pdo->query("SELECT en.*, u.nombre, u.apellidos, e.nombre as epp_nombre, e.codigo as epp_codigo 
                        FROM epp_entregas en 
                        JOIN usuarios u ON en.id_usuario = u.id 
                        JOIN epp_items e ON en.id_epp_item = e.id 
                        WHERE en.estado IN ('entregado', 'en_uso') 
                        ORDER BY en.fecha_entrega DESC 
                        LIMIT 20");
    $entregas_activas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Estadísticas de EPPs
    $stmt = $pdo->query("SELECT COUNT(*) as total_items FROM epp_items WHERE estado = 'activo'");
    $total_items = $stmt->fetch()['total_items'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as items_bajo_stock FROM epp_items WHERE stock_actual <= stock_minimo AND estado = 'activo'");
    $items_bajo_stock = $stmt->fetch()['items_bajo_stock'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as entregas_pendientes FROM epp_entregas WHERE estado IN ('entregado', 'en_uso')");
    $entregas_pendientes = $stmt->fetch()['entregas_pendientes'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as vencimientos_proximos 
                        FROM epp_entregas 
                        WHERE estado IN ('entregado', 'en_uso') 
                        AND fecha_devolucion_programada <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)");
    $vencimientos_proximos = $stmt->fetch()['vencimientos_proximos'];
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
    $categorias_epp = [];
    $usuarios = [];
    $epps_disponibles = [];
    $entregas_activas = [];
    $total_items = 0;
    $items_bajo_stock = 0;
    $entregas_pendientes = 0;
    $vencimientos_proximos = 0;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de EPPs - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php renderEstilosComunes(); ?>
    <style>
        .epp-header {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .alert-card {
            background: linear-gradient(135deg, #ff7b7b 0%, #667eea 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .success-card {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php renderNavbar('epp_gestion'); ?>

    <div class="container mt-4">
        <!-- Header -->
        <div class="epp-header mb-4">
            <h2><i class="fa fa-shield"></i> Gestión de EPPs</h2>
            <p>Control y seguimiento de Equipos de Protección Personal</p>
        </div>

        <!-- Mensajes -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-check"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fa fa-exclamation-triangle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="row">
            <div class="col-md-3">
                <div class="card stat-card">
                    <h4><i class="fa fa-shield"></i></h4>
                    <h3><?php echo $total_items; ?></h3>
                    <p>Total EPPs</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card <?php echo $items_bajo_stock > 0 ? 'alert-card' : 'success-card'; ?>">
                    <h4><i class="fa fa-exclamation-triangle"></i></h4>
                    <h3><?php echo $items_bajo_stock; ?></h3>
                    <p>Stock Bajo</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <h4><i class="fa fa-hand-o-right"></i></h4>
                    <h3><?php echo $entregas_pendientes; ?></h3>
                    <p>Entregas Activas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card <?php echo $vencimientos_proximos > 0 ? 'alert-card' : 'success-card'; ?>">
                    <h4><i class="fa fa-clock-o"></i></h4>
                    <h3><?php echo $vencimientos_proximos; ?></h3>
                    <p>Vencen en 30 días</p>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-bolt"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <button class="btn btn-primary btn-block" data-toggle="modal" data-target="#modalEntregarEPP">
                            <i class="fa fa-hand-o-right"></i> Entregar EPP
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalDevolverEPP">
                            <i class="fa fa-undo"></i> Devolver EPP
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info btn-block" data-toggle="modal" data-target="#modalAgregarEPP">
                            <i class="fa fa-plus"></i> Agregar EPP
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="reportes_epp.php" class="btn btn-warning btn-block">
                            <i class="fa fa-chart-bar"></i> Reportes EPP
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventario de EPPs -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-list"></i> Inventario de EPPs</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Estado Stock</th>
                                <th>Costo Unit.</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($epps_disponibles as $epp): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($epp['codigo']); ?></strong></td>
                                <td>
                                    <?php echo htmlspecialchars($epp['nombre']); ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($epp['marca'] . ' ' . $epp['modelo']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($epp['categoria_nombre']); ?></td>
                                <td>
                                    <span class="badge badge-info"><?php echo $epp['stock_actual']; ?></span>
                                    / <?php echo $epp['stock_minimo']; ?>
                                </td>
                                <td>
                                    <?php if ($epp['stock_actual'] <= $epp['stock_minimo']): ?>
                                        <span class="badge badge-danger">Stock Bajo</span>
                                    <?php elseif ($epp['stock_actual'] <= $epp['stock_minimo'] * 2): ?>
                                        <span class="badge badge-warning">Stock Medio</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Stock OK</span>
                                    <?php endif; ?>
                                </td>
                                <td>S/ <?php echo number_format($epp['costo_unitario'], 2); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="entregarEPP(<?php echo $epp['id']; ?>, '<?php echo htmlspecialchars($epp['nombre']); ?>')" title="Entregar">
                                        <i class="fa fa-hand-o-right"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info" onclick="verDetalle(<?php echo $epp['id']; ?>)" title="Ver Detalle">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Entregas Activas -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-clock-o"></i> Entregas Activas (Últimas 20)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Código</th>
                                <th>Usuario</th>
                                <th>EPP</th>
                                <th>Cantidad</th>
                                <th>Fecha Entrega</th>
                                <th>Vence</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entregas_activas as $entrega): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($entrega['codigo_entrega']); ?></strong></td>
                                <td><?php echo htmlspecialchars($entrega['nombre'] . ' ' . $entrega['apellidos']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($entrega['epp_nombre']); ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($entrega['epp_codigo']); ?></small>
                                </td>
                                <td><span class="badge badge-info"><?php echo $entrega['cantidad_entregada']; ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($entrega['fecha_entrega'])); ?></td>
                                <td>
                                    <?php 
                                    $fecha_vence = strtotime($entrega['fecha_devolucion_programada']);
                                    $hoy = time();
                                    $dias_restantes = ($fecha_vence - $hoy) / (24 * 60 * 60);
                                    
                                    if ($dias_restantes < 0): ?>
                                        <span class="badge badge-danger">Vencido</span>
                                    <?php elseif ($dias_restantes <= 30): ?>
                                        <span class="badge badge-warning"><?php echo ceil($dias_restantes); ?> días</span>
                                    <?php else: ?>
                                        <span class="badge badge-success"><?php echo ceil($dias_restantes); ?> días</span>
                                    <?php endif; ?>
                                    <br><small><?php echo date('d/m/Y', $fecha_vence); ?></small>
                                </td>
                                <td>
                                    <span class="badge badge-<?php echo $entrega['estado'] == 'entregado' ? 'primary' : 'warning'; ?>">
                                        <?php echo ucfirst($entrega['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success" onclick="devolverEPP(<?php echo $entrega['id']; ?>, '<?php echo htmlspecialchars($entrega['codigo_entrega']); ?>')" title="Devolver">
                                        <i class="fa fa-undo"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Navegación rápida -->
        <div class="card">
            <div class="card-body text-center">
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="equipos.php" class="btn btn-warning">
                    <i class="fa fa-cogs"></i> Gestión de Equipos
                </a>
                <a href="mantenimientos.php" class="btn btn-info">
                    <i class="fa fa-wrench"></i> Mantenimientos
                </a>
                <a href="reportes.php" class="btn btn-success">
                    <i class="fa fa-chart-bar"></i> Reportes
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Entregar EPP -->
    <div class="modal fade" id="modalEntregarEPP" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-hand-o-right"></i> Entregar EPP</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="entregar_epp">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Usuario *</label>
                                    <select name="id_usuario" class="form-control" required>
                                        <option value="">Seleccionar usuario...</option>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <option value="<?php echo $usuario['id']; ?>"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>EPP *</label>
                                    <select name="id_epp_item" class="form-control" required onchange="mostrarStock(this)">
                                        <option value="">Seleccionar EPP...</option>
                                        <?php foreach ($epps_disponibles as $epp): ?>
                                            <option value="<?php echo $epp['id']; ?>" data-stock="<?php echo $epp['stock_actual']; ?>">
                                                <?php echo htmlspecialchars($epp['nombre'] . ' (' . $epp['codigo'] . ')'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small id="stock-info" class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Cantidad *</label>
                                    <input type="number" class="form-control" name="cantidad" min="1" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Entrega *</label>
                                    <input type="date" class="form-control" name="fecha_entrega" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones de la entrega..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-hand-o-right"></i> Entregar EPP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Devolver EPP -->
    <div class="modal fade" id="modalDevolverEPP" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-undo"></i> Devolver EPP</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="devolver_epp">
                    <input type="hidden" name="entrega_id" id="devolver_entrega_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Fecha de Devolución *</label>
                            <input type="date" class="form-control" name="fecha_devolucion" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Condición del EPP *</label>
                            <select name="condicion" class="form-control" required>
                                <option value="">Seleccionar condición...</option>
                                <option value="excelente">Excelente</option>
                                <option value="bueno">Bueno</option>
                                <option value="regular">Regular</option>
                                <option value="malo">Malo</option>
                                <option value="destruido">Destruido</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Observaciones de Devolución</label>
                            <textarea class="form-control" name="observaciones_devolucion" rows="3" placeholder="Observaciones sobre la condición del EPP..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-undo"></i> Devolver EPP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Agregar EPP -->
    <div class="modal fade" id="modalAgregarEPP" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> Agregar Nuevo EPP</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="agregar_epp">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código *</label>
                                    <input type="text" class="form-control" name="codigo" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Categoría *</label>
                                    <select name="id_categoria" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($categorias_epp as $categoria): ?>
                                            <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nombre *</label>
                            <input type="text" class="form-control" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <textarea class="form-control" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Marca</label>
                                    <input type="text" class="form-control" name="marca">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modelo</label>
                                    <input type="text" class="form-control" name="modelo">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Talla</label>
                                    <input type="text" class="form-control" name="talla">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Stock Actual *</label>
                                    <input type="number" class="form-control" name="stock_actual" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Stock Mínimo</label>
                                    <input type="number" class="form-control" name="stock_minimo" min="0" value="5">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Costo Unitario</label>
                                    <input type="number" step="0.01" class="form-control" name="costo_unitario" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Proveedor</label>
                            <input type="text" class="form-control" name="proveedor">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Guardar EPP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        function mostrarStock(select) {
            const option = select.selectedOptions[0];
            const stock = option ? option.getAttribute('data-stock') : 0;
            document.getElementById('stock-info').textContent = stock > 0 ? `Stock disponible: ${stock}` : '';
        }

        function entregarEPP(id, nombre) {
            $('select[name="id_epp_item"]').val(id);
            mostrarStock($('select[name="id_epp_item"]')[0]);
            $('#modalEntregarEPP').modal('show');
        }

        function devolverEPP(id, codigo) {
            document.getElementById('devolver_entrega_id').value = id;
            $('#modalDevolverEPP').modal('show');
        }

        function verDetalle(id) {
            alert('Funcionalidad de ver detalle en desarrollo. ID: ' + id);
        }
    </script>
</body>
</html>
# Update 1764801942
# Update 1764801944
