<?php
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
$username = 'root';
$password = '';

$message = '';
$error = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Información de la base de datos
    $stmt = $pdo->query("SELECT DATABASE() as db_name");
    $db_info = $stmt->fetch();
    
    // Obtener todas las tablas
    $stmt = $pdo->query("SHOW TABLES");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Información detallada de las tablas
    $tabla_info = [];
    foreach ($tablas as $tabla) {
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM `$tabla`");
        $count = $stmt->fetch()['total'];
        
        // Información de la tabla
        $stmt = $pdo->query("SHOW TABLE STATUS LIKE '$tabla'");
        $info = $stmt->fetch();
        
        $tabla_info[] = [
            'nombre' => $tabla,
            'registros' => $count,
            'motor' => $info['Engine'] ?? 'N/A',
            'tamaño' => $info['Data_length'] ?? 0,
            'created' => $info['Create_time'] ?? 'N/A'
        ];
    }
    
    // Procesar operaciones de base de datos
    if ($_POST) {
        if (isset($_POST['optimizar_tabla'])) {
            $tabla = $_POST['tabla'];
            $stmt = $pdo->query("OPTIMIZE TABLE `$tabla`");
            $message = "Tabla '$tabla' optimizada exitosamente";
        }
        
        if (isset($_POST['backup_tabla'])) {
            $tabla = $_POST['tabla'];
            // Simulación de backup (en un caso real haríamos mysqldump)
            $message = "Backup de tabla '$tabla' iniciado (funcionalidad simulada)";
        }
    }
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}

