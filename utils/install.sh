#!/bin/bash

echo "🚀 SIMAHG - Script de Instalación"
echo "=================================="
echo ""

# Verificar si estamos en el directorio correcto
if [ ! -f "index.php" ]; then
    echo "❌ Error: Ejecuta este script desde el directorio raíz del proyecto SIMAHG"
    exit 1
fi

echo "📁 Verificando estructura del proyecto..."

# Crear directorios necesarios si no existen
mkdir -p application/logs
mkdir -p application/sessions
mkdir -p uploads

echo "🔐 Configurando permisos de archivos..."

# Configurar permisos para directorios escribibles
chmod 755 application/logs
chmod 755 application/sessions
chmod 755 uploads

# Para sistemas Unix/Linux/macOS
if [ "$(uname)" != "Darwin" ] && [ "$(uname)" != "Linux" ]; then
    echo "⚠️  Sistema no Unix detectado. Configura manualmente los permisos."
else
    chmod -R 755 application/logs
    chmod -R 755 application/sessions
    chmod -R 755 uploads
fi

echo "🗄️  Configuración de Base de Datos"
echo "=================================="
echo ""
echo "Para completar la instalación, necesitas:"
echo ""
echo "1️⃣  Asegurate de que XAMPP esté corriendo"
echo "   - Inicia Apache"
echo "   - Inicia MySQL (puerto 3307)"
echo ""
echo "2️⃣  Importa la base de datos:"
echo "   - Ve a http://localhost/phpmyadmin"
echo "   - Crea una nueva base de datos llamada 'simahg_db'"
echo "   - Importa el archivo: database/simahg_db.sql"
echo ""
echo "3️⃣  Verifica la configuración:"
echo "   - Archivo: application/config/database.php"
echo "   - Puerto MySQL: 3307"
echo "   - Usuario: root (por defecto)"
echo "   - Contraseña: (vacía por defecto)"
echo ""
echo "🌐 Configuración Web"
echo "=================="
echo ""
echo "URL del sistema: http://localhost/simahg/"
echo ""
echo "👤 Usuarios de prueba disponibles:"
echo "┌─────────────┬────────────┬───────────────┐"
echo "│ Usuario     │ Contraseña │ Perfil        │"
echo "├─────────────┼────────────┼───────────────┤"
echo "│ admin       │ 123456     │ Administrador │"
echo "│ jperez      │ 123456     │ Supervisor    │"
echo "│ mgarcia     │ 123456     │ Operador      │"
echo "│ prodriguez  │ 123456     │ Usuario       │"
echo "└─────────────┴────────────┴───────────────┘"
echo ""
echo "✅ ¡Instalación completada!"
echo ""
echo "🚀 Para iniciar:"
echo "   1. Ve a http://localhost/simahg/"
echo "   2. Inicia sesión con cualquier usuario de prueba"
echo "   3. Explora el dashboard moderno"
echo ""
echo "📚 Para más información, revisa README.md"
echo ""
