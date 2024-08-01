# 📚 Índice de Documentación - SIMAHG

Bienvenido a la documentación técnica del Sistema Integral de Manejo de Almacén y Gestión Hospitalaria (SIMAHG).

## 🎯 Guías de Usuario

### 🔐 Autenticación y Seguridad
- **[GUIA_CONFIGURAR_GMAIL.md](GUIA_CONFIGURAR_GMAIL.md)** - Configuración paso a paso de Gmail para recuperación de contraseña
- **[RECUPERACION_PASSWORD.md](RECUPERACION_PASSWORD.md)** - Sistema de recuperación de contraseña (email/SMS)
- **[CAMBIAR_PASSWORD.md](CAMBIAR_PASSWORD.md)** - Cómo cambiar contraseña desde el perfil de usuario
- **[guia_visual_gmail.html](guia_visual_gmail.html)** - Guía visual interactiva para configurar Gmail

## 🛠️ Herramientas de Diagnóstico

### 📧 Email y Comunicaciones
- **[diagnostico_email.php](diagnostico_email.php)** - Verificar configuración de PHPMailer y Gmail
  - Prueba de conexión SMTP
  - Envío de email de prueba
  - Validación de credenciales

## 📖 Documentación Técnica

### 🏗️ Arquitectura del Sistema
- **[ARQUITECTURA_SISTEMA.md](ARQUITECTURA_SISTEMA.md)** - Estructura general del proyecto
- **[CONFIGURACION_BD_CENTRALIZADA.md](CONFIGURACION_BD_CENTRALIZADA.md)** - Base de datos y configuración centralizada

### ✅ Validación y Testing
- **[VALIDACION_CRUD.md](VALIDACION_CRUD.md)** - Validación de operaciones CRUD
- **[GUIA_PRUEBAS.md](GUIA_PRUEBAS.md)** - Guía de pruebas del sistema

### 📝 Implementación y Cambios
- **[RESUMEN_IMPLEMENTACION.md](RESUMEN_IMPLEMENTACION.md)** - Resumen de funcionalidades implementadas
- **[RESUMEN_CAMBIOS.md](RESUMEN_CAMBIOS.md)** - Historial de cambios importantes
- **[ALINEACION_COMPLETA.md](ALINEACION_COMPLETA.md)** - Alineación de módulos y componentes

## 🚀 Guías Rápidas

### Para Desarrolladores
1. **Configurar el proyecto:** Ver [README.md](../README.md) principal
2. **Configurar email:** Ver [GUIA_CONFIGURAR_GMAIL.md](GUIA_CONFIGURAR_GMAIL.md)
3. **Validar instalación:** Usar [diagnostico_email.php](diagnostico_email.php)

### Para Usuarios
1. **Login:** `http://localhost/simahg/login.php`
2. **¿Olvidaste tu contraseña?:** Usar flujo de recuperación desde login
3. **Cambiar contraseña:** Navbar → Tu nombre → 🔑 Cambiar Contraseña

### Para Administradores
1. **Ver usuarios:** `/admin/mis_usuarios.php`
2. **Resetear contraseñas:** `/admin/resetear_passwords.php`
3. **Probar email:** `/docs/diagnostico_email.php`

## 📂 Estructura de Archivos

```
docs/
├── README.md (este archivo)
├── guia_visual_gmail.html          # Guía visual interactiva
├── diagnostico_email.php            # Herramienta de diagnóstico
├── GUIA_CONFIGURAR_GMAIL.md        # Configuración Gmail
├── RECUPERACION_PASSWORD.md         # Sistema de recuperación
├── CAMBIAR_PASSWORD.md              # Cambio de contraseña
└── [otros archivos técnicos]        # Documentación adicional
```

## 🔗 Enlaces Útiles

### Aplicación
- **Sistema principal:** http://localhost/simahg/
- **Login:** http://localhost/simahg/login.php
- **phpMyAdmin:** http://localhost/phpmyadmin

### Herramientas
- **Ver usuarios:** http://localhost/simahg/admin/mis_usuarios.php
- **Resetear passwords:** http://localhost/simahg/admin/resetear_passwords.php
- **Diagnóstico email:** http://localhost/simahg/docs/diagnostico_email.php
- **Guía Gmail:** http://localhost/simahg/docs/guia_visual_gmail.html

## 💡 Preguntas Frecuentes

### ¿Cómo configuro el email?
Ver **[GUIA_CONFIGURAR_GMAIL.md](GUIA_CONFIGURAR_GMAIL.md)** - Incluye capturas de pantalla y pasos detallados.

### ¿Cómo pruebo el email?
Usar **[diagnostico_email.php](diagnostico_email.php)** - Ejecuta pruebas automáticas de configuración.

### ¿Cómo reseteo contraseñas?
Ir a `/admin/resetear_passwords.php` - Cambia todas las contraseñas a `123456`.

### ¿Cómo veo los usuarios del sistema?
Ir a `/admin/mis_usuarios.php` - Lista todos los usuarios con sus credenciales.

## 📝 Notas Importantes

- ⚠️ Los archivos en `/admin/` son herramientas de administración - **no exponer en producción**
- 🔐 Cambiar las contraseñas por defecto antes de usar en producción
- 📧 Configurar Gmail con "Contraseña de aplicación", no la contraseña normal
- 🛡️ El sistema usa SHA1 para contraseñas - considerar actualizar a bcrypt en producción

## 🆘 Soporte

Si tienes problemas:

1. **Revisa la documentación relevante** en esta carpeta
2. **Usa las herramientas de diagnóstico** (`/admin/` y `/docs/`)
3. **Verifica la configuración** en `includes/config_common.php`
4. **Revisa los logs** de Apache y MySQL

---

**Última actualización:** Diciembre 2025  
**Proyecto:** SIMAHG - Sistema Integral de Manejo de Almacén y Gestión Hospitalaria
