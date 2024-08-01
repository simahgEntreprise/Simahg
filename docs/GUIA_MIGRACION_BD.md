# 📘 Guía de Migración: Base de Datos Local → Remota

## 🎯 Objetivo
Este documento te guiará para migrar tu sistema SIMAHG de una base de datos local (XAMPP) a una base de datos remota en la nube, **cambiando solo un archivo**.

---

## ✅ Estado Actual del Sistema

### Tu Sistema YA Está Centralizado! 🎉
Todos tus módulos principales ya usan una configuración centralizada en:
- ✅ **`includes/config_common.php`**: Configuración centralizada (función `getDBConnection()`)
- ✅ **`solicitudes_epp.php`**: Ya usa `config_common.php`
- ✅ **`dashboard.php`**: Ya usa `config_common.php`
- ✅ **`equipos.php`**: Ya usa `config_common.php`
- ✅ **`mantenimientos.php`**: Ya usa `config_common.php`
- ✅ **`epp_gestion.php`**: Ya usa `config_common.php`
- ✅ **`reportes.php`**: Ya usa `config_common.php`
- ✅ **`usuarios.php`**: Ya usa `config_common.php`

**Esto significa**: Para migrar a BD remota, **solo editas `includes/config_common.php`** y TODOS los módulos se actualizan automáticamente.

---

## 🔧 PASO 1: Verificar la Configuración Actual (Ya está lista!)

Tu sistema ya está correctamente configurado. Todos los módulos usan:
```php
require_once 'includes/config_common.php';
$pdo = getDBConnection();
```

**No necesitas cambiar nada en los módulos individuales.** La configuración de BD está en `includes/config_common.php`.

---

## 🌐 PASO 2: Migrar a Base de Datos Remota

### Opción A: FreeSQLDatabase.com (Más Simple)

1. **Registrarte en**: https://www.freesqldatabase.com/
2. **Crear una base de datos MySQL** (te darán estos datos):
   - Host: `sql.freedb.tech`
   - Port: `3306`
   - Database Name: `freedb_simahg_xxxxx`
   - Username: `freedb_usuario`
   - Password: `la_que_elijas`

3. **Editar `includes/config_common.php`**:
   - Abre el archivo `includes/config_common.php`
   - Busca la función `getDBConnection()`
   - Comenta la configuración LOCAL
   - Descomenta y completa la configuración REMOTA:
   
   ```php
   // COMENTAR la configuración local:
   /*
   $host = 'localhost';
   $port = '3307';
   $dbname = 'simahg_db';
   $username = 'root';
   $password = '';
   */

   // DESCOMENTAR y completar la configuración remota:
   $host = 'sql.freedb.tech';
   $port = '3306';
   $dbname = 'freedb_simahg_xxxxx';  // ← Tu BD real
   $username = 'freedb_usuario';     // ← Tu usuario real
   $password = 'tu_password_real';   // ← Tu password real
   ```

4. **Exportar tu BD local**:
   - Abrir: http://localhost/phpmyadmin
   - Seleccionar `simahg_db`
   - Clic en "Exportar"
   - Descargar el archivo `.sql`

5. **Importar a la BD remota**:
   - Usar phpMyAdmin del servicio remoto
   - O usar MySQL Workbench
   - Importar el archivo `.sql`

---

### Opción B: db4free.net (Más Capacidad)

1. **Registrarte en**: https://www.db4free.net/
2. **Crear cuenta** (te dan 200MB)
3. **Editar en `includes/config_common.php` (función `getDBConnection()`):**
   ```php
   $host = 'db4free.net';
   $port = '3306';
   $dbname = 'simahg_remoto';      // ← El nombre que elijas
   $username = 'tu_usuario_db4';   // ← Tu usuario
   $password = 'tu_password_db4';  // ← Tu password
   ```

---

### Opción C: Railway.app (Más Profesional)

1. **Registrarte en**: https://railway.app/
2. **Crear proyecto** → **Add MySQL**
3. **Copiar las credenciales** que te da Railway
4. **Editar en `includes/config_common.php` (función `getDBConnection()`):**
   ```php
   $host = 'containers-us-west-123.railway.app'; // ← Host de Railway
   $port = '6789';                                // ← Puerto de Railway
   $dbname = 'railway';                           // ← Nombre de Railway
   $username = 'root';
   $password = 'password_largo_generado';         // ← Password de Railway
   ```

---

## 🔄 PASO 3: Cambiar entre Local y Remoto Fácilmente

### Para trabajar LOCAL (XAMPP):
Editar `includes/config_common.php` (dentro de la función `getDBConnection()`):
```php
// ✅ ACTIVAR ESTO:
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

// ⏸️ COMENTAR ESTO:
/*
$host = 'sql.freedb.tech';
$port = '3306';
$dbname = 'freedb_simahg_xxxxx';
$username = 'freedb_usuario';
$password = 'tu_password';
*/
```

### Para trabajar REMOTO:
Editar `includes/config_common.php` (dentro de la función `getDBConnection()`):
```php
// ⏸️ COMENTAR ESTO:
/*
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';
*/

// ✅ ACTIVAR ESTO:
$host = 'sql.freedb.tech';
$port = '3306';
$dbname = 'freedb_simahg_xxxxx';
$username = 'freedb_usuario';
$password = 'tu_password';
```

---

## 🚨 Solución de Problemas

### Error: "SQLSTATE[HY000] [2002] Connection refused"
**Solución**: Verifica que el host y puerto sean correctos.

### Error: "Access denied for user"
**Solución**: Verifica usuario y contraseña.

### Error: "Unknown database"
**Solución**: Verifica que el nombre de la BD exista en el servidor remoto.

### La aplicación está muy lenta
**Solución**: Es normal con servicios gratuitos, considera Railway o PlanetScale para mejor rendimiento.

---

## ✅ Lista de Verificación

Antes de migrar, asegúrate de:

- [ ] Hacer backup de tu BD local (`simahg_db.sql`)
- [ ] Crear cuenta en el servicio de BD remota
- [ ] Obtener las credenciales (host, puerto, nombre BD, usuario, password)
- [x] ~~Centralizar TODOS los módulos~~ (Ya está hecho! ✅)
- [ ] Editar `includes/config_common.php` con las credenciales remotas
- [ ] Importar tu BD al servidor remoto
- [ ] Probar el login y funciones básicas
- [ ] Guardar las credenciales en un lugar seguro

---

## 📞 Soporte

Si algo no funciona:
1. Verifica que XAMPP esté detenido si usas BD remota
2. Revisa los logs de errores de PHP
3. Abre `test_conexion.php` en tu navegador para diagnosticar problemas

---

**¡Listo! Con esto solo cambias `includes/config_common.php` y todo tu sistema se actualiza automáticamente.** 🎉
