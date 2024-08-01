<?php
/**
 * Módulo de Solicitudes de EPPs - SIMAHG  
 * Versión compatible con sistema de sesión actual
 */

session_start();

// Verificar si está logueado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Configuración de la base de datos
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['perfil_nombre'] ?? 'Usuario';
    
    // Función helper para sanitizar
    function sanitizar($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
    
    // Procesar acciones POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        
        // CREAR SOLICITUD
        if ($accion === 'crear_solicitud') {
            $id_epp = $_POST['id_epp'];
            $cantidad = $_POST['cantidad'];
            $justificacion = sanitizar($_POST['justificacion']);
            
            $stmt = $pdo->prepare("
                INSERT INTO solicitudes_epp (id_usuario, id_epp, cantidad, justificacion, estado, fecha_solicitud)
                VALUES (?, ?, ?, ?, 'PENDIENTE', NOW())
            ");
            $stmt->execute([$userId, $id_epp, $cantidad, $justificacion]);
            
            $_SESSION['success'] = 'Solicitud creada exitosamente. En espera de aprobación.';
            header('Location: solicitudes_epp.php');
            exit;
        }
        
        // APROBAR SOLICITUD (solo admin/supervisor)
        if ($accion === 'aprobar' && in_array($userRole, ['Administrador', 'Supervisor'])) {
            $id_solicitud = $_POST['id_solicitud'];
            
            $stmt = $pdo->prepare("
                UPDATE solicitudes_epp 
                SET estado = 'APROBADA', 
                    id_aprobador = ?,
                    fecha_aprobacion = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$userId, $id_solicitud]);
            
            $_SESSION['success'] = 'Solicitud aprobada exitosamente';
            header('Location: solicitudes_epp.php');
            exit;
        }
        
        // RECHAZAR SOLICITUD
        if ($accion === 'rechazar' && in_array($userRole, ['Administrador', 'Supervisor'])) {
            $id_solicitud = $_POST['id_solicitud'];
            $motivo_rechazo = sanitizar($_POST['motivo_rechazo']);
            
            $stmt = $pdo->prepare("
                UPDATE solicitudes_epp 
                SET estado = 'RECHAZADA', 
                    id_aprobador = ?,
                    fecha_aprobacion = NOW(),
                    motivo_rechazo = ?
                WHERE id = ?
            ");
            $stmt->execute([$userId, $motivo_rechazo, $id_solicitud]);
            
            $_SESSION['success'] = 'Solicitud rechazada';
            header('Location: solicitudes_epp.php');
            exit;
        }
        
        // ENTREGAR EPP
        if ($accion === 'entregar' && in_array($userRole, ['Administrador', 'Supervisor'])) {
            $id_solicitud = $_POST['id_solicitud'];
            
            // Obtener datos de la solicitud
            $stmt2 = $pdo->prepare("SELECT id_epp, cantidad FROM solicitudes_epp WHERE id = ?");
            $stmt2->execute([$id_solicitud]);
            $solicitud = $stmt2->fetch();
            
            // Actualizar estado
            $stmt = $pdo->prepare("
                UPDATE solicitudes_epp 
                SET estado = 'ENTREGADA', 
                    fecha_entrega = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$id_solicitud]);
            
            // Descontar del inventario
            $stmt3 = $pdo->prepare("
                UPDATE epp_items 
                SET stock_actual = stock_actual - ? 
                WHERE id = ?
            ");
            $stmt3->execute([$solicitud['cantidad'], $solicitud['id_epp']]);
            
            $_SESSION['success'] = 'EPP entregado y descontado del inventario';
            header('Location: solicitudes_epp.php');
            exit;
        }
    }
    
    // Obtener solicitudes según rol
    if (in_array($userRole, ['Administrador', 'Supervisor', 'Operador'])) {
        // Ver todas las solicitudes
        $stmt = $pdo->query("
            SELECT s.*, e.nombre as epp_nombre, e.codigo, c.nombre as categoria,
                   u.nombre as solicitante_nombre, u.apellidos as solicitante_apellidos,
                   a.nombre as aprobador_nombre
            FROM solicitudes_epp s
            INNER JOIN epp_items e ON s.id_epp = e.id
            LEFT JOIN categorias_epp c ON e.id_categoria = c.id
            INNER JOIN usuarios u ON s.id_usuario = u.id
            LEFT JOIN usuarios a ON s.id_aprobador = a.id
            ORDER BY s.fecha_solicitud DESC
        ");
    } else {
        // Ver solo mis solicitudes
        $stmt = $pdo->prepare("
            SELECT s.*, e.nombre as epp_nombre, e.codigo, c.nombre as categoria,
                   u.nombre as solicitante_nombre, u.apellidos as solicitante_apellidos,
                   a.nombre as aprobador_nombre
            FROM solicitudes_epp s
            INNER JOIN epp_items e ON s.id_epp = e.id
            LEFT JOIN categorias_epp c ON e.id_categoria = c.id
            INNER JOIN usuarios u ON s.id_usuario = u.id
            LEFT JOIN usuarios a ON s.id_aprobador = a.id
            WHERE s.id_usuario = ?
            ORDER BY s.fecha_solicitud DESC
        ");
        $stmt->execute([$userId]);
    }
    
    $solicitudes = $stmt->fetchAll();
    
    // Obtener EPPs disponibles para el formulario
    $stmtEPP = $pdo->query("
        SELECT e.*, c.nombre as categoria_nombre 
        FROM epp_items e 
        LEFT JOIN categorias_epp c ON e.id_categoria = c.id 
        WHERE e.estado = 'activo' 
        ORDER BY c.nombre, e.nombre
    ");
    $epps = $stmtEPP->fetchAll();
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Solicitudes de EPPs - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            margin-bottom: 30px;
        }
        .navbar-brand, .navbar-nav li a { color: white !important; }
        .card { 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-aprobada { background-color: #28a745; }
        .badge-rechazada { background-color: #dc3545; }
        .badge-entregada { background-color: #17a2b8; }
        .modal-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .stat-box {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fa fa-shield"></i> SIMAHG
            </a>
        </div>
        <ul class="nav navbar-nav">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="equipos.php"><i class="fa fa-cogs"></i> Equipos</a></li>
            <li><a href="epp_gestion.php"><i class="fa fa-shield"></i> Gestión EPP</a></li>
            <li class="active"><a href="solicitudes_epp.php"><i class="fa fa-clipboard-list"></i> Solicitudes EPP</a></li>
            <li><a href="mantenimientos.php"><i class="fa fa-wrench"></i> Mantenimientos</a></li>
            <li><a href="reportes.php"><i class="fa fa-bar-chart"></i> Reportes</a></li>
            <li><a href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?> 
                    <span class="badge badge-info"><?php echo $userRole; ?></span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
    </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <div class="row">
                        <div class="col-md-6">
                            <h3><i class="fa fa-clipboard-list"></i> Solicitudes de EPPs</h3>
                            <p class="text-muted">
                                Sistema de solicitud y aprobación de Equipos de Protección Personal
                            </p>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalNuevaSolicitud">
                                <i class="fa fa-plus"></i> Nueva Solicitud
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="text-warning"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'PENDIENTE')); ?></h2>
                <p><i class="fa fa-clock-o"></i> Pendientes</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="text-success"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'APROBADA')); ?></h2>
                <p><i class="fa fa-check"></i> Aprobadas</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="text-info"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'ENTREGADA')); ?></h2>
                <p><i class="fa fa-handshake-o"></i> Entregadas</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <h2 class="text-danger"><?php echo count(array_filter($solicitudes, fn($s) => $s['estado'] == 'RECHAZADA')); ?></h2>
                <p><i class="fa fa-times"></i> Rechazadas</p>
            </div>
        </div>
    </div>
    
    <!-- Tabla de Solicitudes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" style="padding: 20px;">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fecha</th>
                                    <?php if (in_array($userRole, ['Administrador', 'Supervisor'])): ?>
                                    <th>Solicitante</th>
                                    <?php endif; ?>
                                    <th>EPP</th>
                                    <th>Categoría</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($solicitudes as $solicitud): ?>
                                <tr>
                                    <td><?php echo $solicitud['id']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($solicitud['fecha_solicitud'])); ?></td>
                                    <?php if (in_array($userRole, ['Administrador', 'Supervisor'])): ?>
                                    <td><?php echo $solicitud['solicitante_nombre'] . ' ' . $solicitud['solicitante_apellidos']; ?></td>
                                    <?php endif; ?>
                                    <td><?php echo $solicitud['epp_nombre']; ?></td>
                                    <td><?php echo $solicitud['categoria']; ?></td>
                                    <td><span class="badge badge-secondary"><?php echo $solicitud['cantidad']; ?></span></td>
                                    <td>
                                        <?php
                                        $badgeClass = 'badge-' . strtolower($solicitud['estado']);
                                        echo "<span class='badge $badgeClass'>" . $solicitud['estado'] . "</span>";
                                        ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="verDetalle(<?php echo htmlspecialchars(json_encode($solicitud)); ?>)">
                                            <i class="fa fa-eye"></i> Ver
                                        </button>
                                        
                                        <?php if ($solicitud['estado'] == 'PENDIENTE' && in_array($userRole, ['Administrador', 'Supervisor'])): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="accion" value="aprobar">
                                            <input type="hidden" name="id_solicitud" value="<?php echo $solicitud['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('¿Aprobar esta solicitud?')">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-danger" onclick="rechazarSolicitud(<?php echo $solicitud['id']; ?>)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($solicitud['estado'] == 'APROBADA' && in_array($userRole, ['Administrador', 'Supervisor'])): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="accion" value="entregar">
                                            <input type="hidden" name="id_solicitud" value="<?php echo $solicitud['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('¿Confirmar entrega? Se descontará del inventario.')">
                                                <i class="fa fa-hand-paper-o"></i> Entregar
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($solicitudes)): ?>
                                <tr>
                                    <td colspan="8" class="text-center">No hay solicitudes registradas</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Modal Nueva Solicitud -->
