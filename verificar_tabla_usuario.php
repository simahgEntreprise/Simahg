<?php
/**
 * Verificar estructura de la tabla usuario
 */

$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Estructura de la Tabla 'usuario'</h1>";
    echo "<hr>";
    
    // Obtener estructura
    $sql = "DESCRIBE usuario";
    $stmt = $pdo->query($sql);
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #007bff; color: white;'>";
    echo "<th>Campo</th><th>Tipo</th><th>Nulo</th><th>Key</th><th>Default</th><th>Extra</th>";
    echo "</tr>";
    
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($col['Field']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Default']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Extra']) . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    // Verificar si existe campo fecha_modificacion
    echo "<hr>";
    echo "<h2>Verificación:</h2>";
    
    $has_fecha_mod = false;
    foreach ($columns as $col) {
        if (strtolower($col['Field']) === 'fecha_modificacion') {
            $has_fecha_mod = true;
            break;
        }
    }
    
    if ($has_fecha_mod) {
        echo "<p style='color: orange; font-size: 18px;'>⚠ La tabla SÍ tiene el campo 'fecha_modificacion'</p>";
        echo "<p>El UPDATE debe incluir este campo.</p>";
    } else {
        echo "<p style='color: green; font-size: 18px;'>✓ La tabla NO tiene el campo 'fecha_modificacion'</p>";
        echo "<p>El UPDATE actual es correcto.</p>";
    }
    
    // Mostrar campos relacionados con contraseña
    echo "<hr>";
    echo "<h2>Campos relacionados con contraseña:</h2>";
    echo "<ul>";
    foreach ($columns as $col) {
        $field = strtolower($col['Field']);
        if (strpos($field, 'password') !== false || 
            strpos($field, 'fecha') !== false || 
            strpos($field, 'modificacion') !== false ||
            strpos($field, 'actualizacion') !== false) {
            echo "<li><strong>" . htmlspecialchars($col['Field']) . "</strong> - " . htmlspecialchars($col['Type']) . "</li>";
        }
    }
    echo "</ul>";
    
    echo "<hr>";
    echo "<p><a href='limpiar_cache.php'>Ir a Limpiar Caché</a> | <a href='dashboard.php'>Volver al Dashboard</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
