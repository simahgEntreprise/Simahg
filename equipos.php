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
            if ($_POST['action'] == 'agregar_equipo') {
                $codigo = trim($_POST['codigo']);
                $nombre = trim($_POST['nombre']);
                $marca = trim($_POST['marca']);
                $modelo = trim($_POST['modelo']);
                $numero_serie = trim($_POST['numero_serie']);
                $id_categoria = $_POST['id_categoria'];
                $fecha_adquisicion = $_POST['fecha_adquisicion'];
                $costo_adquisicion = $_POST['costo_adquisicion'];
                $ubicacion = trim($_POST['ubicacion']);
                $responsable = trim($_POST['responsable']);
                $descripcion = trim($_POST['descripcion']);
                
                // Validar que el código no exista
                $stmt = $pdo->prepare("SELECT id FROM equipos WHERE codigo = ?");
                $stmt->execute([$codigo]);
                
                if ($stmt->rowCount() > 0) {
                    $error = "Ya existe un equipo con ese código";
                } else {
                    $sql = "INSERT INTO equipos (codigo, nombre, descripcion, marca, modelo, numero_serie, 
                            id_categoria, fecha_adquisicion, costo_adquisicion, ubicacion, responsable, creado_por) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $codigo, $nombre, $descripcion, $marca, $modelo, $numero_serie,
                        $id_categoria, $fecha_adquisicion, $costo_adquisicion, $ubicacion, 
                        $responsable, $_SESSION['user_id']
                    ]);
                    
                    $message = "Equipo agregado exitosamente";
                }
            }
            
            if ($_POST['action'] == 'cambiar_estado') {
                $equipo_id = $_POST['equipo_id'];
                $nuevo_estado = $_POST['nuevo_estado'];
                
                $stmt = $pdo->prepare("UPDATE equipos SET estado = ?, actualizado_por = ? WHERE id = ?");
                $stmt->execute([$nuevo_estado, $_SESSION['user_id'], $equipo_id]);
                
                $message = "Estado del equipo actualizado exitosamente";
            }
        }
        
    } catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}

try {
    // Obtener categorías para el formulario
    $stmt = $pdo->query("SELECT * FROM categorias_equipos WHERE estado = 1 ORDER BY nombre");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener equipos con información de categoría
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : '';
    $categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : '';
    
    $sql = "SELECT e.*, c.nombre as categoria_nombre 
            FROM equipos e 
            LEFT JOIN categorias_equipos c ON e.id_categoria = c.id 
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($filtro)) {
        $sql .= " AND (e.codigo LIKE ? OR e.nombre LIKE ? OR e.marca LIKE ?)";
        $filtro_param = "%$filtro%";
        $params = array_merge($params, [$filtro_param, $filtro_param, $filtro_param]);
    }
    
    if (!empty($categoria_filtro)) {
        $sql .= " AND e.id_categoria = ?";
        $params[] = $categoria_filtro;
    }
    
    $sql .= " ORDER BY e.fecha_creacion DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Estadísticas
    $stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM equipos GROUP BY estado");
    $estadisticas = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM equipos");
    $total_equipos = $stmt->fetch()['total'];
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
    $equipos = [];
    $categorias = [];
    $estadisticas = [];
    $total_equipos = 0;
}

// Función para obtener el color del estado
function getEstadoColor($estado) {
    switch($estado) {
        case 'operativo': return 'success';
        case 'mantenimiento': return 'warning';
        case 'fuera_servicio': return 'danger';
        case 'dado_baja': return 'secondary';
        default: return 'info';
    }
}

