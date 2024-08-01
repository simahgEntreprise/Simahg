# 🔐 CREDENCIALES DE PRUEBA - SIMAHG

## 👥 USUARIOS DISPONIBLES

### 1️⃣ **Administrador** (Acceso completo)
```
Usuario:   admin
Contraseña: admin123 (o la que configuraste)
Perfil:    Administrador
```
**Permisos:**
- ✅ Ver todas las solicitudes
- ✅ Aprobar/Rechazar solicitudes
- ✅ Entregar EPPs
- ✅ Gestionar usuarios, equipos, inventario
- ✅ Acceso a todos los módulos

---

### 2️⃣ **Supervisor**
```
Usuario:   jperez
Contraseña: (configurar o usar la que tengas)
Perfil:    Supervisor
```
**Permisos:**
- ✅ Ver todas las solicitudes
- ✅ Aprobar/Rechazar solicitudes
- ✅ Entregar EPPs
- ✅ Gestionar equipos y mantenimientos
- ❌ No puede gestionar usuarios

---

### 3️⃣ **Operador/Trabajador**
```
Usuario:   mgarcia
Contraseña: (configurar o usar la que tengas)
Perfil:    Operador
```
**Permisos:**
- ✅ Ver solo SUS solicitudes
- ✅ Crear nuevas solicitudes
- ❌ No puede aprobar/rechazar
- ❌ No puede entregar EPPs

---

## 🔧 CONFIGURAR CONTRASEÑAS

Si necesitas establecer contraseñas para probar:

```sql
-- Conectar a la base de datos
mysql --port=3307 -u root simahg_db

-- Actualizar contraseñas (MD5 - solo para pruebas)
UPDATE usuarios SET password = MD5('admin123') WHERE usuario = 'admin';
UPDATE usuarios SET password = MD5('super123') WHERE usuario = 'jperez';
UPDATE usuarios SET password = MD5('opera123') WHERE usuario = 'mgarcia';
```

O crear un script PHP para generar hashes seguros:
```php
<?php
// Usar password_hash() en vez de MD5 en producción
echo password_hash('admin123', PASSWORD_DEFAULT);
?>
```

---

## 🧪 ESCENARIOS DE PRUEBA

### Escenario 1: Flujo completo exitoso
```
1. Login como 'mgarcia' (Operador)
2. Ir a Solicitudes de EPPs
3. Crear solicitud de "Casco de Seguridad" x2
4. Justificar: "Para obra en edificio B"
5. Logout

6. Login como 'jperez' (Supervisor)
7. Ver solicitud pendiente de mgarcia
8. Aprobar solicitud
9. Entregar EPP
10. Verificar que stock se descontó
```

### Escenario 2: Rechazo de solicitud
```
1. Login como 'mgarcia'
2. Crear solicitud exagerada (100 cascos)
3. Logout

4. Login como 'admin'
5. Ver solicitud
6. Rechazar con motivo: "Cantidad excesiva, solicitar cantidad real"
7. Logout

8. Login como 'mgarcia'
9. Ver su solicitud rechazada con el motivo
```

### Escenario 3: Múltiples solicitudes
```
1. Login como 'mgarcia'
2. Crear 3 solicitudes diferentes:
   - 2 Cascos
   - 5 Guantes
   - 1 Botas
3. Logout

4. Login como 'jperez'
5. Ver las 3 solicitudes
6. Aprobar 2, rechazar 1
7. Entregar las 2 aprobadas
8. Verificar stock de cada EPP
```

---

## 📊 DATOS DE PRUEBA EN LA BD

### EPPs disponibles:
```
| ID | Nombre                    | Stock | Estado |
|----|---------------------------|-------|--------|
| 1  | Casco de Seguridad Blanco | 25    | activo |
| 2  | Mascarilla N95            | 150   | activo |
| 3  | Guantes de Látex          | 100   | activo |
| 4  | Botas de Seguridad        | 15    | activo |
```

### Perfiles disponibles:
```
| ID | Nombre        |
|----|---------------|
| 1  | Administrador |
| 2  | Supervisor    |
| 3  | Operador      |
| 4  | Usuario       |
```

---

## 🎯 URLs DE ACCESO

### Producción Local:
```
Login:      http://localhost/simahg/login.php
Dashboard:  http://localhost/simahg/dashboard.php
Solicitudes: http://localhost/simahg/solicitudes_epp_v2.php
```

### Si usas puerto diferente:
```
http://localhost:8080/simahg/...
```

---

## 🔍 VERIFICACIONES DE SEGURIDAD

### Protección de sesión:
```php
// En solicitudes_epp_v2.php líneas 7-11
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}
```
✅ Si no está logueado, redirige al login

### Control de roles:
```php
// Funciones de ayuda (líneas 42-56)
function esAdmin() { ... }
function esSupervisor() { ... }
function esTrabajador() { ... }
function puedeGestionar() { ... }
```
✅ Solo supervisores/admins pueden aprobar/entregar

### Sanitización de inputs:
```php
// Línea 31
function sanitizar($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
```
✅ Protege contra XSS

### Transacciones de BD:
```php
// Líneas 151-179 (al entregar EPP)
$pdo->beginTransaction();
// ... operaciones ...
$pdo->commit();
```
✅ Garantiza consistencia de datos

---

## 🚨 IMPORTANTE - SEGURIDAD

### Para producción:
1. ❗ Cambiar contraseñas por defecto
2. ❗ Usar `password_hash()` en vez de MD5
3. ❗ Implementar límite de intentos de login
4. ❗ Activar HTTPS
5. ❗ Configurar backup automático de BD
6. ❗ Implementar logs de auditoría
7. ❗ Validar todos los inputs en servidor

### Configuración recomendada:
```php
// config.php
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_NAME', 'simahg_db');
define('DB_USER', 'simahg_user'); // NO usar root
define('DB_PASS', 'contraseña_segura_aquí');

// Habilitar logs
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php-errors.log');

// Deshabilitar errores en pantalla en producción
ini_set('display_errors', 0);
```

---

## 📱 ACCESO MÓVIL (Futuro)

El módulo web está listo. Para la versión móvil:
1. Crear API REST basada en este código
2. Endpoints JSON para:
   - GET /api/solicitudes
   - POST /api/solicitudes
   - PUT /api/solicitudes/{id}/aprobar
   - PUT /api/solicitudes/{id}/rechazar
   - PUT /api/solicitudes/{id}/entregar
3. Implementar autenticación JWT
4. Desarrollar app en Flutter/React Native

---

## ✅ CHECKLIST DE PRODUCCIÓN

Antes de poner en producción:
- [ ] Cambiar credenciales de BD
- [ ] Eliminar usuarios de prueba
- [ ] Configurar HTTPS
- [ ] Hacer backup de BD
- [ ] Probar todos los flujos
- [ ] Documentar para usuarios finales
- [ ] Capacitar al personal
- [ ] Configurar monitoreo
- [ ] Establecer política de respaldo
- [ ] Definir SLA de soporte

---

**🎉 ¡Sistema listo para pruebas!**

**Última actualización:** 21/12/2024
**Versión:** 2.0
**Estado:** ✅ OPERATIVO
