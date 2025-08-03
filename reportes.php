<?php
/**
 * Módulo de Reportes - SIMAHG
 * Con control de roles y acceso restringido
 */

session_start();

// Verificar si está logueado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// ==================== CONTROL DE ROLES ====================
function esAdmin() {
    return isset($_SESSION['perfil_nombre']) && strtolower($_SESSION['perfil_nombre']) === 'administrador';
}

function esSupervisor() {
    return isset($_SESSION['perfil_nombre']) && strtolower($_SESSION['perfil_nombre']) === 'supervisor';
}

function esOperador() {
    return isset($_SESSION['perfil_nombre']) && strtolower($_SESSION['perfil_nombre']) === 'operador';
}

function puedeGestionar() {
    return esAdmin() || esSupervisor();
}

// VERIFICAR PERMISO: Solo Admin y Supervisor pueden ver reportes
if (!puedeGestionar()) {
    $_SESSION['error'] = 'No tienes permisos para acceder a esta sección.';
    header('Location: dashboard.php');
    exit();
}
// ========================================================

$userId = $_SESSION['user_id'];
$userName = $_SESSION['username'];
$userRole = $_SESSION['perfil_nombre'];

// Configuración de la base de datos
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ==================== ESTADÍSTICAS DE SOLICITUDES EPP ====================
    
    // Total solicitudes por estado
    $stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM solicitudes_epp GROUP BY estado");
    $solicitudes_estado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Total de solicitudes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM solicitudes_epp");
    $total_solicitudes = $stmt->fetch()['total'];
    
    // Solicitudes pendientes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM solicitudes_epp WHERE estado = 'PENDIENTE'");
    $solicitudes_pendientes = $stmt->fetch()['total'];
    
    // EPPs más solicitados
    $stmt = $pdo->query("
        SELECT e.nombre, e.codigo, COUNT(s.id) as total_solicitudes
        FROM solicitudes_epp s
        INNER JOIN epp_items e ON s.id_epp = e.id
        GROUP BY e.id
        ORDER BY total_solicitudes DESC
        LIMIT 10
    ");
    $epps_mas_solicitados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Últimas solicitudes
    $stmt = $pdo->query("
        SELECT s.*, e.nombre as epp_nombre, u.nombre as solicitante_nombre, u.apellidos
        FROM solicitudes_epp s
        INNER JOIN epp_items e ON s.id_epp = e.id
        INNER JOIN usuarios u ON s.id_usuario = u.id
        ORDER BY s.fecha_solicitud DESC
        LIMIT 15
    ");
    $ultimas_solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Solicitudes por usuario
    $stmt = $pdo->query("
        SELECT u.nombre, u.apellidos, COUNT(s.id) as total_solicitudes
        FROM usuarios u
        LEFT JOIN solicitudes_epp s ON u.id = s.id_usuario
        GROUP BY u.id
        HAVING total_solicitudes > 0
        ORDER BY total_solicitudes DESC
        LIMIT 10
    ");
    $usuarios_solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // ==================== ESTADÍSTICAS DE USUARIOS (Solo Admin) ====================
    
    if (esAdmin()) {
        // Total usuarios activos/inactivos
        $stmt = $pdo->query("SELECT estado, COUNT(*) as total FROM usuarios GROUP BY estado");
        $usuarios_estado = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        // Usuarios por perfil
        $stmt = $pdo->query("SELECT p.nombre, COUNT(u.id) as total 
                            FROM perfiles p 
                            LEFT JOIN usuarios u ON p.id = u.id_perfil 
                            GROUP BY p.id, p.nombre 
                            ORDER BY total DESC");
        $usuarios_perfil = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // ==================== INVENTARIO EPP ====================
    
    // EPPs con stock bajo (menos de 10)
    $stmt = $pdo->query("
        SELECT e.nombre, e.codigo, e.stock_actual, e.stock_minimo, c.nombre as categoria
        FROM epp_items e
        LEFT JOIN categorias_epp c ON e.id_categoria = c.id
        WHERE e.stock_actual < 10
        ORDER BY e.stock_actual ASC
    ");
    $stock_bajo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Total EPPs por categoría
    $stmt = $pdo->query("
        SELECT c.nombre as categoria, COUNT(e.id) as total_items, SUM(e.stock_actual) as stock_total
        FROM categorias_epp c
        LEFT JOIN epp_items e ON c.id = e.id_categoria
        GROUP BY c.id
        ORDER BY total_items DESC
    ");
    $epps_categoria = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $error = "Error de base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reportes - SIMAHG</title>
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .navbar-brand, .navbar-nav li a { color: white !important; }
        
        /* Fix para dropdown del usuario - VISIBLE */
        .navbar-default .navbar-nav > .open > a,
        .navbar-default .navbar-nav > .open > a:hover,
        .navbar-default .navbar-nav > .open > a:focus {
            background-color: rgba(255,255,255,0.1) !important;
            color: white !important;
        }
        
        .dropdown-menu {
            background-color: #ffffff !important;
            border: 1px solid rgba(0,0,0,0.15) !important;
            box-shadow: 0 6px 12px rgba(0,0,0,0.175) !important;
        }
        
        .dropdown-menu > li > a {
            color: #333333 !important;
            padding: 10px 20px !important;
            display: block !important;
            text-decoration: none !important;
        }
        
        .dropdown-menu > li > a:hover,
        .dropdown-menu > li > a:focus {
            background-color: #f5f5f5 !important;
            color: #667eea !important;
        }
        
        .dropdown-menu > li > a > i {
            margin-right: 8px;
            color: #667eea !important;
        }
        
        .card { border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); margin-bottom: 20px; }
        .stat-card {
            text-align: center;
            padding: 30px;
        }
        .stat-number { font-size: 2.5rem; font-weight: bold; }
        .chart-card { min-height: 300px; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-default" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; margin-bottom: 30px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="dashboard.php" style="color: white;">
                    <i class="fa fa-dashboard"></i> SIMAHG
                </a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="dashboard.php" style="color: white !important;"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="solicitudes_epp.php" style="color: white !important;"><i class="fa fa-shield"></i> Solicitudes EPP</a></li>
                <?php if (puedeGestionar()): ?>
                <li><a href="equipos.php" style="color: white !important;"><i class="fa fa-cogs"></i> Equipos</a></li>
                <li><a href="mantenimientos.php" style="color: white !important;"><i class="fa fa-wrench"></i> Mantenimientos</a></li>
                <li><a href="epp_gestion.php" style="color: white !important;"><i class="fa fa-cubes"></i> Inventario EPP</a></li>
                <li class="active"><a href="reportes.php" style="color: white !important;"><i class="fa fa-bar-chart"></i> Reportes</a></li>
                <?php endif; ?>
                <?php if (esAdmin()): ?>
                <li><a href="usuarios.php" style="color: white !important;"><i class="fa fa-users"></i> Usuarios</a></li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white !important;">
                        <i class="fa fa-user"></i> <?php echo $userName; ?> (<?php echo $userRole; ?>) <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="solicitudes_epp.php"><i class="fa fa-shield"></i> Mis Solicitudes</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid" style="padding: 20px;">
        
        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fa fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>
        
        <!-- Título -->
        <div class="row">
            <div class="col-md-9">
                <h2><i class="fa fa-bar-chart"></i> Reportes y Estadísticas - SIMAHG</h2>
                <p class="text-muted">Panel de análisis y seguimiento de solicitudes EPP</p>
            </div>
            <div class="col-md-3 text-right">
                <a href="solicitudes_epp.php" class="btn btn-primary btn-lg">
                    <i class="fa fa-shield"></i> Ver Solicitudes
                </a>
            </div>
        </div>
        <hr>

        <!-- Estadísticas principales de Solicitudes EPP -->
        <div class="row">
            <div class="col-md-3">
                <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_solicitudes; ?></div>
                        <div>Total Solicitudes</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $solicitudes_pendientes; ?></div>
                        <div>Pendientes de Aprobar</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($epps_mas_solicitados); ?></div>
                        <div>EPPs en Catálogo</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                    <div class="stat-card">
                        <div class="stat-number"><?php echo count($stock_bajo); ?></div>
                        <div>Alertas de Stock</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Solicitudes por Estado -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <h5><i class="fa fa-pie-chart"></i> Solicitudes por Estado</h5>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if (!empty($solicitudes_estado)): ?>
                            <?php 
                            $colores = [
                                'PENDIENTE' => '#ffc107',
                                'APROBADA' => '#28a745',
                                'RECHAZADA' => '#dc3545',
                                'ENTREGADA' => '#17a2b8'
                            ];
                            $total = array_sum(array_column($solicitudes_estado, 'total'));
                            foreach ($solicitudes_estado as $estado): 
                                $porcentaje = $total > 0 ? round(($estado['total'] / $total) * 100, 1) : 0;
                                $color = $colores[$estado['estado']] ?? '#6c757d';
                            ?>
                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                    <span><strong><?php echo $estado['estado']; ?></strong></span>
                                    <span><?php echo $estado['total']; ?> (<?php echo $porcentaje; ?>%)</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" style="width: <?php echo $porcentaje; ?>%; background-color: <?php echo $color; ?>"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No hay solicitudes registradas aún.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white;">
                        <h5><i class="fa fa-trophy"></i> Top 10 - EPPs Más Solicitados</h5>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if (!empty($epps_mas_solicitados)): ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>EPP</th>
                                        <th>Código</th>
                                        <th class="text-center">Solicitudes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($epps_mas_solicitados as $epp): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $epp['nombre']; ?></td>
                                        <td><code><?php echo $epp['codigo']; ?></code></td>
                                        <td class="text-center"><span class="badge badge-primary"><?php echo $epp['total_solicitudes']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">No hay datos disponibles.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Últimas Solicitudes -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                <h5><i class="fa fa-history"></i> Últimas 15 Solicitudes</h5>
            </div>
            <div class="card-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Solicitante</th>
                                <th>EPP</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ultimas_solicitudes)): ?>
                                <?php foreach ($ultimas_solicitudes as $sol): ?>
                                <tr>
                                    <td><?php echo $sol['id']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($sol['fecha_solicitud'])); ?></td>
                                    <td><?php echo $sol['solicitante_nombre'] . ' ' . $sol['apellidos']; ?></td>
                                    <td><?php echo $sol['epp_nombre']; ?></td>
                                    <td><?php echo $sol['cantidad']; ?></td>
                                    <td>
                                        <?php
                                        $badges = [
                                            'PENDIENTE' => 'badge-warning',
                                            'APROBADA' => 'badge-success',
                                            'RECHAZADA' => 'badge-danger',
                                            'ENTREGADA' => 'badge-info'
                                        ];
                                        $badge_class = $badges[$sol['estado']] ?? 'badge-secondary';
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $sol['estado']; ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No hay solicitudes registradas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Alertas de Stock Bajo -->
        <?php if (!empty($stock_bajo)): ?>
        <div class="card" style="border-left: 4px solid #dc3545;">
            <div class="card-header bg-danger text-white">
                <h5><i class="fa fa-exclamation-triangle"></i> ⚠️ Alertas - EPPs con Stock Bajo</h5>
            </div>
            <div class="card-body" style="padding: 20px;">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th class="text-center">Stock Actual</th>
                                <th class="text-center">Stock Mínimo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stock_bajo as $item): ?>
                            <tr>
                                <td><code><?php echo $item['codigo']; ?></code></td>
                                <td><?php echo $item['nombre']; ?></td>
                                <td><?php echo $item['categoria']; ?></td>
                                <td class="text-center">
                                    <span class="badge badge-danger"><?php echo $item['stock_actual']; ?></span>
                                </td>
                                <td class="text-center"><?php echo $item['stock_minimo']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning" onclick="alert('Función de reabastecimiento en desarrollo')">
                                        <i class="fa fa-shopping-cart"></i> Reabastecer
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Usuarios más Activos -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                        <h5><i class="fa fa-users"></i> Top 10 - Usuarios Más Activos</h5>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if (!empty($usuarios_solicitudes)): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Usuario</th>
                                        <th class="text-center">Solicitudes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; foreach ($usuarios_solicitudes as $u): ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo $u['nombre'] . ' ' . $u['apellidos']; ?></td>
                                        <td class="text-center"><span class="badge badge-primary"><?php echo $u['total_solicitudes']; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">No hay datos disponibles.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                        <h5><i class="fa fa-cubes"></i> EPPs por Categoría</h5>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <?php if (!empty($epps_categoria)): ?>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Categoría</th>
                                        <th class="text-center">Items</th>
                                        <th class="text-center">Stock Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($epps_categoria as $cat): ?>
                                    <tr>
                                        <td><?php echo $cat['categoria']; ?></td>
                                        <td class="text-center"><span class="badge badge-info"><?php echo $cat['total_items']; ?></span></td>
                                        <td class="text-center"><span class="badge badge-success"><?php echo $cat['stock_total'] ?? 0; ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">No hay categorías registradas.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-3">
                <button class="btn btn-primary btn-lg btn-block" onclick="window.print()">
                    <i class="fa fa-print"></i> Imprimir Reporte
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-success btn-lg btn-block" onclick="exportToCSV()">
                    <i class="fa fa-download"></i> Exportar CSV
                </button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-info btn-lg btn-block" onclick="refreshData()">
                    <i class="fa fa-refresh"></i> Actualizar Datos
                </button>
            </div>
            <div class="col-md-3">
                <a href="dashboard.php" class="btn btn-secondary btn-lg btn-block">
                    <i class="fa fa-home"></i> Volver al Dashboard
                </a>
            </div>
        </div>
        
        <br><br>
    </div>

    <!-- Scripts -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    
    <script>
        function exportToCSV() {
            alert('Función de exportación CSV en desarrollo');
        }
        
        function refreshData() {
            location.reload();
        }
    </script>
</body>
</html>
# Update 1764801942
# Update 1764801943
# Update 1764801945
# Update 1764801946
