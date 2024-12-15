<?php
/**
 * Archivo de configuración común para todos los módulos de SIMAHG
 * Incluye: Control de roles, funciones comunes, navbar
 */

// Verificar sesión
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

// Variables de sesión
$userId = $_SESSION['user_id'];
$userName = $_SESSION['username'];
$userRole = isset($_SESSION['perfil_nombre']) ? trim($_SESSION['perfil_nombre']) : 'Sin Rol';

// ==================== FUNCIONES DE CONTROL DE ROLES ====================
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

// ==================== FUNCIÓN PARA RENDERIZAR NAVBAR ====================
function renderNavbar($paginaActual = '') {
    global $userName, $userRole;
    ?>
    <!-- Navbar -->
    <nav class="navbar navbar-default" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; margin-bottom: 30px;">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="dashboard.php" style="color: white;">
                    <i class="fa fa-dashboard"></i> SIMAHG
                </a>
            </div>
            <ul class="nav navbar-nav">
                <li <?php echo ($paginaActual == 'dashboard') ? 'class="active"' : ''; ?>>
                    <a href="dashboard.php" style="color: white !important;"><i class="fa fa-dashboard"></i> Dashboard</a>
                </li>
                <li <?php echo ($paginaActual == 'solicitudes') ? 'class="active"' : ''; ?>>
                    <a href="solicitudes_epp.php" style="color: white !important;"><i class="fa fa-shield"></i> Solicitudes EPP</a>
                </li>
                <?php if (puedeGestionar()): ?>
                <li <?php echo ($paginaActual == 'equipos') ? 'class="active"' : ''; ?>>
                    <a href="equipos.php" style="color: white !important;"><i class="fa fa-cogs"></i> Equipos</a>
                </li>
                <li <?php echo ($paginaActual == 'mantenimientos') ? 'class="active"' : ''; ?>>
                    <a href="mantenimientos.php" style="color: white !important;"><i class="fa fa-wrench"></i> Mantenimientos</a>
                </li>
                <li <?php echo ($paginaActual == 'epp_gestion') ? 'class="active"' : ''; ?>>
                    <a href="epp_gestion.php" style="color: white !important;"><i class="fa fa-cubes"></i> Inventario EPP</a>
                </li>
                <li <?php echo ($paginaActual == 'reportes') ? 'class="active"' : ''; ?>>
                    <a href="reportes.php" style="color: white !important;"><i class="fa fa-bar-chart"></i> Reportes</a>
                </li>
                <?php endif; ?>
                <?php if (esAdmin()): ?>
                <li <?php echo ($paginaActual == 'usuarios') ? 'class="active"' : ''; ?>>
                    <a href="usuarios.php" style="color: white !important;"><i class="fa fa-users"></i> Usuarios</a>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: white !important;">
                        <i class="fa fa-user"></i> <?php echo $userName; ?> (<?php echo $userRole; ?>) <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="solicitudes_epp.php"><i class="fa fa-shield"></i> Mis Solicitudes</a></li>
                        <li><a href="cambiar_password.php"><i class="fa fa-key"></i> Cambiar Contraseña</a></li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out"></i> Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <?php
}

// ==================== ESTILOS COMUNES ====================
function renderEstilosComunes() {
    ?>
    <style>
        body { background-color: #f8f9fa; }
        .navbar { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            margin-bottom: 30px;
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
        
        .card { 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .stat-card {
            text-align: center;
            padding: 20px;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-aprobada { background-color: #28a745; }
        .badge-rechazada { background-color: #dc3545; }
        .badge-entregada { background-color: #17a2b8; }
        .modal-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .btn-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }
    </style>
    <?php
}

// ==================== CONFIGURACIÓN DE BASE DE DATOS ====================
/**
 * Configuración Centralizada de Base de Datos
 * Para migrar a BD remota, solo cambia estas variables
 */
function getDBConnection() {
    // ====================================================================
    // CONFIGURACIÓN LOCAL (XAMPP) - ACTIVA ✅
    // ====================================================================
    $host = 'localhost';
    $port = '3307';
    $dbname = 'simahg_db';
    $username = 'root';
    $password = '';
    
    // ====================================================================
    // CONFIGURACIÓN REMOTA (Base de Datos en la Nube) - INACTIVA ⏸️
    // ====================================================================
    // Descomenta estas líneas cuando quieras usar una BD remota
    // y comenta las líneas de configuración LOCAL de arriba
    
    /*
    // Ejemplo con FreeSQLDatabase.com
    $host = 'sql.freedb.tech';
    $port = '3306';
    $dbname = 'freedb_simahg_12345';  // Reemplaza con tu nombre de BD
    $username = 'freedb_usuario123';   // Reemplaza con tu usuario
    $password = 'TuPassword123!';      // Reemplaza con tu contraseña
    */
    
    /*
    // Ejemplo con db4free.net
    $host = 'db4free.net';
    $port = '3306';
    $dbname = 'simahg_remoto';         // Reemplaza con tu nombre de BD
    $username = 'tu_usuario';          // Reemplaza con tu usuario
    $password = 'tu_password';         // Reemplaza con tu contraseña
    */
    
    /*
    // Ejemplo con Railway.app o PlanetScale
    $host = 'tu-proyecto.railway.app'; // Reemplaza con tu host
    $port = '3306';
    $dbname = 'railway';               // Reemplaza según el servicio
    $username = 'root';                // Usuario del servicio
    $password = 'tu_password_largo';   // Contraseña generada
    */
    
    // ====================================================================
    // CONEXIÓN PDO - NO MODIFICAR ESTA SECCIÓN
    // ====================================================================
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        die("❌ Error de conexión a la base de datos: " . $e->getMessage() . "<br>Host: $host | Puerto: $port | BD: $dbname");
    }
}

// ==================== FUNCIONES ÚTILES ====================
function sanitizar($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatearFecha($fecha, $formato = 'd/m/Y H:i') {
    if (!$fecha) return '-';
    return date($formato, strtotime($fecha));
}

function mostrarAlerta($tipo = 'success', $mensaje = '') {
    $iconos = [
        'success' => 'fa-check-circle',
        'error' => 'fa-exclamation-circle',
        'warning' => 'fa-exclamation-triangle',
        'info' => 'fa-info-circle'
    ];
    $icon = $iconos[$tipo] ?? 'fa-info-circle';
    ?>
    <div class="alert alert-<?php echo $tipo; ?> alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa <?php echo $icon; ?>"></i> <?php echo $mensaje; ?>
    </div>
    <?php
}
?>
# Update 1764801942
# Update 1764801943