<div class="modal fade" id="modalNuevaSolicitud" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Nueva Solicitud de EPP</h4>
            </div>
            <form method="POST">
                <input type="hidden" name="accion" value="crear_solicitud">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Seleccione el EPP *</label>
                        <select name="id_epp" class="form-control" required>
                            <option value="">-- Seleccione un EPP --</option>
                            <?php foreach ($epps as $epp): ?>
                            <option value="<?php echo $epp['id']; ?>">
                                <?php echo $epp['nombre'] . ' - ' . $epp['categoria_nombre'] . ' (Disponible: ' . $epp['stock_actual'] . ')'; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Cantidad *</label>
                        <input type="number" name="cantidad" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label>Justificación *</label>
                        <textarea name="justificacion" class="form-control" rows="3" required placeholder="Explica por qué necesitas este EPP..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-paper-plane"></i> Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalle" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-file-text"></i> Detalle de Solicitud</h4>
            </div>
            <div class="modal-body" id="detalleContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Rechazar -->
<div class="modal fade" id="modalRechazar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #dc3545;">
                <button type="button" class="close" data-dismiss="modal" style="color: white;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-times"></i> Rechazar Solicitud</h4>
            </div>
            <form method="POST">
                <input type="hidden" name="accion" value="rechazar">
                <input type="hidden" name="id_solicitud" id="rechazar_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Motivo del rechazo *</label>
                        <textarea name="motivo_rechazo" class="form-control" rows="3" required placeholder="Explica por qué se rechaza esta solicitud..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times"></i> Rechazar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="bower_components/jquery/dist/jquery.min.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
