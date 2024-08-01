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
    
    // Obtener datos del formulario
    $usuario = $_POST['usuario'] ?? '';
    $metodo = $_POST['metodo'] ?? '';
    
    if (empty($usuario) || empty($metodo)) {
        $_SESSION['error'] = 'Debes completar todos los campos';
        header('Location: recuperar_password.php');
        exit();
    }
    
    // Buscar usuario en la base de datos
    $sql = "SELECT id, usuario, email, telefono, nombre, apellidos FROM usuarios WHERE usuario = ? AND estado = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    if (!$user) {
        $_SESSION['error'] = 'Usuario no encontrado o inactivo';
        header('Location: recuperar_password.php');
        exit();
    }
    
    // Verificar si ya existe un código válido no usado y no expirado
    $sql_check = "SELECT id, codigo, expiracion FROM codigos_recuperacion 
                  WHERE usuario_id = ? AND usado = 0 AND expiracion > NOW() 
                  ORDER BY fecha_creacion DESC LIMIT 1";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$user->id]);
    $codigo_existente = $stmt_check->fetch(PDO::FETCH_OBJ);
    
    if ($codigo_existente) {
        // Ya tiene un código válido, redirigir a verificar código
        $_SESSION['recovery_user_id'] = $user->id;
        $_SESSION['recovery_metodo'] = $metodo;
        $_SESSION['warning'] = 'Ya tienes un código activo. Revisa tu correo o solicita uno nuevo desde la página de verificación.';
        header('Location: verificar_codigo.php');
        exit();
    }
    
    // Iniciar una transacción para asegurar atomicidad
    // Generar código de verificación de 6 dígitos
    $codigo = sprintf('%06d', mt_rand(0, 999999));
    $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes')); // Expira en 15 minutos
    
    $pdo->beginTransaction();
    
    try {
        // ESTRATEGIA: Primero intentar ACTUALIZAR un código existente no usado
        // Si no existe, entonces INSERTAR uno nuevo
        // Esto evita completamente el error de UNIQUE KEY
        
        $sql_update = "UPDATE codigos_recuperacion 
                       SET codigo = ?, metodo = ?, expiracion = ?, fecha_creacion = CURRENT_TIMESTAMP 
                       WHERE usuario_id = ? AND usado = 0";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$codigo, $metodo, $expiracion, $user->id]);
        
        // Si no se actualizó ningún registro (no había código previo), entonces insertar
        if ($stmt_update->rowCount() == 0) {
            $sql_insert = "INSERT INTO codigos_recuperacion (usuario_id, codigo, metodo, expiracion, usado) 
                           VALUES (?, ?, ?, ?, 0)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$user->id, $codigo, $metodo, $expiracion]);
        }
        
        // Confirmar la transacción
        $pdo->commit();
        
    } catch (Exception $e) {
        // Revertir en caso de error
        $pdo->rollBack();
        $_SESSION['error'] = 'Error al generar el código: ' . $e->getMessage();
        header('Location: recuperar_password.php');
        exit();
    }
    
    // Enviar código según el método seleccionado
    if ($metodo === 'email') {
        // ENVÍO POR EMAIL usando PHPMailer
        if (empty($user->email)) {
            $_SESSION['error'] = 'No tienes un correo electrónico registrado';
            header('Location: recuperar_password.php');
            exit();
        }
        
        // Incluir la configuración de email con PHPMailer
        require_once __DIR__ . '/config_email.php';
        
        $destinatario = $user->email;
        $nombre_destinatario = $user->nombre . ' ' . $user->apellidos;
        $asunto = 'Código de Recuperación - SIMAHG';
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
                    <h2>🔐 Recuperación de Contraseña</h2>
                </div>
                <h3>Hola, {$user->nombre} {$user->apellidos}</h3>
                <p>Hemos recibido una solicitud para recuperar tu contraseña en <strong>SIMAHG</strong>.</p>
                <p>Tu código de verificación es:</p>
                <div class='code'>{$codigo}</div>
                <p><strong>⏱️ Este código expira en 15 minutos.</strong></p>
                <p>Si no solicitaste este código, ignora este mensaje.</p>
                <div class='footer'>
                    <p>Sistema de Gestión SIMAHG<br>Este es un mensaje automático, no responder.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Enviar email usando PHPMailer
        $resultado = enviarEmail($destinatario, $nombre_destinatario, $asunto, $mensaje);
        
        if ($resultado['success']) {
            $_SESSION['success'] = 'Código enviado a tu correo: ' . maskEmail($user->email);
            $_SESSION['recovery_user_id'] = $user->id;
            $_SESSION['recovery_metodo'] = 'email';
            header('Location: verificar_codigo.php');
            exit();
        } else {
            $_SESSION['error'] = 'Error al enviar el correo: ' . $resultado['message'];
            header('Location: recuperar_password.php');
            exit();
        }
        
    } elseif ($metodo === 'sms') {
        // ENVÍO POR SMS
        if (empty($user->telefono)) {
            $_SESSION['error'] = 'No tienes un número de teléfono registrado';
            header('Location: recuperar_password.php');
            exit();
        }
        
        // Aquí integrarías un servicio de SMS como Twilio, Nexmo, etc.
        // Por ahora, simulamos el envío
        
        // EJEMPLO DE INTEGRACIÓN CON TWILIO (necesitas instalar el SDK)
        /*
        require_once 'vendor/autoload.php';
        use Twilio\Rest\Client;
        
        $sid = 'tu_account_sid';
        $token = 'tu_auth_token';
        $twilio = new Client($sid, $token);
        
        $message = $twilio->messages->create(
            $user->telefono,
            [
                'from' => '+1234567890',
                'body' => "Tu código de recuperación SIMAHG es: {$codigo}. Expira en 15 minutos."
            ]
        );
        */
        
        // POR AHORA: Simulación (en producción debes usar un servicio real)
        $_SESSION['success'] = 'Código enviado por SMS a: ' . maskPhone($user->telefono) . ' (Código de prueba: ' . $codigo . ')';
        $_SESSION['recovery_user_id'] = $user->id;
        $_SESSION['recovery_metodo'] = 'sms';
        header('Location: verificar_codigo.php');
        exit();
    }
    
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error de conexión: ' . $e->getMessage();
    header('Location: recuperar_password.php');
    exit();
}

// Función para ocultar parcialmente el email
function maskEmail($email) {
    $parts = explode('@', $email);
    $name = $parts[0];
    $domain = $parts[1];
    
    $name_length = strlen($name);
    if ($name_length > 2) {
        $masked_name = substr($name, 0, 2) . str_repeat('*', $name_length - 2);
    } else {
        $masked_name = $name;
    }
    
    return $masked_name . '@' . $domain;
}

// Función para ocultar parcialmente el teléfono
function maskPhone($phone) {
    $length = strlen($phone);
    if ($length > 4) {
        return str_repeat('*', $length - 4) . substr($phone, -4);
    }
    return $phone;
}
?>
