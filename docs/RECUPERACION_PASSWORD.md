# 🔐 Sistema de Recuperación de Contraseña - SIMAHG

## ✅ Componentes Implementados

### Archivos Creados:
1. **`recuperar_password.php`** - Formulario inicial para seleccionar método de recuperación
2. **`recuperar_password_process.php`** - Procesa el envío del código
3. **`verificar_codigo.php`** - Formulario para ingresar el código recibido
4. **`verificar_codigo_process.php`** - Valida el código ingresado
5. **`nueva_password.php`** - Formulario para establecer nueva contraseña
6. **`nueva_password_process.php`** - Actualiza la contraseña en la BD
7. **`database/crear_tabla_recuperacion.sql`** - Script SQL para crear la tabla

### Tabla en la Base de Datos:
- **`codigos_recuperacion`** - Almacena los códigos de verificación
- **Campo `telefono`** agregado a la tabla `usuarios`

---

## 🚀 Cómo Funciona

### Flujo de Recuperación:

1. **Usuario solicita recuperación** → `recuperar_password.php`
   - Selecciona método: EMAIL o SMS
   - Ingresa su nombre de usuario

2. **Sistema genera código** → `recuperar_password_process.php`
   - Genera código de 6 dígitos aleatorios
   - Guarda en BD con expiración de 15 minutos
   - Envía el código por email o SMS

3. **Usuario ingresa código** → `verificar_codigo.php`
   - Timer de 15 minutos
   - Valida el código ingresado

4. **Código verificado** → `verificar_codigo_process.php`
   - Marca código como usado
   - Permite cambiar contraseña

5. **Nueva contraseña** → `nueva_password.php`
   - Indicador de fortaleza
   - Validación de coincidencia
   - Requisitos de seguridad

6. **Contraseña actualizada** → `nueva_password_process.php`
   - Encripta con SHA1
   - Actualiza en la BD
   - Redirige al login

---

## 📧 Configuración de EMAIL

### Opción 1: Usar `mail()` de PHP (Configuración actual - BÁSICA)

El sistema ya está configurado para usar la función `mail()` de PHP. 

**Configurar XAMPP para enviar emails:**

1. **Editar `php.ini`:**
```ini
[mail function]
SMTP = smtp.gmail.com
smtp_port = 587
sendmail_from = tucorreo@gmail.com
sendmail_path = "\"C:\xampp\sendmail\sendmail.exe\" -t"
```

2. **Editar `sendmail.ini`:**
```ini
[sendmail]
smtp_server=smtp.gmail.com
smtp_port=587
auth_username=tucorreo@gmail.com
auth_password=tu_contraseña_de_aplicación
force_sender=tucorreo@gmail.com
```

3. **Habilitar "Contraseñas de aplicación" en Gmail:**
   - Ve a: https://myaccount.google.com/apppasswords
   - Genera una contraseña de aplicación
   - Usa esa contraseña en `sendmail.ini`

### Opción 2: Usar PHPMailer (RECOMENDADO para producción)

**Instalar PHPMailer:**
```bash
composer require phpmailer/phpmailer
```

**Modificar `recuperar_password_process.php` (líneas 54-78):**
```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuración SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tucorreo@gmail.com';
    $mail->Password = 'tu_contraseña_de_aplicación';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    
    // Remitente y destinatario
    $mail->setFrom('noreply@simahg.com', 'SIMAHG');
    $mail->addAddress($user->email, "{$user->nombre} {$user->apellidos}");
    
    // Contenido
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Código de Recuperación - SIMAHG';
    $mail->Body = $mensaje;
    
    $mail->send();
    $_SESSION['success'] = 'Código enviado a tu correo: ' . maskEmail($user->email);
    
} catch (Exception $e) {
    $_SESSION['error'] = "Error al enviar: {$mail->ErrorInfo}";
}
```

---

## 📱 Configuración de SMS

### Opción 1: Twilio (RECOMENDADO - Servicios globales)

**Instalar SDK de Twilio:**
```bash
composer require twilio/sdk
```

**Registrarse en Twilio:**
1. Crea cuenta en: https://www.twilio.com/
2. Obtén tu `Account SID` y `Auth Token`
3. Compra un número de teléfono Twilio

**Modificar `recuperar_password_process.php` (líneas 92-108):**
```php
require_once 'vendor/autoload.php';
use Twilio\Rest\Client;

$sid = 'tu_account_sid';
$token = 'tu_auth_token';
$twilio_number = '+1234567890'; // Tu número Twilio

$twilio = new Client($sid, $token);

try {
    $message = $twilio->messages->create(
        $user->telefono, // Número del usuario
        [
            'from' => $twilio_number,
            'body' => "Tu código de recuperación SIMAHG es: {$codigo}. Expira en 15 minutos."
        ]
    );
    
    $_SESSION['success'] = 'Código enviado por SMS a: ' . maskPhone($user->telefono);
    
} catch (Exception $e) {
    $_SESSION['error'] = 'Error al enviar SMS: ' . $e->getMessage();
}
```

