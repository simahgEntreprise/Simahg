<?php
session_start();

// Verificar que haya una sesión de recuperación activa
if (!isset($_SESSION['recovery_user_id'])) {
    $_SESSION['error'] = 'Sesión de recuperación no válida';
    header('Location: recuperar_password.php');
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
    
    $usuario_id = $_SESSION['recovery_user_id'];
    $metodo = $_SESSION['recovery_metodo'] ?? 'email';
    
    // Generar nuevo código
    $codigo = sprintf('%06d', mt_rand(0, 999999));
    $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    // Iniciar una transacción para asegurar atomicidad
    $pdo->beginTransaction();
    
    try {
        // ESTRATEGIA: Primero intentar ACTUALIZAR un código existente no usado
        // Si no existe, entonces INSERTAR uno nuevo
        // Esto evita completamente el error de UNIQUE KEY
        
        $sql_update = "UPDATE codigos_recuperacion 
                       SET codigo = ?, metodo = ?, expiracion = ?, fecha_creacion = CURRENT_TIMESTAMP 
                       WHERE usuario_id = ? AND usado = 0";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$codigo, $metodo, $expiracion, $usuario_id]);
        
        // Si no se actualizó ningún registro (no había código previo), entonces insertar
        if ($stmt_update->rowCount() == 0) {
            $sql_insert = "INSERT INTO codigos_recuperacion (usuario_id, codigo, metodo, expiracion, usado) 
                           VALUES (?, ?, ?, ?, 0)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$usuario_id, $codigo, $metodo, $expiracion]);
        }
        
        // Confirmar la transacción
        $pdo->commit();
        
    } catch (Exception $e) {
        // Revertir en caso de error
        $pdo->rollBack();
        $_SESSION['error'] = 'Error al generar nuevo código: ' . $e->getMessage();
        header('Location: verificar_codigo.php');
        exit();
    }
    
    // Obtener datos del usuario para enviar el código
    $stmt = $pdo->prepare("SELECT email, nombre, apellidos FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($metodo === 'email' && !empty($user->email)) {
        // Intentar enviar por email
        try {
            require_once __DIR__ . '/config_email.php';
            
            $destinatario = $user->email;
            $nombre_destinatario = $user->nombre . ' ' . $user->apellidos;
            $asunto = 'Nuevo Código de Recuperación - SIMAHG';
            $mensaje = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
                    .container { background: white; padding: 30px; border-radius: 10px; max-width: 600px; margin: 0 auto; }
                    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px 10px 0 0; text-align: center; }
                    .code { font-size: 32px; font-weight: bold; color: #667eea; text-align: center; padding: 20px; background: #f8f9fa; border-radius: 10px; margin: 20px 0; letter-spacing: 5px; }
                    .footer { text-align: center; color: #999; margin-top: 30px; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>🔐 Código de Recuperación - SIMAHG</h2>
                    </div>
                    <p>Hola <strong>$nombre_destinatario</strong>,</p>
                    <p>Has solicitado un nuevo código para recuperar tu contraseña.</p>
                    <div class='code'>$codigo</div>
                    <p><strong>⏱️ Este código expira en 15 minutos.</strong></p>
                    <p>Si no solicitaste este código, ignora este mensaje.</p>
                    <div class='footer'>
                        <p>© " . date('Y') . " SIMAHG - Sistema de Gestión</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            if (enviarEmail($destinatario, $nombre_destinatario, $asunto, $mensaje)) {
                $_SESSION['success'] = '✓ Nuevo código enviado a tu correo electrónico';
            } else {
                $_SESSION['warning'] = 'Código generado pero no se pudo enviar el email. Código: ' . $codigo;
            }
        } catch (Exception $e) {
            $_SESSION['warning'] = 'Código generado: ' . $codigo . ' (No se pudo enviar email)';
        }
    } else {
        // Si no hay email o es SMS
        $_SESSION['success'] = 'Nuevo código generado: ' . $codigo;
    }
    
    // Redirigir de vuelta a verificar código
    header('Location: verificar_codigo.php');
    exit();
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error al generar nuevo código: ' . $e->getMessage();
    header('Location: verificar_codigo.php');
    exit();
}
?>
