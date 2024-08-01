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
    
    // Verificar que el código haya sido verificado
    if (!isset($_SESSION['password_reset_verified']) || !isset($_SESSION['recovery_user_id'])) {
        $_SESSION['error'] = 'Sesión de recuperación no válida';
        header('Location: recuperar_password.php');
        exit();
    }
    
    $usuario_id = $_SESSION['recovery_user_id'];
    $nueva_password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validaciones
    if (empty($nueva_password) || empty($password_confirm)) {
        $_SESSION['error'] = 'Debes completar ambos campos';
        header('Location: nueva_password.php');
        exit();
    }
    
    if ($nueva_password !== $password_confirm) {
        $_SESSION['error'] = 'Las contraseñas no coinciden';
        header('Location: nueva_password.php');
        exit();
    }
    
    if (strlen($nueva_password) < 6) {
        $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
        header('Location: nueva_password.php');
        exit();
    }
    
    // Encriptar contraseña (mismo método que el login: SHA1)
    $password_encrypted = sha1($nueva_password);
    
    // Actualizar contraseña en la base de datos
    $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$password_encrypted, $usuario_id]);
    
    // Limpiar sesión de recuperación
    unset($_SESSION['password_reset_verified']);
    unset($_SESSION['recovery_user_id']);
    unset($_SESSION['recovery_metodo']);
    
    $_SESSION['success'] = '¡Contraseña actualizada correctamente! Ya puedes iniciar sesión con tu nueva contraseña.';
    header('Location: login.php');
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error al actualizar la contraseña: ' . $e->getMessage();
    header('Location: nueva_password.php');
    exit();
}
?>
