# 🛡️ SIMAHG - Sistema Integral de Manejo de Almacén y Gestión Hospitalaria

## 📋 Descripción del Proyecto

SIMAHG es un sistema web integral desarrollado en PHP para la gestión hospitalaria, enfocado en el control de Equipos de Protección Personal (EPP), almacén, usuarios y procesos administrativos.

## ✨ Características Principales

### 🔐 Seguridad y Autenticación
- **Login seguro** con encriptación SHA1
- **Recuperación de contraseña** vía email (Gmail) o SMS
- **Cambio de contraseña** desde el perfil de usuario
- **Control de sesiones** con roles y permisos
- **Validación CSRF** y protección contra ataques

### 👥 Gestión de Usuarios
- Sistema de roles: Administrador, Supervisor, Almacenero, Trabajador
- Navbar dinámica según permisos
- Perfil de usuario con foto y datos personales
- Historial de actividades

### 📦 Gestión de EPP (Equipos de Protección Personal)
- Catálogo completo de EPP
- Solicitudes de EPP por trabajador
- Aprobación/Rechazo por supervisor
- Entrega por almacenero
- Historial de movimientos

### 📊 Reportes y Estadísticas
- Dashboard con métricas en tiempo real
- Reportes de solicitudes por estado
- Gráficos interactivos
- Exportación de datos

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 7+** - Lenguaje principal
- **MySQL/MariaDB** - Base de datos
- **PDO** - Capa de abstracción de base de datos
- **PHPMailer** - Envío de emails

### Frontend
- **HTML5, CSS3, JavaScript**
- **Bootstrap 3.3.7** - Framework CSS
- **jQuery 1.11.3** - Librería JavaScript
- **Font Awesome** - Iconos
- **DataTables** - Tablas interactivas
- **Morris.js / Chart.js** - Gráficos

### Arquitectura
- **MVC** - Modelo Vista Controlador
- **Sesiones PHP** - Gestión de usuarios
- **AJAX** - Interacciones asíncronas
- **Responsive Design** - Compatible con móviles

## 📁 Estructura del Proyecto

```
simahg/
├── application/          # Core de la aplicación
│   ├── config/          # Configuraciones
│   ├── controllers/     # Controladores MVC
│   ├── models/          # Modelos de datos
│   ├── views/           # Vistas
│   ├── libraries/       # Librerías propias
│   └── helpers/         # Funciones auxiliares
├── includes/            # Configuración compartida
│   └── config_common.php # Navbar y roles centralizados
├── database/            # Scripts SQL
│   ├── simahg_db.sql   # Base de datos completa
│   └── *.sql           # Actualizaciones
├── docs/                # Documentación
│   ├── guia_visual_gmail.html
│   ├── diagnostico_email.php
│   └── *.md            # Guías técnicas
├── admin/               # Herramientas de administración
│   ├── mis_usuarios.php
│   └── resetear_passwords.php
├── bower_components/    # Dependencias frontend
├── images/              # Recursos gráficos
├── js/                  # JavaScript
└── css/                 # Estilos CSS
```

## 🚀 Instalación

### Prerrequisitos
- XAMPP (Apache + MySQL + PHP)
- Navegador web moderno
- Cuenta de Gmail (para recuperación de contraseña)

### Pasos de Instalación

1. **Clonar/Descargar el proyecto**
   ```bash
   # Colocar en: /Applications/XAMPP/xamppfiles/htdocs/simahg
   ```

2. **Importar base de datos**
   - Abrir phpMyAdmin: `http://localhost/phpmyadmin`
   - Crear base de datos: `simahg_db`
   - Importar: `database/simahg_db.sql`

3. **Configurar base de datos**
   - Editar: `includes/config_common.php`
   - Ajustar credenciales si es necesario

4. **Configurar email (opcional)**
   - Copiar: `config_email.example.php` → `config_email.php`
   - Configurar Gmail según `docs/GUIA_CONFIGURAR_GMAIL.md`

5. **Instalar dependencias PHP**
   ```bash
   composer install
   ```

6. **Acceder al sistema**
   - URL: `http://localhost/simahg/`
   - Usuario: `admin`
   - Contraseña: `123456`

## 👤 Usuarios de Prueba

| Usuario | Contraseña | Rol | Descripción |
|---------|------------|-----|-------------|
| `admin` | `123456` | Administrador | Acceso completo al sistema |
| `supervisor1` | `123456` | Supervisor | Aprueba/rechaza solicitudes |
| `almacenero1` | `123456` | Almacenero | Entrega EPP |
| `trabajador1` | `123456` | Trabajador | Solicita EPP |

