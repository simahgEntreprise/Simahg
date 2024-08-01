@echo off
echo.
echo 🚀 SIMAHG - Script de Instalacion
echo ==================================
echo.

REM Verificar si estamos en el directorio correcto
if not exist "index.php" (
    echo ❌ Error: Ejecuta este script desde el directorio raiz del proyecto SIMAHG
    pause
    exit /b 1
)

echo 📁 Verificando estructura del proyecto...

REM Crear directorios necesarios si no existen
if not exist "application\logs" mkdir application\logs
if not exist "application\sessions" mkdir application\sessions
if not exist "uploads" mkdir uploads

echo.
echo 🗄️  Configuracion de Base de Datos
echo ==================================
echo.
echo Para completar la instalacion, necesitas:
echo.
echo 1️⃣  Asegurate de que XAMPP este corriendo
echo    - Inicia Apache
echo    - Inicia MySQL (puerto 3307)
echo.
echo 2️⃣  Importa la base de datos:
echo    - Ve a http://localhost/phpmyadmin
echo    - Crea una nueva base de datos llamada 'simahg_db'
echo    - Importa el archivo: database\simahg_db.sql
echo.
echo 3️⃣  Verifica la configuracion:
echo    - Archivo: application\config\database.php
echo    - Puerto MySQL: 3307
echo    - Usuario: root (por defecto)
echo    - Contraseña: (vacia por defecto)
echo.
echo 🌐 Configuracion Web
echo ==================
echo.
echo URL del sistema: http://localhost/simahg/
echo.
echo 👤 Usuarios de prueba disponibles:
echo ┌─────────────┬────────────┬───────────────┐
echo │ Usuario     │ Contraseña │ Perfil        │
echo ├─────────────┼────────────┼───────────────┤
echo │ admin       │ 123456     │ Administrador │
echo │ jperez      │ 123456     │ Supervisor    │
echo │ mgarcia     │ 123456     │ Operador      │
echo │ prodriguez  │ 123456     │ Usuario       │
echo └─────────────┴────────────┴───────────────┘
echo.
echo ✅ ¡Instalacion completada!
echo.
echo 🚀 Para iniciar:
echo    1. Ve a http://localhost/simahg/
echo    2. Inicia sesion con cualquier usuario de prueba
echo    3. Explora el dashboard moderno
echo.
echo 📚 Para mas informacion, revisa README.md
echo.
pause
