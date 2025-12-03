<?php
/**
 * Configuración de Email para SIMAHG
 * Usando PHPMailer para envío de correos
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar PHPMailer
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Función para enviar emails usando PHPMailer
 * 
 * @param string $destinatario Email del destinatario
 * @param string $nombre_destinatario Nombre del destinatario
 * @param string $asunto Asunto del correo
 * @param string $mensaje_html Contenido HTML del mensaje
 * @return array ['success' => bool, 'message' => string]
 */
function enviarEmail($destinatario, $nombre_destinatario, $asunto, $mensaje_html) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuración del servidor SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'lothararbaiza0506@gmail.com';
        $mail->Password   = 'ffrtrtxmmzkgbqnb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';
        
        // Configuración adicional para Gmail
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        // Remitente
        $mail->setFrom('lothararbaiza0506@gmail.com', 'SIMAHG - Sistema de Gestión');
        
        // Destinatario
        $mail->addAddress($destinatario, $nombre_destinatario);
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $mensaje_html;
        $mail->AltBody = strip_tags($mensaje_html); // Versión texto plano
        
        // Enviar
        $mail->send();
        
        return [
            'success' => true,
            'message' => 'Correo enviado exitosamente'
        ];
        
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => "Error al enviar correo: {$mail->ErrorInfo}"
        ];
    }
}

/**
 * Función para enmascarar email (mostrar solo parte)
 * Ejemplo: usuario@gmail.com -> u****o@gmail.com
 */
function maskEmail($email) {
    $parts = explode('@', $email);
    if (count($parts) !== 2) {
        return $email;
    }
    
    $nombre = $parts[0];
    $dominio = $parts[1];
    
    $len = strlen($nombre);
    if ($len <= 2) {
        return $email;
    }
    
    $masked = substr($nombre, 0, 1) . str_repeat('*', min($len - 2, 4)) . substr($nombre, -1);
    return $masked . '@' . $dominio;
}

/**
 * Función para enmascarar teléfono
 * Ejemplo: 987654321 -> 98****321
 */
function maskPhone($phone) {
    $len = strlen($phone);
    if ($len < 6) {
        return $phone;
    }
    
    return substr($phone, 0, 2) . str_repeat('*', $len - 4) . substr($phone, -2);
}
?>
