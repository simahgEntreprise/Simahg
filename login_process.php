<?php
session_start();

// Configuración de la base de datos
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

try {
    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener datos del formulario
    $usuario = $_POST['usuario'] ?? '';
    $pass = $_POST['password'] ?? '';
    
    if (empty($usuario) || empty($pass)) {
        $_SESSION['error'] = 'Usuario y contraseña son requeridos';
        header('Location: login.php');
        exit();
    }
    
    // Encriptar contraseña (debe coincidir con la BD)
    $password_encrypted = sha1($pass);
    
    // DEBUG TEMPORAL - Descomentar para ver qué está pasando
    // error_log("DEBUG LOGIN - Usuario: $usuario");
    // error_log("DEBUG LOGIN - Password ingresado: $pass");
    // error_log("DEBUG LOGIN - Hash generado: $password_encrypted");
    
    // Buscar usuario
    $sql = "SELECT u.*, p.nombre as perfil_nombre 
            FROM usuarios u 
            JOIN perfiles p ON u.id_perfil = p.id 
            WHERE u.usuario = ? AND u.password = ? AND u.estado = 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario, $password_encrypted]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    // DEBUG TEMPORAL - Descomentar para ver el resultado
    // error_log("DEBUG LOGIN - Usuario encontrado: " . ($user ? "SÍ" : "NO"));
    
    if ($user) {
        // Login exitoso
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->nombre . ' ' . $user->apellidos;
        $_SESSION['email'] = $user->email;
        $_SESSION['usuario'] = $user->usuario;
        $_SESSION['perfil_id'] = $user->id_perfil;
        $_SESSION['perfil_nombre'] = $user->perfil_nombre;
        $_SESSION['login_time'] = time();
        
        // Actualizar último acceso
        $update_sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->execute([$user->id]);
        
        $_SESSION['success'] = '¡Bienvenido ' . $user->nombre . '!';
        
        // Redirigir al dashboard
        header('Location: dashboard.php');
        exit();
        
    } else {
        $_SESSION['error'] = 'Usuario o contraseña incorrectos';
        header('Location: login.php');
        exit();
    }
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error de conexión: ' . $e->getMessage();
    header('Location: login.php');
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = 'Error interno: ' . $e->getMessage();
    header('Location: login.php');
    exit();
}
?>