function verDetalle(solicitud) {
    let html = `
        <table class="table table-bordered">
            <tr><th width="40%">ID:</th><td>${solicitud.id}</td></tr>
            <tr><th>EPP:</th><td>${solicitud.epp_nombre}</td></tr>
            <tr><th>Código:</th><td>${solicitud.codigo}</td></tr>
            <tr><th>Categoría:</th><td>${solicitud.categoria}</td></tr>
            <tr><th>Cantidad:</th><td><span class="badge badge-secondary">${solicitud.cantidad}</span></td></tr>
            <tr><th>Estado:</th><td><span class="badge badge-${solicitud.estado.toLowerCase()}">${solicitud.estado}</span></td></tr>
            <tr><th>Solicitante:</th><td>${solicitud.solicitante_nombre} ${solicitud.solicitante_apellidos}</td></tr>
            <tr><th>Fecha Solicitud:</th><td>${solicitud.fecha_solicitud}</td></tr>
            <tr><th>Justificación:</th><td>${solicitud.justificacion}</td></tr>
    `;
    
    if (solicitud.aprobador_nombre) {
        html += `<tr><th>Aprobador:</th><td>${solicitud.aprobador_nombre}</td></tr>`;
    }
    
    if (solicitud.fecha_aprobacion) {
        html += `<tr><th>Fecha Aprobación:</th><td>${solicitud.fecha_aprobacion}</td></tr>`;
    }
    
    if (solicitud.fecha_entrega) {
        html += `<tr><th>Fecha Entrega:</th><td>${solicitud.fecha_entrega}</td></tr>`;
    }
    
    if (solicitud.motivo_rechazo) {
        html += `<tr><th>Motivo Rechazo:</th><td class="text-danger">${solicitud.motivo_rechazo}</td></tr>`;
    }
    
    html += `</table>`;
    
    $('#detalleContent').html(html);
    $('#modalDetalle').modal('show');
}

function rechazarSolicitud(id) {
    $('#rechazar_id').val(id);
    $('#modalRechazar').modal('show');
}
</script>

</body>
</html>