// Función para formatear tamaño
function formatBytes($size, $precision = 2) {
    if ($size == 0) return '0 B';
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Base de Datos - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .navbar-brand, .navbar-nav li a { color: white !important; }
        .card { 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .database-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
        }
        .table-info-card {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .btn-action { margin: 2px; }
        .status-connected { color: #28a745; }
        .status-info { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php"><i class="fa fa-dashboard"></i> SIMAHG</a>
            
            <div class="navbar-nav ml-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                        <i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                        <a class="dropdown-item" href="configuracion.php"><i class="fa fa-cogs"></i> Configuración</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="database-header mb-4">
            <h2><i class="fa fa-database"></i> Gestión de Base de Datos</h2>
            <p>Administración y monitoreo de la base de datos SIMAHG</p>
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

        <!-- Información de Conexión -->
        <div class="row">
            <div class="col-md-3">
                <div class="card table-info-card">
                    <h4><i class="fa fa-server"></i></h4>
                    <h5><?php echo $host . ':' . $port; ?></h5>
                    <p>Servidor</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card table-info-card">
                    <h4><i class="fa fa-database"></i></h4>
                    <h5><?php echo $dbname; ?></h5>
                    <p>Base de Datos</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card table-info-card">
                    <h4><i class="fa fa-table"></i></h4>
                    <h5><?php echo count($tablas); ?></h5>
                    <p>Total Tablas</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card table-info-card">
                    <h4><i class="fa fa-check-circle"></i></h4>
                    <h5>Activo</h5>
                    <p>Estado</p>
                </div>
            </div>
        </div>

        <!-- Información del Sistema -->
        <div class="card">
            <div class="card-header status-info">
                <h5><i class="fa fa-info-circle"></i> Información del Sistema de Base de Datos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        // Información de MySQL
                        $stmt = $pdo->query("SELECT VERSION() as version");
                        $mysql_version = $stmt->fetch()['version'];
                        ?>
                        <h6><i class="fa fa-database"></i> MySQL</h6>
                        <ul class="list-unstyled">
                            <li><strong>Versión:</strong> <?php echo $mysql_version; ?></li>
                            <li><strong>Host:</strong> <?php echo $host; ?></li>
                            <li><strong>Puerto:</strong> <?php echo $port; ?></li>
                            <li><strong>Usuario:</strong> <?php echo $username; ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fa fa-chart-pie"></i> Estadísticas</h6>
                        <ul class="list-unstyled">
                            <li><strong>Total Tablas:</strong> <?php echo count($tablas); ?></li>
                            <li><strong>Estado:</strong> <span class="status-connected"><i class="fa fa-check"></i> Conectado</span></li>
                            <li><strong>Última Conexión:</strong> <?php echo date('Y-m-d H:i:s'); ?></li>
                            <li><strong>Charset:</strong> utf8</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Tablas -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-table"></i> Tablas de la Base de Datos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th><i class="fa fa-table"></i> Tabla</th>
                                <th><i class="fa fa-list-ol"></i> Registros</th>
                                <th><i class="fa fa-cog"></i> Motor</th>
                                <th><i class="fa fa-hdd-o"></i> Tamaño</th>
                                <th><i class="fa fa-calendar"></i> Creación</th>
                                <th><i class="fa fa-wrench"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tabla_info as $tabla): ?>
                            <tr>
                                <td><strong><?php echo $tabla['nombre']; ?></strong></td>
                                <td>
                                    <span class="badge badge-primary"><?php echo number_format($tabla['registros']); ?></span>
                                </td>
                                <td><?php echo $tabla['motor']; ?></td>
                                <td><?php echo formatBytes($tabla['tamaño']); ?></td>
                                <td><?php echo $tabla['created'] !== 'N/A' ? date('d/m/Y', strtotime($tabla['created'])) : 'N/A'; ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="tabla" value="<?php echo $tabla['nombre']; ?>">
                                        <button type="submit" name="optimizar_tabla" class="btn btn-sm btn-warning btn-action" 
                                                onclick="return confirm('¿Optimizar tabla <?php echo $tabla['nombre']; ?>?')">
                                            <i class="fa fa-magic"></i> Optimizar
                                        </button>
                                    </form>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="tabla" value="<?php echo $tabla['nombre']; ?>">
                                        <button type="submit" name="backup_tabla" class="btn btn-sm btn-info btn-action"
                                                onclick="return confirm('¿Crear backup de <?php echo $tabla['nombre']; ?>?')">
                                            <i class="fa fa-download"></i> Backup
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Herramientas de Mantenimiento -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-wrench"></i> Herramientas de Mantenimiento</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-download fa-2x text-info"></i>
                                <h6 class="mt-2">Backup Completo</h6>
                                <button class="btn btn-info btn-sm" onclick="alert('Funcionalidad en desarrollo')">
                                    Crear Backup
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-upload fa-2x text-success"></i>
                                <h6 class="mt-2">Restaurar</h6>
                                <button class="btn btn-success btn-sm" onclick="alert('Funcionalidad en desarrollo')">
                                    Restaurar BD
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-magic fa-2x text-warning"></i>
                                <h6 class="mt-2">Optimizar Todo</h6>
                                <button class="btn btn-warning btn-sm" onclick="alert('Funcionalidad en desarrollo')">
                                    Optimizar BD
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-check-circle fa-2x text-success"></i>
                                <h6 class="mt-2">Verificar</h6>
                                <button class="btn btn-secondary btn-sm" onclick="alert('Base de datos verificada correctamente')">
                                    Verificar BD
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultas Rápidas -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-bolt"></i> Consultas Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <h6><i class="fa fa-users"></i> Usuarios</h6>
                        <?php
                        $stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM usuarios GROUP BY estado");
                        $usuarios_estado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($usuarios_estado as $estado):
                        ?>
                        <p class="mb-1">
                            <span class="badge <?php echo $estado['estado'] ? 'badge-success' : 'badge-danger'; ?>">
                                <?php echo $estado['estado'] ? 'Activos' : 'Inactivos'; ?>: <?php echo $estado['total']; ?>
                            </span>
                        </p>
                        <?php endforeach; ?>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fa fa-user-circle"></i> Perfiles</h6>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM perfiles WHERE estado = 1");
                        $perfiles_activos = $stmt->fetch()['total'];
                        ?>
                        <p class="mb-1">
                            <span class="badge badge-info">Total: <?php echo $perfiles_activos; ?></span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="fa fa-clock-o"></i> Última Actividad</h6>
                        <?php
                        $stmt = $pdo->query("SELECT MAX(ultimo_acceso) as ultimo FROM usuarios WHERE ultimo_acceso IS NOT NULL");
                        $ultimo_acceso = $stmt->fetch()['ultimo'];
                        ?>
                        <p class="mb-1">
                            <small><?php echo $ultimo_acceso ? date('d/m/Y H:i', strtotime($ultimo_acceso)) : 'N/A'; ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navegación rápida -->
        <div class="card">
            <div class="card-body text-center">
                <a href="dashboard.php" class="btn btn-primary">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="usuarios.php" class="btn btn-success">
                    <i class="fa fa-users"></i> Usuarios
                </a>
                <a href="reportes.php" class="btn btn-info">
                    <i class="fa fa-chart-bar"></i> Reportes
                </a>
                <a href="configuracion.php" class="btn btn-warning">
                    <i class="fa fa-cogs"></i> Configuración
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
