<?php
/**
 * CORRECCIÓN AUTOMÁTICA MASIVA DE TODO EL SISTEMA
 * Este script corrige automáticamente todos los archivos PHP del sistema
 */

set_time_limit(300); // 5 minutos

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Corrección Automática SIMAHG</title>";
echo "<style>
body { font-family: Arial; margin: 20px; background: #f4f6f9; }
.container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
h1 { color: #667eea; text-align: center; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
.warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
.error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
.file { background: #f8f9fa; padding: 10px; margin: 5px 0; border-left: 3px solid #667eea; }
table { width: 100%; border-collapse: collapse; margin: 20px 0; }
th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
th { background: #667eea; color: white; }
.btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
</style></head><body><div class='container'>";

echo "<h1>🔧 Corrección Automática del Sistema SIMAHG</h1>";
echo "<p style='text-align: center;'>Escaneando y corrigiendo todos los archivos PHP...</p>";
echo "<hr>";

$rootDir = __DIR__;
$archivosCorregidos = 0;
$erroresEncontrados = 0;
$cambiosTotales = 0;

// Patrones a buscar y reemplazar
$patrones = [
    // Tablas
    [
        'buscar' => '/FROM\s+usuario\s+(WHERE|LEFT|INNER|JOIN|ORDER|GROUP|LIMIT)/i',
        'reemplazar' => 'FROM usuarios $1',
        'descripcion' => 'Tabla usuario → usuarios'
    ],
    [
        'buscar' => '/UPDATE\s+usuario\s+SET/i',
        'reemplazar' => 'UPDATE usuarios SET',
        'descripcion' => 'UPDATE usuario → usuarios'
    ],
    [
        'buscar' => '/INSERT\s+INTO\s+usuario\s+\(/i',
        'reemplazar' => 'INSERT INTO usuarios (',
        'descripcion' => 'INSERT INTO usuario → usuarios'
    ],
    [
        'buscar' => '/DELETE\s+FROM\s+usuario\s+WHERE/i',
        'reemplazar' => 'DELETE FROM usuarios WHERE',
        'descripcion' => 'DELETE FROM usuario → usuarios'
    ],
    
    // Campos
    [
        'buscar' => '/\bidusuario\b/',
        'reemplazar' => 'id',
        'descripcion' => 'Campo idusuario → id',
        'solo_sql' => true
    ],
    [
        'buscar' => '/\bapellido\b(?!\s*\()/i',
        'reemplazar' => 'apellidos',
        'descripcion' => 'Campo apellido → apellidos',
        'solo_sql' => true
    ],
    [
        'buscar' => '/\bactivo\b(?=\s*=|\s+AND|\s+OR|,)/i',
        'reemplazar' => 'estado',
        'descripcion' => 'Campo activo → estado',
        'solo_sql' => true
    ],
];

// Función para escanear archivos
function escanearDirectorio($dir, &$archivos) {
    if (!is_dir($dir)) return;
    
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        
        $path = $dir . '/' . $item;
        
        if (is_dir($path)) {
            // Saltar algunas carpetas
            if (in_array($item, ['vendor', 'node_modules', 'bower_components', '.git', 'system'])) {
                continue;
            }
            escanearDirectorio($path, $archivos);
        } elseif (is_file($path) && pathinfo($path, PATHINFO_EXTENSION) == 'php') {
            // Saltar archivos de verificación y prueba
            $basename = basename($path);
            if (in_array($basename, ['correccion_masiva.php', 'verificacion_final.php', 'limpiar_cache.php', 'prueba_cambio_password.php'])) {
                continue;
            }
            $archivos[] = $path;
        }
    }
}

echo "<h2>📁 Escaneando archivos...</h2>";
$archivos = [];
escanearDirectorio($rootDir, $archivos);
echo "<div class='success'>✓ Se encontraron " . count($archivos) . " archivos PHP</div>";

echo "<h2>🔍 Analizando y corrigiendo...</h2>";
echo "<table>";
echo "<tr><th>Archivo</th><th>Cambios</th><th>Estado</th></tr>";

foreach ($archivos as $archivo) {
    $contenidoOriginal = file_get_contents($archivo);
    $contenido = $contenidoOriginal;
    $cambiosArchivo = 0;
    $cambiosDetalle = [];
    
    // Aplicar cada patrón
    foreach ($patrones as $patron) {
        $contenidoAntes = $contenido;
        $contenido = preg_replace($patron['buscar'], $patron['reemplazar'], $contenido, -1, $count);
        
        if ($count > 0) {
            $cambiosArchivo += $count;
            $cambiosDetalle[] = $patron['descripcion'] . " ($count)";
        }
    }
    
    // Si hubo cambios, guardar el archivo
    if ($contenido !== $contenidoOriginal) {
        if (file_put_contents($archivo, $contenido)) {
            echo "<tr>";
            echo "<td>" . str_replace($rootDir, '', $archivo) . "</td>";
            echo "<td>" . $cambiosArchivo . " cambios<br><small>" . implode(', ', $cambiosDetalle) . "</small></td>";
            echo "<td style='color: green;'>✓ Corregido</td>";
            echo "</tr>";
            
            $archivosCorregidos++;
            $cambiosTotales += $cambiosArchivo;
        } else {
            echo "<tr>";
            echo "<td>" . str_replace($rootDir, '', $archivo) . "</td>";
            echo "<td colspan='2' style='color: red;'>✗ Error al guardar</td>";
            echo "</tr>";
            $erroresEncontrados++;
        }
    }
}

echo "</table>";

echo "<hr>";
echo "<h2>📊 Resumen Final</h2>";
echo "<table style='width: auto; margin: 20px auto;'>";
echo "<tr><th>Archivos analizados</th><td style='text-align: center; font-size: 20px;'>" . count($archivos) . "</td></tr>";
echo "<tr><th>Archivos corregidos</th><td style='text-align: center; font-size: 20px; color: green;'>" . $archivosCorregidos . "</td></tr>";
echo "<tr><th>Cambios totales</th><td style='text-align: center; font-size: 20px; color: blue;'>" . $cambiosTotales . "</td></tr>";
echo "<tr><th>Errores</th><td style='text-align: center; font-size: 20px; color: red;'>" . $erroresEncontrados . "</td></tr>";
echo "</table>";

if ($archivosCorregidos > 0) {
    echo "<div class='success' style='text-align: center; font-size: 18px; padding: 30px;'>";
    echo "<strong>🎉 ¡CORRECCIÓN COMPLETADA EXITOSAMENTE!</strong><br>";
    echo "Se corrigieron $archivosCorregidos archivos con $cambiosTotales cambios totales.";
    echo "</div>";
} else {
    echo "<div class='warning' style='text-align: center; font-size: 18px; padding: 30px;'>";
    echo "<strong>✓ El sistema ya está correctamente configurado</strong><br>";
    echo "No se encontraron archivos que necesiten corrección.";
    echo "</div>";
}

echo "<hr>";
echo "<h2>🚀 Próximos Pasos</h2>";
echo "<div class='warning'>";
echo "<ol>";
echo "<li><strong>Reinicia Apache en XAMPP</strong></li>";
echo "<li><strong>Cierra todos los navegadores</strong></li>";
echo "<li><strong>Abre en modo incógnito</strong></li>";
echo "<li><strong>Prueba todas las funcionalidades:</strong>";
echo "<ul>";
echo "<li>Login</li>";
echo "<li>Cambio de contraseña</li>";
echo "<li>Gestión de usuarios (CRUD)</li>";
echo "<li>Módulos EPP</li>";
echo "<li>Reportes</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='login.php' class='btn'>🔐 Ir a Login</a> ";
echo "<a href='dashboard.php' class='btn'>📊 Dashboard</a> ";
echo "<a href='verificacion_final.php' class='btn'>✓ Verificar Sistema</a>";
echo "</div>";

echo "</div></body></html>";
?>
