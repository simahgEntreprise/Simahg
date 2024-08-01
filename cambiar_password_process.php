<?php
session_start();

// Limpiar variables de sesión de recuperación de código para evitar conflictos
unset($_SESSION['recovery_user_id']);
unset($_SESSION['recovery_metodo']);
unset($_SESSION['recovery_codigo']);

// Verificar que el usuario esté logueado
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    $_SESSION['error'] = 'Debes iniciar sesión para cambiar tu contraseña';
    header('Location: login.php');
    exit();
}

// Configuración de la base de datos
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Obtener datos del formulario
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validaciones
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = 'Todos los campos son obligatorios';
        header('Location: cambiar_password.php');
        exit();
    }
    
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = 'Las contraseñas nuevas no coinciden';
        header('Location: cambiar_password.php');
        exit();
    }
    
    if (strlen($new_password) < 6) {
        $_SESSION['error'] = 'La nueva contraseña debe tener al menos 6 caracteres';
        header('Location: cambiar_password.php');
        exit();
    }
    
    if ($current_password === $new_password) {
        $_SESSION['error'] = 'La nueva contraseña debe ser diferente a la actual';
        header('Location: cambiar_password.php');
        exit();
    }
    
    // Obtener usuario actual de la base de datos
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT id, usuario, password, nombre, apellidos FROM usuarios WHERE id = ? AND estado = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    if (!$user) {
        $_SESSION['error'] = 'Usuario no encontrado';
        header('Location: cambiar_password.php');
        exit();
    }
    
    // Verificar contraseña actual
    $current_password_hash = sha1($current_password);
    
    if ($current_password_hash !== $user->password) {
        $_SESSION['error'] = 'La contraseña actual es incorrecta';
        header('Location: cambiar_password.php');
        exit();
    }
    
    // Encriptar nueva contraseña (mismo método que el login: SHA1)
    $new_password_hash = sha1($new_password);
    
    // Eliminar cualquier código de recuperación activo para este usuario
    $sql_delete_codigos = "DELETE FROM codigos_recuperacion WHERE usuario_id = ? AND usado = 0";
    $stmt_delete_codigos = $pdo->prepare($sql_delete_codigos);
    $stmt_delete_codigos->execute([$user_id]);
    
    // Actualizar contraseña en la base de datos
    $sql_update = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$new_password_hash, $user_id]);
    
    // Registrar el cambio en un log (opcional)
    $log_sql = "INSERT INTO logs_sistema (usuario_id, accion, descripcion, fecha) 
                VALUES (?, 'CAMBIO_PASSWORD', 'Usuario cambió su contraseña', NOW())";
    
    try {
        $log_stmt = $pdo->prepare($log_sql);
        $log_stmt->execute([$user_id]);
    } catch (PDOException $e) {
        // Si no existe la tabla de logs, no pasa nada
    }
    
    $_SESSION['success'] = '¡Contraseña cambiada correctamente! Por seguridad, debes volver a iniciar sesión.';
    
    // Cerrar sesión por seguridad (el usuario debe iniciar sesión con la nueva contraseña)
    session_unset();
    session_destroy();
    
    // Iniciar nueva sesión para mostrar el mensaje
    session_start();
    $_SESSION['success'] = '¡Contraseña cambiada correctamente! Inicia sesión con tu nueva contraseña.';
    
    header('Location: login.php');
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error al cambiar la contraseña: ' . $e->getMessage();
    header('Location: cambiar_password.php');
    exit();
} catch (Exception $e) {
    $_SESSION['error'] = 'Error interno: ' . $e->getMessage();
    header('Location: cambiar_password.php');
    exit();
}
?>