> **Nota:** Para ver todos los usuarios o resetear contraseñas, usar: `admin/mis_usuarios.php`

## 📖 Documentación

Toda la documentación técnica está en la carpeta `docs/`:

- **GUIA_CONFIGURAR_GMAIL.md** - Configuración de Gmail para recuperación
- **RECUPERACION_PASSWORD.md** - Sistema de recuperación de contraseña
- **CAMBIAR_PASSWORD.md** - Cambio de contraseña desde perfil
- **guia_visual_gmail.html** - Guía visual interactiva de Gmail
- **diagnostico_email.php** - Herramienta de diagnóstico de email

## 🔧 Configuración

### Base de Datos (`includes/config_common.php`)
```php
$host = 'localhost';
$dbname = 'simahg_db';
$username = 'root';
$password = '';
```

### Email (`config_email.php`)
```php
$smtp_host = 'smtp.gmail.com';
$smtp_username = 'tu-email@gmail.com';
$smtp_password = 'tu-app-password';
```

## 🎯 Funcionalidades Principales

### 1. Login y Recuperación
- `login.php` - Página de inicio de sesión
- `recuperar_password.php` - Recuperación por email/SMS
- `verificar_codigo.php` - Validación de código
- `nueva_password.php` - Establecer nueva contraseña

### 2. Cambio de Contraseña
- Acceso desde navbar → Usuario → 🔑 Cambiar Contraseña
- Validación de contraseña actual
- Requisitos mínimos de seguridad

### 3. Gestión de EPP
- `solicitudes_epp.php` - Solicitar EPP
- `aprobar_solicitud.php` - Supervisores aprueban
- `entregar_epp.php` - Almaceneros entregan
- `historial_epp.php` - Historial completo

### 4. Dashboard
- `home.php` - Panel principal con métricas
- Gráficos de solicitudes
- Indicadores en tiempo real
- Acceso rápido a funciones

## 🔒 Seguridad Implementada

✅ **Encriptación de contraseñas** (SHA1)  
✅ **Validación de sesiones** en cada página  
✅ **Control de acceso por roles**  
✅ **Prepared Statements** (PDO) contra SQL Injection  
✅ **Validación de datos** en frontend y backend  
✅ **Protección CSRF** en formularios  
✅ **Sanitización de inputs** contra XSS  
✅ **Timeouts de sesión**  

## 🧪 Testing

### Herramientas de Administración (`admin/`)
- **mis_usuarios.php** - Ver todos los usuarios del sistema
- **resetear_passwords.php** - Resetear contraseñas a `123456`

### Diagnóstico (`docs/`)
- **diagnostico_email.php** - Verificar configuración de email
- Pruebas de envío de correo
- Validación de credenciales Gmail

## 📧 Configuración de Gmail

Para habilitar la recuperación de contraseña por email:

1. Habilitar verificación en 2 pasos en tu cuenta Gmail
2. Generar "Contraseña de aplicación"
3. Configurar en `config_email.php`
4. Probar con `docs/diagnostico_email.php`

Ver guía completa en: `docs/GUIA_CONFIGURAR_GMAIL.md`

## 🐛 Solución de Problemas

### No puedo iniciar sesión
- Verificar que la base de datos esté importada
- Usar `admin/resetear_passwords.php` para resetear contraseñas
- Revisar que XAMPP esté corriendo

### No llegan emails de recuperación
- Verificar configuración en `config_email.php`
- Usar `docs/diagnostico_email.php` para probar
- Verificar "Contraseña de aplicación" de Gmail

### Error de base de datos
- Verificar credenciales en `includes/config_common.php`
- Asegurarse que la base de datos `simahg_db` existe
- Revisar que las tablas estén creadas

## 📞 Soporte

Para dudas o problemas:
- Revisar documentación en carpeta `docs/`
- Consultar código fuente (bien comentado)
- Usar herramientas de diagnóstico en `admin/` y `docs/`

---

## 🎓 Proyecto Académico

Desarrollado como sistema integral de gestión hospitalaria con enfoque en:
- Arquitectura MVC limpia
- Seguridad robusta
- UX/UI moderna
- Código mantenible y documentado
- Buenas prácticas de desarrollo

**¡Sistema completamente funcional y listo para producción!** 🚀

---

**Última actualización:** Diciembre 2025
