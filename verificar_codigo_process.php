<?php
session_start();

// Configuración de la base de datos
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar que haya una sesión de recuperación activa
    if (!isset($_SESSION['recovery_user_id'])) {
        $_SESSION['error'] = 'Sesión de recuperación no válida';
        header('Location: recuperar_password.php');
        exit();
    }
    
    $usuario_id = $_SESSION['recovery_user_id'];
    $codigo_ingresado = $_POST['codigo'] ?? '';
    
    if (empty($codigo_ingresado)) {
        $_SESSION['error'] = 'Debes ingresar el código';
        header('Location: verificar_codigo.php');
        exit();
    }
    
    // Buscar código en la base de datos
    $sql = "SELECT * FROM codigos_recuperacion 
            WHERE usuario_id = ? 
            AND codigo = ? 
            AND usado = 0 
            AND expiracion > NOW()
            ORDER BY fecha_creacion DESC 
            LIMIT 1";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id, $codigo_ingresado]);
    $codigo = $stmt->fetch(PDO::FETCH_OBJ);
    
    if (!$codigo) {
        $_SESSION['error'] = 'Código incorrecto o expirado';
        header('Location: verificar_codigo.php');
        exit();
    }
    
    // Marcar código como usado
    $sql_update = "UPDATE codigos_recuperacion SET usado = 1 WHERE id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$codigo->id]);
    
    // Código correcto, permitir cambiar contraseña
    $_SESSION['password_reset_verified'] = true;
    $_SESSION['success'] = '¡Código verificado correctamente! Ahora puedes cambiar tu contraseña.';
    header('Location: nueva_password.php');
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error de conexión: ' . $e->getMessage();
    header('Location: verificar_codigo.php');
    exit();
}
?>