function formatEstado($estado) {
    switch($estado) {
        case 'operativo': return 'Operativo';
        case 'mantenimiento': return 'Mantenimiento';
        case 'fuera_servicio': return 'Fuera de Servicio';
        case 'dado_baja': return 'Dado de Baja';
        default: return ucfirst($estado);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Equipos - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php renderEstilosComunes(); ?>
    <style>
        .equipment-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
        }
        .stat-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .equipment-card {
            transition: transform 0.3s;
        }
        .equipment-card:hover {
            transform: translateY(-5px);
        }
        .badge-estado {
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <?php renderNavbar('equipos'); ?>

    <div class="container mt-4">
        <!-- Header -->
        <div class="equipment-header mb-4">
            <h2><i class="fa fa-cogs"></i> Gestión de Equipos</h2>
            <p>Control y seguimiento de equipos de construcción civil</p>
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
                    <h4><i class="fa fa-cogs"></i></h4>
                    <h3><?php echo $total_equipos; ?></h3>
                    <p>Total Equipos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <h4><i class="fa fa-check-circle"></i></h4>
                    <h3><?php echo $estadisticas['operativo'] ?? 0; ?></h3>
                    <p>Operativos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <h4><i class="fa fa-wrench"></i></h4>
                    <h3><?php echo $estadisticas['mantenimiento'] ?? 0; ?></h3>
                    <p>En Mantenimiento</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card">
                    <h4><i class="fa fa-exclamation-triangle"></i></h4>
                    <h3><?php echo $estadisticas['fuera_servicio'] ?? 0; ?></h3>
                    <p>Fuera de Servicio</p>
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fa fa-search"></i> Buscar Equipos</h5>
                    </div>
                    <div class="col-md-6 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarEquipo">
                            <i class="fa fa-plus"></i> Agregar Equipo
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <div class="form-group mr-3">
                        <input type="text" class="form-control" name="filtro" placeholder="Buscar por código, nombre o marca..." 
                               value="<?php echo htmlspecialchars($filtro); ?>" style="width: 300px;">
                    </div>
                    <div class="form-group mr-3">
                        <select name="categoria" class="form-control">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categorias as $categoria): ?>
                                <option value="<?php echo $categoria['id']; ?>" <?php echo $categoria_filtro == $categoria['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info mr-2">
                        <i class="fa fa-search"></i> Buscar
                    </button>
                    <a href="equipos.php" class="btn btn-secondary">
                        <i class="fa fa-refresh"></i> Limpiar
                    </a>
                </form>
            </div>
        </div>

        <!-- Lista de Equipos -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-list"></i> Lista de Equipos</h5>
            </div>
            <div class="card-body">
                <?php if (count($equipos) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Marca/Modelo</th>
                                <th>Categoría</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($equipos as $equipo): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($equipo['codigo']); ?></strong></td>
                                <td><?php echo htmlspecialchars($equipo['nombre']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($equipo['marca'] . ' ' . $equipo['modelo']); ?>
                                    <br><small class="text-muted">S/N: <?php echo htmlspecialchars($equipo['numero_serie']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($equipo['categoria_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($equipo['ubicacion']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo getEstadoColor($equipo['estado']); ?> badge-estado">
                                        <?php echo formatEstado($equipo['estado']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info" onclick="verDetalle(<?php echo $equipo['id']; ?>)" title="Ver Detalle">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="cambiarEstado(<?php echo $equipo['id']; ?>, '<?php echo $equipo['estado']; ?>')" title="Cambiar Estado">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="programarMantenimiento(<?php echo $equipo['id']; ?>)" title="Mantenimiento">
                                            <i class="fa fa-wrench"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa fa-cogs fa-3x text-muted"></i>
                    <h5 class="mt-3">No se encontraron equipos</h5>
                    <p class="text-muted">No hay equipos registrados con los filtros seleccionados.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Navegación rápida -->
        <div class="card">
            <div class="card-body text-center">
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="epp_gestion.php" class="btn btn-success">
                    <i class="fa fa-shield"></i> Gestión de EPPs
                </a>
                <a href="mantenimientos.php" class="btn btn-warning">
                    <i class="fa fa-wrench"></i> Mantenimientos
                </a>
                <a href="reportes.php" class="btn btn-info">
                    <i class="fa fa-chart-bar"></i> Reportes
                </a>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Equipo -->
    <div class="modal fade" id="modalAgregarEquipo" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus"></i> Agregar Nuevo Equipo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="POST">
                    <input type="hidden" name="action" value="agregar_equipo">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Código *</label>
                                    <input type="text" class="form-control" name="codigo" required>
                                    <small class="text-muted">Ejemplo: EXC-001</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Categoría *</label>
                                    <select name="id_categoria" class="form-control" required>
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($categorias as $categoria): ?>
                                            <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['nombre']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nombre del Equipo *</label>
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
                                    <label>Número de Serie</label>
                                    <input type="text" class="form-control" name="numero_serie">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fecha de Adquisición</label>
                                    <input type="date" class="form-control" name="fecha_adquisicion">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Costo de Adquisición</label>
                                    <input type="number" step="0.01" class="form-control" name="costo_adquisicion">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Ubicación</label>
                                    <input type="text" class="form-control" name="ubicacion">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Responsable</label>
                                    <input type="text" class="form-control" name="responsable">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Guardar Equipo
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
        function verDetalle(id) {
            alert('Funcionalidad de ver detalle en desarrollo. ID: ' + id);
        }

        function cambiarEstado(id, estadoActual) {
            const estados = {
                'operativo': 'Operativo',
                'mantenimiento': 'Mantenimiento', 
                'fuera_servicio': 'Fuera de Servicio',
                'dado_baja': 'Dado de Baja'
            };
            
            let opciones = '<option value="">Seleccionar nuevo estado</option>';
            for (let estado in estados) {
                if (estado !== estadoActual) {
                    opciones += '<option value="' + estado + '">' + estados[estado] + '</option>';
                }
            }
            
            const nuevoEstado = prompt('Estado actual: ' + estados[estadoActual] + '\n\nSeleccionar nuevo estado:\n' + 
                                    Object.keys(estados).filter(e => e !== estadoActual).map((e, i) => (i+1) + '. ' + estados[e]).join('\n'));
            
            if (nuevoEstado) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="cambiar_estado">' +
                               '<input type="hidden" name="equipo_id" value="' + id + '">' +
                               '<input type="hidden" name="nuevo_estado" value="' + nuevoEstado + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }

        function programarMantenimiento(id) {
            if (confirm('¿Desea programar un mantenimiento para este equipo?')) {
                window.location.href = 'mantenimientos.php?equipo_id=' + id + '&action=nuevo';
            }
        }
    </script>
</body>
</html>
# Update 1764801942
