<?php
/**
 * Configuración de Email para SIMAHG
 * Usa PHPMailer con Gmail
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarEmail($destinatario, $nombre_destinatario, $asunto, $mensaje_html) {
    
    // Verificar si PHPMailer está instalado
    if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
        return [
            'success' => false, 
            'message' => 'PHPMailer no está instalado. Ejecuta: composer require phpmailer/phpmailer'
        ];
    }
    
    require __DIR__ . '/vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    
    try {
        // ============================================
        // ⚠️ CONFIGURACIÓN - CAMBIA ESTOS VALORES
        // ============================================
        $config = [
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_user' => 'lothararbaiza0506@gmail.com',          // 👈 CAMBIA ESTO
            'smtp_pass' => 'mzvxwfomtipphvxh',     // 👈 CAMBIA ESTO (16 caracteres sin espacios)
            'from_email' => 'lothararbaiza0506@gmail.com',          // 👈 CAMBIA ESTO
            'from_name' => 'SIMAHG - Sistema de Gestión'
        ];
        
        // Verificar si está configurado
        if ($config['smtp_user'] === 'TU_EMAIL@gmail.com' || $config['smtp_pass'] === 'tu_contraseña_aplicacion') {
            return [
                'success' => false,
                'message' => '⚠️ ERROR: Debes configurar tu email y contraseña en config_email.php'
            ];
        }
        
        // Configuración SMTP
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_user'];
        $mail->Password = $config['smtp_pass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['smtp_port'];
        $mail->CharSet = 'UTF-8';
        
        // Debug (desactivado en producción - activa solo para diagnóstico)
        // $mail->SMTPDebug = 2;
        // $mail->Debugoutput = 'html';
        
        // Remitente
        $mail->setFrom($config['from_email'], $config['from_name']);
        
        // Destinatario
        $mail->addAddress($destinatario, $nombre_destinatario);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje_html;
        $mail->AltBody = strip_tags($mensaje_html);
        
        // Enviar
        $mail->send();
        
        return [
            'success' => true, 
            'message' => 'Email enviado correctamente a ' . $destinatario
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false, 
            'message' => "Error al enviar email: {$mail->ErrorInfo}"
        ];
    }
}

/**
 * Obtener configuración de email
 */
function getEmailConfig() {
    return [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_user' => 'TU_EMAIL@gmail.com',
        'from_email' => 'TU_EMAIL@gmail.com',
        'from_name' => 'SIMAHG - Sistema de Gestión',
        'configurado' => false  // Cambiar a true después de configurar
    ];
}

/**
 * Verificar si el email está configurado
 */
function emailConfigurado() {
    $config = getEmailConfig();
    return $config['smtp_user'] !== 'TU_EMAIL@gmail.com';
}
?>
