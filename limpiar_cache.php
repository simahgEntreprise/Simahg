<?php
/**
 * Script para limpiar caché y verificar archivos
 */

echo "<h1>Limpieza de Caché del Sistema SIMAHG</h1>";
echo "<hr>";

// 1. Limpiar sesiones antiguas
echo "<h2>1. Limpiando sesiones antiguas...</h2>";
$session_path = __DIR__ . '/application/sessions/';
if (is_dir($session_path)) {
    $files = glob($session_path . '*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== 'index.html') {
            unlink($file);
            $count++;
        }
    }
    echo "<p style='color: green;'>✓ $count archivos de sesión eliminados</p>";
} else {
    echo "<p style='color: orange;'>⚠ Carpeta de sesiones no encontrada</p>";
}

// 2. Limpiar caché de CodeIgniter
echo "<h2>2. Limpiando caché de CodeIgniter...</h2>";
$cache_path = __DIR__ . '/application/cache/';
if (is_dir($cache_path)) {
    $files = glob($cache_path . '*');
    $count = 0;
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== 'index.html' && basename($file) !== '.htaccess') {
            unlink($file);
            $count++;
        }
    }
    echo "<p style='color: green;'>✓ $count archivos de caché eliminados</p>";
} else {
    echo "<p style='color: orange;'>⚠ Carpeta de caché no encontrada</p>";
}

// 3. Verificar archivo cambiar_password_process.php
echo "<h2>3. Verificando archivo cambiar_password_process.php...</h2>";
$file = __DIR__ . '/cambiar_password_process.php';
if (file_exists($file)) {
    $content = file_get_contents($file);
    if (strpos($content, 'fecha_modificacion') !== false) {
        echo "<p style='color: red;'>✗ ERROR: El archivo contiene 'fecha_modificacion'</p>";
        echo "<pre style='background: #fff3cd; padding: 10px; border: 1px solid #ffc107;'>";
        $lines = explode("\n", $content);
        foreach ($lines as $num => $line) {
            if (stripos($line, 'fecha_modificacion') !== false) {
                echo "Línea " . ($num + 1) . ": " . htmlspecialchars($line) . "\n";
            }
        }
        echo "</pre>";
    } else {
        echo "<p style='color: green;'>✓ El archivo NO contiene 'fecha_modificacion'</p>";
        
        // Mostrar la línea de UPDATE
        $lines = explode("\n", $content);
        foreach ($lines as $num => $line) {
            if (stripos($line, 'UPDATE usuario') !== false) {
                echo "<p><strong>Línea de UPDATE encontrada (línea " . ($num + 1) . "):</strong></p>";
                echo "<pre style='background: #d4edda; padding: 10px; border: 1px solid #28a745;'>";
                echo htmlspecialchars($line) . "\n";
                // Mostrar las siguientes 2 líneas
                if (isset($lines[$num + 1])) echo htmlspecialchars($lines[$num + 1]) . "\n";
                if (isset($lines[$num + 2])) echo htmlspecialchars($lines[$num + 2]) . "\n";
                echo "</pre>";
                break;
            }
        }
    }
    
    // Mostrar fecha de última modificación
    $mod_time = filemtime($file);
    echo "<p><strong>Última modificación:</strong> " . date('Y-m-d H:i:s', $mod_time) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Archivo no encontrado</p>";
}

// 4. Verificar permisos de archivos
echo "<h2>4. Verificando permisos...</h2>";
$files_to_check = [
    'cambiar_password_process.php',
    'cambiar_password.php',
    'login.php',
    'dashboard.php'
];

foreach ($files_to_check as $filename) {
    $filepath = __DIR__ . '/' . $filename;
    if (file_exists($filepath)) {
        $perms = substr(sprintf('%o', fileperms($filepath)), -4);
        echo "<p>✓ $filename - Permisos: $perms</p>";
    } else {
        echo "<p style='color: red;'>✗ $filename - No encontrado</p>";
    }
}

// 5. Información de PHP Opcache
echo "<h2>5. Estado de PHP Opcache...</h2>";
if (function_exists('opcache_get_status')) {
    $status = opcache_get_status();
    if ($status !== false) {
        echo "<p style='color: orange;'>⚠ Opcache está ACTIVADO</p>";
        echo "<p><strong>Acción recomendada:</strong> Reinicia Apache/XAMPP</p>";
        
        // Intentar resetear opcache
        if (function_exists('opcache_reset')) {
            opcache_reset();
            echo "<p style='color: green;'>✓ Opcache reseteado</p>";
        }
    } else {
        echo "<p style='color: green;'>✓ Opcache está desactivado o no disponible</p>";
    }
} else {
    echo "<p style='color: green;'>✓ Opcache no está instalado</p>";
}

// 6. Recomendaciones
echo "<hr>";
echo "<h2>Acciones a realizar:</h2>";
echo "<ol>";
echo "<li>Cierra todos los navegadores abiertos</li>";
echo "<li>Reinicia Apache/XAMPP desde el panel de control</li>";
echo "<li>Abre el navegador en modo incógnito o privado</li>";
echo "<li>Accede a: <a href='login.php'>login.php</a></li>";
echo "<li>Prueba cambiar la contraseña nuevamente</li>";
echo "</ol>";

echo "<hr>";
echo "<p style='text-align: center;'><a href='dashboard.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Volver al Dashboard</a></p>";
?>
