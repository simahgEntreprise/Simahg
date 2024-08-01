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

// Procesar formularios
if ($_POST) {
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if (isset($_POST['cambiar_password'])) {
            $password_actual = $_POST['password_actual'];
            $password_nuevo = $_POST['password_nuevo'];
            $password_confirmar = $_POST['password_confirmar'];
            
            if ($password_nuevo !== $password_confirmar) {
                $error = "Las contraseñas nuevas no coinciden";
            } else {
                // Verificar contraseña actual
                $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $user = $stmt->fetch();
                
                if (password_verify($password_actual, $user['password'])) {
                    // Actualizar contraseña
                    $hash = password_hash($password_nuevo, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                    $stmt->execute([$hash, $_SESSION['user_id']]);
                    $message = "Contraseña actualizada exitosamente";
                } else {
                    $error = "La contraseña actual es incorrecta";
                }
            }
        }
        
        if (isset($_POST['actualizar_perfil'])) {
            $nombre = trim($_POST['nombre']);
            $apellidos = trim($_POST['apellidos']);
            $email = trim($_POST['email']);
            
            if (empty($nombre) || empty($apellidos) || empty($email)) {
                $error = "Todos los campos son obligatorios";
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellidos = ?, email = ? WHERE id = ?");
                $stmt->execute([$nombre, $apellidos, $email, $_SESSION['user_id']]);
                
                // Actualizar datos de sesión
                $_SESSION['username'] = $nombre . ' ' . $apellidos;
                $message = "Perfil actualizado exitosamente";
            }
        }
        
    } catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}

// Obtener datos del usuario
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Configuraciones del sistema
    $stmt = $pdo->query("SELECT COUNT(*) as total_usuarios FROM usuarios");
    $total_usuarios = $stmt->fetch()['total_usuarios'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total_perfiles FROM perfiles");
    $total_perfiles = $stmt->fetch()['total_perfiles'];
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configuración - SIMAHG</title>
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
        .config-header {
            background: linear-gradient(135deg, #ff7b7b 0%, #667eea 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
        }
        .form-group label { font-weight: bold; color: #555; }
        .btn-custom { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
        .stats-card {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            text-align: center;
            padding: 20px;
        }
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
                        <a class="dropdown-item" href="usuarios.php"><i class="fa fa-users"></i> Usuarios</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="config-header mb-4">
            <h2><i class="fa fa-cogs"></i> Configuración del Sistema</h2>
            <p>Configuración y personalización de SIMAHG</p>
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

        <div class="row">
            <!-- Estadísticas del sistema -->
            <div class="col-md-4">
                <div class="card stats-card">
                    <h4><i class="fa fa-users"></i></h4>
                    <h3><?php echo $total_usuarios; ?></h3>
                    <p>Total Usuarios</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card">
                    <h4><i class="fa fa-user-circle"></i></h4>
                    <h3><?php echo $total_perfiles; ?></h3>
                    <p>Perfiles Definidos</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stats-card">
                    <h4><i class="fa fa-server"></i></h4>
                    <h3>v1.0</h3>
                    <p>Versión Sistema</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Configuración de Perfil -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa fa-user"></i> Mi Perfil</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Nombre:</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Apellidos:</label>
                                <input type="text" class="form-control" name="apellidos" value="<?php echo htmlspecialchars($usuario['apellidos']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Usuario:</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario['usuario']); ?>" readonly>
                            </div>
                            <button type="submit" name="actualizar_perfil" class="btn btn-custom">
                                <i class="fa fa-save"></i> Actualizar Perfil
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fa fa-lock"></i> Cambiar Contraseña</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Contraseña Actual:</label>
                                <input type="password" class="form-control" name="password_actual" required>
                            </div>
                            <div class="form-group">
                                <label>Contraseña Nueva:</label>
                                <input type="password" class="form-control" name="password_nuevo" required>
                            </div>
                            <div class="form-group">
                                <label>Confirmar Contraseña:</label>
                                <input type="password" class="form-control" name="password_confirmar" required>
                            </div>
                            <button type="submit" name="cambiar_password" class="btn btn-warning">
                                <i class="fa fa-key"></i> Cambiar Contraseña
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuraciones del Sistema -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-cogs"></i> Configuraciones del Sistema</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fa fa-database"></i> Base de Datos</h6>
                        <ul class="list-unstyled">
                            <li><strong>Host:</strong> <?php echo $host; ?></li>
                            <li><strong>Puerto:</strong> <?php echo $port; ?></li>
                            <li><strong>Base de Datos:</strong> <?php echo $dbname; ?></li>
                            <li><strong>Estado:</strong> <span class="text-success"><i class="fa fa-check"></i> Conectado</span></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fa fa-info-circle"></i> Información del Sistema</h6>
                        <ul class="list-unstyled">
                            <li><strong>Versión PHP:</strong> <?php echo PHP_VERSION; ?></li>
                            <li><strong>Servidor:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?></li>
                            <li><strong>Sistema:</strong> <?php echo PHP_OS; ?></li>
                            <li><strong>Memoria:</strong> <?php echo ini_get('memory_limit'); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-bolt"></i> Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="usuarios.php" class="btn btn-primary btn-block">
                            <i class="fa fa-users"></i> Gestionar Usuarios
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="reportes.php" class="btn btn-success btn-block">
                            <i class="fa fa-chart-bar"></i> Ver Reportes
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="database.php" class="btn btn-info btn-block">
                            <i class="fa fa-database"></i> Base de Datos
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="dashboard.php" class="btn btn-secondary btn-block">
                            <i class="fa fa-dashboard"></i> Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
