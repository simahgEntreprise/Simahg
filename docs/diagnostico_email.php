<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Diagnóstico de Email - SIMAHG</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 20px auto; padding: 20px; background: #f5f5f5; }
        .card { background: white; padding: 30px; margin: 20px 0; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #667eea; border-bottom: 3px solid #667eea; padding-bottom: 10px; }
        .ok { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .debug-output { background: #2d2d2d; color: #50fa7b; padding: 15px; border-radius: 5px; overflow-x: auto; font-family: monospace; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; border: none; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #5568d3; }
        input[type="email"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; margin: 10px 0; box-sizing: border-box; }
        pre { white-space: pre-wrap; word-wrap: break-word; }
        .info-box { background: #e7f3ff; border-left: 4px solid #2196F3; padding: 15px; margin: 15px 0; }
        .error-box { background: #ffe7e7; border-left: 4px solid #dc3545; padding: 15px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🔍 Diagnóstico Completo de Email</h2>
        
        <?php
        require_once 'config_email.php';
        
        // Verificar PHPMailer
        echo "<h3>1️⃣ Verificar PHPMailer</h3>";
        if (file_exists(__DIR__ . '/vendor/autoload.php')) {
            echo "<p class='ok'>✅ PHPMailer está instalado</p>";
        } else {
            echo "<p class='error'>❌ PHPMailer NO está instalado</p>";
            echo "<p>Ejecuta: <code>composer require phpmailer/phpmailer</code></p>";
            exit;
        }
        
        // Verificar configuración
        echo "<h3>2️⃣ Verificar Configuración</h3>";
        
        require __DIR__ . '/vendor/autoload.php';
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        
        $config = [
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_user' => 'lothararbaiza0506@gmail.com',
            'smtp_pass' => 'lhvshkqvcxjcxrxe',
            'from_email' => 'lothararbaiza0506@gmail.com',
            'from_name' => 'SIMAHG - Sistema de Gestión'
        ];
        
        echo "<p><strong>SMTP Host:</strong> {$config['smtp_host']}</p>";
        echo "<p><strong>SMTP Port:</strong> {$config['smtp_port']}</p>";
        echo "<p><strong>Email:</strong> {$config['smtp_user']}</p>";
        echo "<p><strong>Password:</strong> " . substr($config['smtp_pass'], 0, 4) . "************</p>";
        
        // Verificar extensiones PHP
        echo "<h3>3️⃣ Verificar Extensiones PHP</h3>";
        
        $extensiones_requeridas = ['openssl', 'sockets'];
        foreach ($extensiones_requeridas as $ext) {
            if (extension_loaded($ext)) {
                echo "<p class='ok'>✅ Extensión '$ext' cargada</p>";
            } else {
                echo "<p class='error'>❌ Extensión '$ext' NO cargada</p>";
            }
        }
        
        // Test de conexión SMTP
        echo "<h3>4️⃣ Test de Conexión SMTP</h3>";
        
        $mail = new PHPMailer(true);
        
        try {
            // Configuración básica
            $mail->isSMTP();
            $mail->Host = $config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp_user'];
            $mail->Password = $config['smtp_pass'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['smtp_port'];
            $mail->Timeout = 10;
            
            // Test de conexión
            if ($mail->smtpConnect()) {
                echo "<p class='ok'>✅ Conexión SMTP exitosa con Gmail</p>";
                $mail->smtpClose();
            } else {
                echo "<p class='error'>❌ No se pudo conectar al servidor SMTP</p>";
            }
            
        } catch (Exception $e) {
            echo "<p class='error'>❌ Error de conexión: {$e->getMessage()}</p>";
        }
        
        ?>
        
        <h3>5️⃣ Enviar Email de Prueba con Debug</h3>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_test'])) {
            $email_destino = $_POST['email_destino'] ?? '';
            
            if (filter_var($email_destino, FILTER_VALIDATE_EMAIL)) {
                
                echo "<div class='info-box'>";
                echo "<strong>📧 Enviando email a:</strong> $email_destino<br>";
                echo "<strong>⏱️ Por favor espera...</strong>";
                echo "</div>";
                
                echo "<h4>Debug Output:</h4>";
                echo "<div class='debug-output'>";
                
                // Capturar output
                ob_start();
                
                $mail = new PHPMailer(true);
                
                try {
                    // Configuración
                    $mail->isSMTP();
                    $mail->Host = $config['smtp_host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $config['smtp_user'];
                    $mail->Password = $config['smtp_pass'];
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = $config['smtp_port'];
                    $mail->CharSet = 'UTF-8';
                    
                    // Debug activado
                    $mail->SMTPDebug = 3;
                    $mail->Debugoutput = function($str, $level) {
                        echo htmlspecialchars($str) . "<br>";
                    };
                    
                    // Remitente
                    $mail->setFrom($config['from_email'], $config['from_name']);
                    
                    // Destinatario
                    $mail->addAddress($email_destino, 'Usuario de Prueba');
                    
                    // Contenido
                    $mail->isHTML(true);
                    $mail->Subject = 'Test de Email - SIMAHG';
                    $mail->Body = '
                    <html>
                    <body style="font-family: Arial, sans-serif; padding: 20px;">
                        <h2 style="color: #667eea;">✅ Test de Email Exitoso</h2>
                        <p>Este es un email de prueba del sistema SIMAHG.</p>
                        <p><strong>Hora de envío:</strong> ' . date('Y-m-d H:i:s') . '</p>
                        <p>Si recibes este mensaje, la configuración de email está funcionando correctamente.</p>
                    </body>
                    </html>
                    ';
                    
                    // Enviar
                    $mail->send();
                    
                    $debug_output = ob_get_clean();
                    echo $debug_output;
                    
                    echo "</div>";
                    
                    echo "<div class='info-box' style='background: #d4edda; border-left-color: #28a745; margin-top: 20px;'>";
                    echo "<h3 style='margin-top: 0; color: #28a745;'>✅ ¡Email Enviado Correctamente!</h3>";
                    echo "<p>Revisa la bandeja de entrada de: <strong>$email_destino</strong></p>";
                    echo "<p>Si no lo ves, revisa la carpeta de <strong>SPAM</strong></p>";
                    echo "</div>";
                    
                } catch (Exception $e) {
                    $debug_output = ob_get_clean();
                    echo $debug_output;
                    echo "</div>";
                    
                    echo "<div class='error-box' style='margin-top: 20px;'>";
                    echo "<h3 style='margin-top: 0; color: #dc3545;'>❌ Error al Enviar Email</h3>";
                    echo "<p><strong>Mensaje de error:</strong></p>";
                    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
                    
                    echo "<h4>Posibles Soluciones:</h4>";
                    echo "<ol>";
                    echo "<li><strong>Contraseña incorrecta:</strong> Verifica que copiaste la contraseña de aplicación sin espacios</li>";
                    echo "<li><strong>Verificación en 2 pasos:</strong> Asegúrate de tenerla activada en Gmail</li>";
                    echo "<li><strong>Regenera la contraseña:</strong> <a href='https://myaccount.google.com/apppasswords' target='_blank'>Generar nueva contraseña</a></li>";
                    echo "<li><strong>Permisos de Gmail:</strong> Verifica que Gmail permita el acceso</li>";
                    echo "</ol>";
                    echo "</div>";
                }
                
            } else {
                echo "<div class='error-box'>";
                echo "⚠️ Email inválido";
                echo "</div>";
            }
        }
        ?>
        
        <form method="post">
            <label><strong>Email de destino para la prueba:</strong></label>
            <input type="email" name="email_destino" placeholder="tu_email@gmail.com" 
                   value="lothararbaiza0506@gmail.com" required>
            
            <button type="submit" name="send_test" class="btn">
                📧 Enviar Email de Prueba con Debug
            </button>
        </form>
        
        <div class="info-box" style="margin-top: 30px;">
            <h3>📝 Checklist de Problemas Comunes:</h3>
            <ol>
                <li>✅ ¿Tienes verificación en 2 pasos activada? → <a href="https://myaccount.google.com/security" target="_blank">Verificar</a></li>
                <li>✅ ¿Generaste la contraseña de aplicación? → <a href="https://myaccount.google.com/apppasswords" target="_blank">Generar</a></li>
                <li>✅ ¿Copiaste la contraseña SIN espacios? → Debe ser: <code>abcdefghijklmnop</code> (16 caracteres)</li>
                <li>✅ ¿El email es correcto? → <code>lothararbaiza0506@gmail.com</code></li>
                <li>✅ ¿Guardaste el archivo config_email.php después de editarlo?</li>
            </ol>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="test_email.php" class="btn">← Volver a Test Simple</a>
            <a href="sistema_configurado.php" class="btn">📊 Ver Estado del Sistema</a>
            <a href="login.php" class="btn">🔐 Ir al Login</a>
        </div>
    </div>
</body>
</html>
