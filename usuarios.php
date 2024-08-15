<?php
/**
 * Módulo de Gestión de Usuarios - SIMAHG
 * Solo accesi        <!-- Título -->
        <div class="row">
            <div class="col-md-9">
                <h2><i class="fa fa-users"></i> Gestión de Usuarios - SIMAHG</h2>
                <p class="text-muted">Administración completa de usuarios y perfiles del sistema</p>
            </div>
            <div class="col-md-3 text-right">
                <a href="dashboard.php" class="btn btn-secondary btn-lg">
                    <i class="fa fa-arrow-left"></i> Volver al Dashboard
                </a>
            </div>
        </div>
        <hr>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5><i class="fa fa-list"></i> Lista de Usuarios del Sistema</h5>
            </div>nistradores
 */

session_start();

// Incluir configuración común
require_once 'includes/config_common.php';

// VERIFICAR PERMISO: Solo Administradores
if (!esAdmin()) {
    $_SESSION['error'] = 'No tienes permisos para acceder a esta sección. Solo Administradores.';
    header('Location: dashboard.php');
    exit();
}

// Conexión a la base de datos
$pdo = getDBConnection();

try {
    // Obtener todos los usuarios
    $stmt = $pdo->query("SELECT u.*, p.nombre as perfil_nombre 
                        FROM usuarios u 
                        JOIN perfiles p ON u.id_perfil = p.id 
                        ORDER BY u.id ASC");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener perfiles disponibles
    $stmt2 = $pdo->query("SELECT * FROM perfiles WHERE estado = 1 ORDER BY nombre");
    $perfiles = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Usuarios - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php renderEstilosComunes(); ?>
    <style>
        .badge-activo { background-color: #28a745; }
        .badge-inactivo { background-color: #dc3545; }
    </style>
</head>
<body>

<?php renderNavbar('usuarios'); ?>

<div class="container-fluid" style="padding: 20px;">
    
    <?php if (isset($_SESSION['success'])): ?>
        <?php mostrarAlerta('success', $_SESSION['success']); unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <?php mostrarAlerta('error', $error); ?>
    <?php endif; ?>

    <div class="container mt-4">
        <!-- Título -->
        <div class="row">
            <div class="col-12">
                <h2><i class="fa fa-users"></i> Gestión de Usuarios</h2>
                <hr>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-list"></i> Lista de Usuarios</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Nombre Completo</th>
                                <th>Email</th>
                                <th>Perfil</th>
                                <th>Estado</th>
                                <th>Último Acceso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><strong><?php echo $user['usuario']; ?></strong></td>
                                <td><?php echo $user['nombre'] . ' ' . $user['apellidos']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><span class="badge badge-info"><?php echo $user['perfil_nombre']; ?></span></td>
                                <td>
                                    <?php if ($user['estado'] == 1): ?>
                                        <span class="badge badge-activo">Activo</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactivo">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    echo $user['ultimo_acceso'] 
                                        ? date('d/m/Y H:i', strtotime($user['ultimo_acceso']))
                                        : 'Nunca';
                                    ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <?php if ($user['estado'] == 1): ?>
                                        <button class="btn btn-sm btn-warning" title="Desactivar">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-success" title="Activar">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
# Update 1764801941
