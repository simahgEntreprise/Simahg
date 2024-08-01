# 📧 Guía Completa: Configurar Gmail en XAMPP para Enviar Emails

## 🎯 PASO 1: Configurar tu Cuenta de Gmail

### 1.1 Activar Verificación en 2 Pasos

1. Ve a: https://myaccount.google.com/security
2. Busca "Verificación en dos pasos"
3. Actívala si no la tienes activada
4. Sigue las instrucciones (te pedirá tu teléfono)

### 1.2 Generar Contraseña de Aplicación

1. Ve a: https://myaccount.google.com/apppasswords
2. Si te pide iniciar sesión, hazlo
3. En "Seleccionar app" → Elige "Correo"
4. En "Seleccionar dispositivo" → Elige "Otro (nombre personalizado)"
5. Escribe: "XAMPP SIMAHG"
6. Clic en "GENERAR"
7. **IMPORTANTE:** Copia la contraseña de 16 caracteres (sin espacios)
   - Ejemplo: `abcd efgh ijkl mnop` → Copia: `abcdefghijklmnop`
8. ⚠️ **GUARDA ESTA CONTRASEÑA** - No la podrás ver de nuevo

---

## 🎯 PASO 2: Configurar XAMPP (Opción Fácil con PHPMailer)

### 2.1 Instalar PHPMailer (RECOMENDADO)

Abre Terminal y ejecuta:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/simahg
curl -sS https://getcomposer.org/installer | php
php composer.phar require phpmailer/phpmailer
```

O si ya tienes Composer:

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/simahg
composer require phpmailer/phpmailer
```

### 2.2 Crear Archivo de Configuración de Email

Crea el archivo: `/Applications/XAMPP/xamppfiles/htdocs/simahg/config_email.php`

```php
<?php
/**
 * Configuración de Email para SIMAHG
 * Usa PHPMailer con Gmail
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarEmail($destinatario, $nombre_destinatario, $asunto, $mensaje_html) {
    require 'vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    
    try {
        // ============================================
        // ⚠️ CONFIGURACIÓN - CAMBIA ESTOS VALORES
        // ============================================
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'TU_EMAIL@gmail.com';  // 👈 CAMBIA ESTO
        $mail->Password = 'tu_contraseña_app';    // 👈 CAMBIA ESTO (sin espacios)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        
        // Remitente
        $mail->setFrom('TU_EMAIL@gmail.com', 'SIMAHG - Sistema de Gestión');
        
        // Destinatario
        $mail->addAddress($destinatario, $nombre_destinatario);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $mensaje_html;
        $mail->AltBody = strip_tags($mensaje_html);
        
        // Enviar
        $mail->send();
        return ['success' => true, 'message' => 'Email enviado correctamente'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => "Error: {$mail->ErrorInfo}"];
    }
}
?>
```

---

## 🎯 PASO 3: Actualizar el Sistema de Recuperación

Ya tengo el código listo, solo ejecuta estos comandos y los archivos se actualizarán automáticamente.

---

## 🎯 OPCIÓN ALTERNATIVA: Configurar sendmail (Más complejo)

Si prefieres usar la función mail() de PHP nativa:

### 3.1 Editar php.ini

```bash
nano /Applications/XAMPP/xamppfiles/etc/php.ini
```

Busca y modifica estas líneas:

```ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = TU_EMAIL@gmail.com
sendmail_path = "/Applications/XAMPP/xamppfiles/bin/sendmail -t"
```

### 3.2 Crear archivo sendmail (Mac)

```bash
sudo nano /Applications/XAMPP/xamppfiles/bin/sendmail
```

Pega este contenido:

```bash
#!/bin/bash
/usr/sbin/sendmail -t -i "$@"
```

Dar permisos:

```bash
sudo chmod +x /Applications/XAMPP/xamppfiles/bin/sendmail
```

⚠️ **NOTA:** Esta opción es más compleja y puede no funcionar en macOS moderno.

---

## 🧪 PASO 4: Probar la Configuración

### Opción A: Con PHPMailer (Recomendado)

Ejecuta el script de prueba que voy a crear.

### Opción B: Manualmente

1. Ve a: http://localhost/simahg/recuperar_password.php
2. Selecciona "Correo Electrónico"
3. Ingresa usuario: `admin`
4. Revisa tu bandeja de entrada del email configurado en usuarios

---

## ❓ Solución de Problemas

### Error: "SMTP connect() failed"
- Verifica que la contraseña de aplicación sea correcta (sin espacios)
- Verifica que tu email y contraseña estén en config_email.php

### Error: "Could not authenticate"
- Verifica que tengas activada la verificación en 2 pasos
- Regenera la contraseña de aplicación

### No llega el email
- Revisa spam/correo no deseado
- Verifica que el email del usuario en BD sea correcto
- Revisa logs de PHP: `/Applications/XAMPP/xamppfiles/logs/php_error_log`

### Error: "Vendor autoload not found"
- Ejecuta: `composer require phpmailer/phpmailer`
- O descarga PHPMailer manualmente

---

## ✅ Checklist de Configuración

- [ ] Activar verificación en 2 pasos en Gmail
- [ ] Generar contraseña de aplicación
- [ ] Guardar contraseña de aplicación
- [ ] Instalar PHPMailer con Composer
- [ ] Crear config_email.php con tus datos
- [ ] Actualizar recuperar_password_process.php
- [ ] Probar envío de email
- [ ] Verificar recepción en bandeja de entrada

---

## 📊 Resumen

**Lo más fácil es usar PHPMailer** (Opción recomendada):

1. ✅ Generar contraseña de app en Gmail (2 minutos)
2. ✅ Instalar PHPMailer (1 minuto)
3. ✅ Configurar config_email.php (1 minuto)
4. ✅ Actualizar sistema (automático)
5. ✅ Probar (1 minuto)

**Total: ~5 minutos** ⚡

---

¿Listo para empezar? 🚀