**Precios Twilio (aproximados):**
- Mensajes SMS: $0.0075 USD por SMS enviado
- Número de teléfono: $1 USD/mes

### Opción 2: Amazon SNS (AWS)

```bash
composer require aws/aws-sdk-php
```

```php
require 'vendor/autoload.php';
use Aws\Sns\SnsClient;

$sns = new SnsClient([
    'version' => 'latest',
    'region' => 'us-east-1',
    'credentials' => [
        'key' => 'tu_access_key',
        'secret' => 'tu_secret_key'
    ]
]);

$result = $sns->publish([
    'Message' => "Tu código SIMAHG: {$codigo}. Expira en 15 minutos.",
    'PhoneNumber' => $user->telefono
]);
```

### Opción 3: Nexmo/Vonage (Servicios globales)

```bash
composer require vonage/client
```

```php
$client = new Vonage\Client(new Vonage\Client\Credentials\Basic('API_KEY', 'API_SECRET'));

$message = $client->message()->send([
    'to' => $user->telefono,
    'from' => 'SIMAHG',
    'text' => "Tu código de recuperación: {$codigo}. Expira en 15 minutos."
]);
```

### Opción 4: Servicios Peruanos (para Perú específicamente)

**API Peruana - SMS Masivos:**
- https://www.smsmasivos.com.pe/
- https://www.apiperu.pe/

---

## 🧪 Modo de Prueba (ACTUAL)

El sistema actualmente está en **modo de prueba**. Cuando seleccionas SMS:
- ❌ NO envía SMS reales
- ✅ Muestra el código en la pantalla para pruebas
- ✅ El código sigue funcionando en la verificación

**Para activar envío real:**
1. Elige un proveedor (Twilio, AWS SNS, etc.)
2. Instala el SDK correspondiente
3. Configura las credenciales
4. Modifica `recuperar_password_process.php` (líneas 92-108)

---

## 🔒 Seguridad Implementada

### ✅ Características de Seguridad:

1. **Códigos de un solo uso**
   - Marcados como "usados" después de verificar
   - No se pueden reutilizar

2. **Expiración temporal**
   - Códigos válidos por 15 minutos
   - Timer visual en la interfaz

3. **Validación de contraseñas**
   - Mínimo 6 caracteres
   - Indicador de fortaleza
   - Verificación de coincidencia

4. **Prevención de spam**
   - Un código activo por usuario
   - Sistema de expiración automática

5. **Encriptación SHA1**
   - Misma que el sistema de login
   - Contraseñas nunca en texto plano

6. **Sesiones seguras**
   - Validación en cada paso
   - Limpieza después de completar

---

## 📝 Actualización de Usuarios con Teléfonos

Para que los usuarios puedan usar recuperación por SMS, deben tener teléfonos registrados:

```sql
-- Actualizar usuarios existentes
UPDATE usuarios SET telefono = '+51987654321' WHERE usuario = 'admin';
UPDATE usuarios SET telefono = '+51912345678' WHERE usuario = 'supervisor';

-- Verificar usuarios con teléfono
SELECT usuario, email, telefono FROM usuarios WHERE estado = 1;
```

---

## 🎯 Pruebas del Sistema

### Probar Recuperación por EMAIL:

1. Ve a: http://localhost/simahg/login.php
2. Clic en "¿Olvidaste tu contraseña?"
3. Selecciona "Correo Electrónico"
4. Ingresa usuario: `admin`
5. (En modo prueba) Verifica el código en logs o pantalla
6. Ingresa el código
7. Establece nueva contraseña
8. Inicia sesión con la nueva contraseña

### Probar Recuperación por SMS:

1. Ve a: http://localhost/simahg/login.php
2. Clic en "¿Olvidaste tu contraseña?"
3. Selecciona "SMS al Celular"
4. Ingresa usuario: `admin`
5. El código aparecerá en pantalla (modo prueba)
6. Copia el código de 6 dígitos
7. Ingrésalo en el formulario
8. Establece nueva contraseña
9. Inicia sesión

---

## 🛠️ Mantenimiento

### Limpiar códigos expirados (ejecutar periódicamente):

```sql
DELETE FROM codigos_recuperacion WHERE expiracion < NOW() OR usado = 1;
```

### Ver códigos activos:

```sql
SELECT 
    cr.codigo,
    u.usuario,
    cr.metodo,
    cr.expiracion,
    cr.usado,
    CASE 
        WHEN cr.expiracion > NOW() AND cr.usado = 0 THEN 'ACTIVO'
        WHEN cr.expiracion <= NOW() THEN 'EXPIRADO'
        WHEN cr.usado = 1 THEN 'USADO'
    END as estado
FROM codigos_recuperacion cr
JOIN usuarios u ON cr.usuario_id = u.id
ORDER BY cr.fecha_creacion DESC
LIMIT 10;
```

---

## 📊 Estadísticas de Uso

```sql
-- Recuperaciones por método
SELECT 
    metodo,
    COUNT(*) as total,
    SUM(usado) as exitosos,
    COUNT(*) - SUM(usado) as fallidos
FROM codigos_recuperacion
GROUP BY metodo;

-- Últimas recuperaciones
SELECT 
    u.usuario,
    cr.metodo,
    cr.fecha_creacion,
    CASE WHEN cr.usado = 1 THEN 'Exitoso' ELSE 'Pendiente/Expirado' END as resultado
FROM codigos_recuperacion cr
JOIN usuarios u ON cr.usuario_id = u.id
ORDER BY cr.fecha_creacion DESC
LIMIT 20;
```

---

## ✅ Checklist de Implementación

### Para Desarrollo (Actual):
- [x] Formulario de recuperación
- [x] Generación de códigos
- [x] Verificación de códigos
- [x] Cambio de contraseña
- [x] Tabla en base de datos
- [x] Modo de prueba para SMS
- [x] Enlace en login

### Para Producción:
- [ ] Configurar PHPMailer o servicio de email
- [ ] Configurar servicio de SMS (Twilio/AWS/Nexmo)
- [ ] Probar con emails reales
- [ ] Probar con números reales
- [ ] Configurar logs de recuperación
- [ ] Implementar rate limiting (prevenir spam)
- [ ] Configurar alertas de seguridad
- [ ] Documentar para usuarios finales

---

## 🎨 Personalización

### Cambiar tiempo de expiración:

En `recuperar_password_process.php` línea 28:
```php
// 15 minutos (actual)
$expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));

// Cambiar a 30 minutos:
$expiracion = date('Y-m-d H:i:s', strtotime('+30 minutes'));

// Cambiar a 5 minutos:
$expiracion = date('Y-m-d H:i:s', strtotime('+5 minutes'));
```

En `verificar_codigo.php` línea 139:
```javascript
let timeLeft = 15 * 60; // Cambiar según el tiempo configurado
```

### Cambiar longitud del código:

En `recuperar_password_process.php` línea 27:
```php
// 6 dígitos (actual)
$codigo = sprintf('%06d', mt_rand(0, 999999));

// 4 dígitos:
$codigo = sprintf('%04d', mt_rand(0, 9999));

// 8 dígitos:
$codigo = sprintf('%08d', mt_rand(0, 99999999));
```

---

## 🆘 Solución de Problemas

### Problema: "Usuario no encontrado"
- Verifica que el usuario exista: `SELECT * FROM usuarios WHERE usuario = 'admin'`
- Verifica que esté activo: `estado = 1`

### Problema: No llegan emails
- Verifica configuración de `php.ini` y `sendmail.ini`
- Revisa logs de PHP: `/Applications/XAMPP/xamppfiles/logs/php_error_log`
- Usa PHPMailer en lugar de `mail()`

### Problema: "Código incorrecto o expirado"
- Verifica que no hayan pasado 15 minutos
- Verifica que el código no haya sido usado
- Query: `SELECT * FROM codigos_recuperacion WHERE usuario_id = X ORDER BY fecha_creacion DESC LIMIT 1`

### Problema: SMS no se envían (modo producción)
- Verifica credenciales del servicio SMS
- Verifica formato del número (+51987654321)
- Revisa balance/créditos del servicio
- Revisa logs del proveedor SMS

---

## 📚 Recursos Adicionales

- **Twilio PHP SDK:** https://www.twilio.com/docs/libraries/php
- **PHPMailer:** https://github.com/PHPMailer/PHPMailer
- **AWS SNS:** https://docs.aws.amazon.com/sns/
- **Vonage PHP SDK:** https://developer.vonage.com/

---

## 🎉 ¡Sistema Completo!

El sistema de recuperación de contraseña está **100% funcional** en modo de prueba y listo para integrarse con servicios reales de email y SMS.

**Próximos pasos:**
1. ✅ Probar en modo desarrollo
2. 🔧 Configurar servicios de email/SMS
3. 🚀 Desplegar en producción
4. 📊 Monitorear uso y seguridad
