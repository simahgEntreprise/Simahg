#!/bin/bash

# Script de Instalación de PHPMailer para SIMAHG
# Ejecuta: bash instalar_phpmailer.sh

echo "📧 Instalando PHPMailer para SIMAHG..."
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Ir al directorio del proyecto
cd /Applications/XAMPP/xamppfiles/htdocs/simahg

# Verificar si composer está instalado
if ! command -v composer &> /dev/null; then
    echo "⚠️  Composer no está instalado globalmente"
    echo "📦 Descargando Composer local..."
    
    # Descargar composer.phar
    curl -sS https://getcomposer.org/installer | php
    
    if [ $? -eq 0 ]; then
        echo "✅ Composer descargado correctamente"
        echo ""
        echo "📦 Instalando PHPMailer..."
        php composer.phar require phpmailer/phpmailer
    else
        echo "❌ Error al descargar Composer"
        exit 1
    fi
else
    echo "✅ Composer encontrado"
    echo ""
    echo "📦 Instalando PHPMailer..."
    composer require phpmailer/phpmailer
fi

# Verificar instalación
if [ -d "vendor/phpmailer" ]; then
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "✅ ¡PHPMailer instalado correctamente!"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "📝 Próximos pasos:"
    echo ""
    echo "1️⃣  Genera una contraseña de aplicación en Gmail:"
    echo "    👉 https://myaccount.google.com/apppasswords"
    echo ""
    echo "2️⃣  Edita el archivo: config_email.php"
    echo "    Cambia 'TU_EMAIL@gmail.com' por tu email real"
    echo "    Cambia 'tu_contraseña_aplicacion' por la contraseña generada"
    echo ""
    echo "3️⃣  Prueba el sistema:"
    echo "    👉 http://localhost/simahg/test_email.php"
    echo ""
    echo "📚 Documentación completa: GUIA_CONFIGURAR_GMAIL.md"
    echo ""
else
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "❌ Error al instalar PHPMailer"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "Intenta instalarlo manualmente:"
    echo "cd /Applications/XAMPP/xamppfiles/htdocs/simahg"
    echo "composer require phpmailer/phpmailer"
    echo ""
    exit 1
fi
