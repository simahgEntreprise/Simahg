<?php
/**
 * CORRECCIÓN DE CÓDIGOS DE RECUPERACIÓN DUPLICADOS
 */

$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Corrección Códigos Recuperación</title>";
echo "<style>
body { font-family: Arial; margin: 20px; background: #f4f6f9; }
.container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
h1 { color: #667eea; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
.error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
.warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
.info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
.btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
th { background: #667eea; color: white; }
</style></head><body><div class='container'>";

echo "<h1>🔧 Corrección de Códigos de Recuperación</h1>";
echo "<hr>";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Paso 1: Ver códigos actuales
    echo "<h2>📋 Paso 1: Códigos actuales en la base de datos</h2>";
    $stmt = $pdo->query("SELECT * FROM codigos_recuperacion ORDER BY fecha_creacion DESC");
    $codigos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($codigos) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Usuario ID</th><th>Código</th><th>Método</th><th>Usado</th><th>Fecha Creación</th></tr>";
        foreach ($codigos as $cod) {
            $usado_text = $cod['usado'] == 0 ? '❌ No usado' : '✅ Usado';
            echo "<tr>";
            echo "<td>{$cod['id']}</td>";
            echo "<td>{$cod['usuario_id']}</td>";
            echo "<td>{$cod['codigo']}</td>";
            echo "<td>{$cod['metodo']}</td>";
            echo "<td>$usado_text</td>";
            echo "<td>{$cod['fecha_creacion']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<div class='info'>Total de códigos: " . count($codigos) . "</div>";
    } else {
        echo "<div class='info'>No hay códigos en la base de datos</div>";
    }
    
    // Paso 2: Detectar duplicados
    echo "<h2>🔍 Paso 2: Detectar códigos duplicados (usuario_id + usado = 0)</h2>";
    $stmt = $pdo->query("
        SELECT usuario_id, COUNT(*) as total 
        FROM codigos_recuperacion 
        WHERE usado = 0 
        GROUP BY usuario_id 
        HAVING COUNT(*) > 1
    ");
    $duplicados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($duplicados) > 0) {
        echo "<div class='warning'>⚠️ Se encontraron " . count($duplicados) . " usuarios con códigos duplicados</div>";
        echo "<table>";
        echo "<tr><th>Usuario ID</th><th>Códigos No Usados</th></tr>";
        foreach ($duplicados as $dup) {
            echo "<tr>";
            echo "<td>{$dup['usuario_id']}</td>";
            echo "<td>{$dup['total']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='success'>✓ No se encontraron duplicados</div>";
    }
    
    // Paso 3: Limpiar códigos antiguos no usados (dejar solo el más reciente)
    echo "<h2>🧹 Paso 3: Limpiar códigos antiguos no usados</h2>";
    
    $stmt = $pdo->query("SELECT DISTINCT usuario_id FROM codigos_recuperacion WHERE usado = 0");
    $usuarios_con_codigos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $eliminados = 0;
    foreach ($usuarios_con_codigos as $uid) {
        // Obtener todos los códigos no usados del usuario
        $stmt = $pdo->prepare("SELECT id FROM codigos_recuperacion WHERE usuario_id = ? AND usado = 0 ORDER BY fecha_creacion DESC");
        $stmt->execute([$uid]);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Mantener solo el más reciente (el primero), eliminar los demás
        if (count($ids) > 1) {
            $mantener = array_shift($ids); // Sacar el primero (más reciente)
            
            // Eliminar los demás
            foreach ($ids as $id_eliminar) {
                $stmt_del = $pdo->prepare("DELETE FROM codigos_recuperacion WHERE id = ?");
                $stmt_del->execute([$id_eliminar]);
                $eliminados++;
            }
        }
    }
    
    if ($eliminados > 0) {
        echo "<div class='success'>✓ Se eliminaron $eliminados códigos antiguos duplicados</div>";
    } else {
        echo "<div class='info'>No había códigos duplicados que eliminar</div>";
    }
    
    // Paso 4: Verificar códigos después de la limpieza
    echo "<h2>✅ Paso 4: Códigos después de la limpieza</h2>";
    $stmt = $pdo->query("SELECT * FROM codigos_recuperacion ORDER BY fecha_creacion DESC");
    $codigos_final = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($codigos_final) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Usuario ID</th><th>Código</th><th>Método</th><th>Usado</th><th>Fecha Creación</th></tr>";
        foreach ($codigos_final as $cod) {
            $usado_text = $cod['usado'] == 0 ? '❌ No usado' : '✅ Usado';
            echo "<tr>";
            echo "<td>{$cod['id']}</td>";
            echo "<td>{$cod['usuario_id']}</td>";
            echo "<td>{$cod['codigo']}</td>";
            echo "<td>{$cod['metodo']}</td>";
            echo "<td>$usado_text</td>";
            echo "<td>{$cod['fecha_creacion']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<div class='success'>✓ Total de códigos: " . count($codigos_final) . "</div>";
    } else {
        echo "<div class='info'>No hay códigos en la base de datos</div>";
    }
    
    // Verificar que no haya duplicados
    $stmt = $pdo->query("
        SELECT usuario_id, COUNT(*) as total 
        FROM codigos_recuperacion 
        WHERE usado = 0 
        GROUP BY usuario_id 
        HAVING COUNT(*) > 1
    ");
    $duplicados_final = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($duplicados_final) == 0) {
        echo "<div class='success' style='text-align: center; font-size: 18px; padding: 30px;'>";
        echo "<strong>🎉 ¡PROBLEMA RESUELTO!</strong><br>";
        echo "Ya no hay códigos duplicados. La recuperación de contraseña funcionará correctamente.";
        echo "</div>";
    } else {
        echo "<div class='error'>⚠️ Aún hay duplicados. Ejecuta este script de nuevo.</div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<hr>";
echo "<h2>📝 Explicación del Problema</h2>";
echo "<div class='info'>";
echo "<p><strong>Problema:</strong> La tabla <code>codigos_recuperacion</code> tiene una restricción UNIQUE sobre <code>(usuario_id, usado)</code>.</p>";
echo "<p>Esto significa que un usuario solo puede tener UN código con <code>usado = 0</code> al mismo tiempo.</p>";
echo "<p><strong>Solución aplicada:</strong></p>";
echo "<ol>";
echo "<li>Se eliminaron todos los códigos antiguos no usados antes de crear uno nuevo</li>";
echo "<li>Se actualizó <code>recuperar_password_process.php</code> para eliminar códigos antiguos antes de insertar</li>";
echo "<li>Ahora cada usuario solo tendrá un código activo a la vez</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='recuperar_password.php' class='btn'>🔐 Probar Recuperación</a> ";
echo "<a href='login.php' class='btn'>Ir a Login</a> ";
echo "<a href='prueba_crud_completa.php' class='btn'>Probar CRUD</a>";
echo "</div>";

echo "</div></body></html>";
?>
