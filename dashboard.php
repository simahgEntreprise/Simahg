<?php
session_start();

// Incluir archivo común
require_once 'includes/config_common.php';

// Obtener conexión a BD
$pdo = getDBConnection();

try {
    
    // Obtener estadísticas (solo para admin y supervisor)
    $stats = [];
    $usuarios_perfil = [];
    
    if (puedeGestionar()) {
        // Total usuarios
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 1");
        $stats['usuarios'] = $stmt->fetch()['total'];
        
        // Total perfiles
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM perfiles WHERE estado = 1");
        $stats['perfiles'] = $stmt->fetch()['total'];
        
        // Usuarios por perfil
        $stmt = $pdo->query("SELECT p.nombre, COUNT(u.id) as total 
                            FROM perfiles p 
                            LEFT JOIN usuarios u ON p.id = u.id_perfil AND u.estado = 1
                            WHERE p.estado = 1
                            GROUP BY p.id, p.nombre");
        $usuarios_perfil = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Estadísticas para todos los usuarios (solicitudes propias)
    $mis_solicitudes = 0;
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM solicitudes_epp WHERE id_usuario = ? AND estado != 'cancelada'");
        $stmt->execute([$_SESSION['user_id']]);
        $mis_solicitudes = $stmt->fetch()['total'];
    }
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <?php renderEstilosComunes(); ?>
    <style>
        .welcome-card { 
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 30px;
        }
    </style>
</head>
<body>
    <?php renderNavbar('dashboard'); ?>

    <div class="container mt-4">
        <!-- Mensaje de bienvenida -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Tarjeta de bienvenida -->
        <div class="card welcome-card">
            <div class="row">
                <div class="col-md-9">
                    <h2><i class="fa fa-dashboard"></i> Dashboard SIMAHG</h2>
                    <p>Bienvenido, <strong><?php echo $_SESSION['username']; ?></strong></p>
                    <p>Perfil: <strong><?php echo $_SESSION['perfil_nombre']; ?></strong></p>
                    <p>Último acceso: <strong><?php echo date('d/m/Y H:i', $_SESSION['login_time']); ?></strong></p>
                </div>
                <div class="col-md-3 text-right">
                    <a href="solicitudes_epp.php" class="btn btn-light btn-lg" style="margin-bottom: 10px; width: 100%;">
                        <i class="fa fa-shield"></i> Solicitudes EPP
                    </a>
                    <a href="logout.php" class="btn btn-danger btn-lg" style="width: 100%;">
                        <i class="fa fa-sign-out"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas para operadores y usuarios -->
        <div class="row">
            <div class="col-md-12">
                <div class="card stat-card">
                    <div class="stat-number"><?php echo $mis_solicitudes; ?></div>
                    <div>Mis Solicitudes de EPP Activas</div>
                    <a href="solicitudes_epp.php" class="btn btn-light mt-3">
                        <i class="fa fa-clipboard-list"></i> Ver Mis Solicitudes
                    </a>
                </div>
            </div>
        </div>

        <?php if (puedeGestionar()): ?>
        <!-- Estadísticas administrativas (solo admin y supervisor) -->
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="card stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="stat-number"><?php echo $stats['usuarios']; ?></div>
                    <div>Usuarios Activos</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="stat-number"><?php echo $stats['perfiles']; ?></div>
                    <div>Perfiles de Usuario</div>
                </div>
            </div>
        </div>

        <!-- Usuarios por perfil -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-users"></i> Usuarios por Perfil</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Perfil</th>
                                <th>Total Usuarios</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios_perfil as $row): 
                                $porcentaje = $stats['usuarios'] > 0 ? round(($row['total'] / $stats['usuarios']) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td><?php echo $row['nombre']; ?></td>
                                <td><span class="badge badge-primary"><?php echo $row['total']; ?></span></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?php echo $porcentaje; ?>%">
                                            <?php echo $porcentaje; ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Acceso rápido destacado: Solicitudes de EPPs -->
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; margin-top: 20px;">
            <div class="card-body text-center" style="padding: 40px;">
                <i class="fa fa-clipboard-list fa-5x mb-4" style="opacity: 0.9;"></i>
                <h2>⭐ Módulo Principal: Solicitudes de EPPs</h2>
                <p style="font-size: 1.2rem; margin-top: 15px;">
                    <?php if (esAdmin()): ?>
                        Gestión completa: Aprobar, entregar y administrar todas las solicitudes de Equipos de Protección Personal
                    <?php elseif (esSupervisor()): ?>
                        Aprobar solicitudes y gestionar entregas de Equipos de Protección Personal
                    <?php else: ?>
                        Solicitar y consultar tus Equipos de Protección Personal necesarios para el trabajo
                    <?php endif; ?>
                </p>
                <div style="margin-top: 30px;">
                    <a href="solicitudes_epp.php" class="btn btn-light btn-lg" style="padding: 15px 40px; font-size: 1.2rem;">
                        <i class="fa fa-arrow-circle-right"></i> Acceder a Solicitudes EPP
                    </a>
                </div>
                <div style="margin-top: 20px; opacity: 0.8;">
                    <small>💡 Tip: También puedes acceder desde el menú superior</small>
                </div>
            </div>
        </div>

        <?php if (esAdmin()): ?>
        <!-- Módulos secundarios - SOLO ADMINISTRADOR -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fa fa-lock"></i> Módulos Administrativos</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-users fa-3x text-info"></i>
                                <h5>Gestión de Usuarios</h5>
                                <a href="usuarios.php" class="btn btn-info">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-chart-bar fa-3x text-secondary"></i>
                                <h5>Reportes</h5>
                                <a href="reportes.php" class="btn btn-secondary">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-cogs fa-3x text-dark"></i>
                                <h5>Configuración</h5>
                                <a href="configuracion.php" class="btn btn-dark">Acceder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa fa-database fa-3x text-primary"></i>
                                <h5>Base de Datos</h5>
                                <a href="database.php" class="btn btn-primary">Acceder</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Nota informativa -->
        <div class="alert alert-info text-center">
            <i class="fa fa-info-circle"></i> 
            <strong>Nota:</strong> Usa el menú superior para acceder rápidamente a todos los módulos del sistema.
            <?php if (puedeGestionar()): ?>
            Para ver reportes detallados, utiliza el enlace <strong>"Reportes"</strong> en el menú superior.
            <?php endif; ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
# Update 1764801942
# Update 1764801943
# Update 1764801944
# Update 1764801944
