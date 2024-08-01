<?php
/**
 * SCRIPT DE DIAGNÓSTICO PARA IDENTIFICAR EL ORIGEN DEL ERROR DE CÓDIGO DUPLICADO
 */

// Configuración de la base de datos
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>🔍 DIAGNÓSTICO DEL ERROR DE CÓDIGO DUPLICADO</h1>";
    echo "<hr>";
    
    // 1. Estado actual de la tabla
    echo "<h2>1️⃣ Estado actual de codigos_recuperacion</h2>";
    $stmt = $pdo->query("SELECT * FROM codigos_recuperacion ORDER BY fecha_creacion DESC LIMIT 10");
    $codigos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($codigos) > 0) {
        echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background: #667eea; color: white;'>";
        echo "<th>ID</th><th>Usuario ID</th><th>Código</th><th>Método</th><th>Usado</th><th>Expiración</th><th>Fecha Creación</th>";
        echo "</tr>";
        foreach ($codigos as $codigo) {
            $color = $codigo['usado'] == 0 ? '#fff3cd' : '#d1e7dd';
            echo "<tr style='background: $color;'>";
            echo "<td>{$codigo['id']}</td>";
            echo "<td>{$codigo['usuario_id']}</td>";
            echo "<td>{$codigo['codigo']}</td>";
            echo "<td>{$codigo['metodo']}</td>";
            echo "<td>" . ($codigo['usado'] ? 'Sí' : 'No') . "</td>";
            echo "<td>{$codigo['expiracion']}</td>";
            echo "<td>{$codigo['fecha_creacion']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='background: #d1e7dd; padding: 15px; border-radius: 5px;'>✅ No hay códigos de recuperación en la base de datos</p>";
    }
    
    // 2. Verificar duplicados
    echo "<h2>2️⃣ Verificar códigos duplicados (mismo usuario_id con usado=0)</h2>";
    $stmt = $pdo->query("
        SELECT usuario_id, COUNT(*) as cantidad, GROUP_CONCAT(id) as ids
        FROM codigos_recuperacion 
        WHERE usado = 0 
        GROUP BY usuario_id 
        HAVING cantidad > 1
    ");
    $duplicados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($duplicados) > 0) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "<strong>❌ SE DETECTARON CÓDIGOS DUPLICADOS:</strong><br><br>";
        foreach ($duplicados as $dup) {
            echo "- Usuario ID {$dup['usuario_id']}: {$dup['cantidad']} códigos no usados (IDs: {$dup['ids']})<br>";
        }
        echo "</div>";
    } else {
        echo "<p style='background: #d1e7dd; padding: 15px; border-radius: 5px;'>✅ No hay códigos duplicados</p>";
    }
    
    // 3. Mostrar la restricción UNIQUE
    echo "<h2>3️⃣ Restricción UNIQUE KEY de la tabla</h2>";
    $stmt = $pdo->query("SHOW CREATE TABLE codigos_recuperacion");
    $create = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<pre style='background: #f8f9fa; padding: 15px; border-radius: 5px; overflow-x: auto;'>";
    echo htmlspecialchars($create['Create Table']);
    echo "</pre>";
    
    // 4. Simular el error
    echo "<h2>4️⃣ Simulación del error (intentar insertar duplicado)</h2>";
    echo "<p>Voy a intentar crear un código duplicado para el usuario ID 1 y ver si se produce el error:</p>";
    
    try {
        // Primero, limpiar códigos del usuario 1
        $pdo->query("DELETE FROM codigos_recuperacion WHERE usuario_id = 1 AND usado = 0");
        echo "<p style='background: #cfe2ff; padding: 10px; border-radius: 5px;'>✓ Códigos del usuario 1 limpiados</p>";
        
        // Insertar primer código
        $stmt = $pdo->prepare("INSERT INTO codigos_recuperacion (usuario_id, codigo, metodo, expiracion, usado) VALUES (1, '111111', 'email', NOW() + INTERVAL 15 MINUTE, 0)");
        $stmt->execute();
        echo "<p style='background: #d1e7dd; padding: 10px; border-radius: 5px;'>✓ Primer código insertado correctamente</p>";
        
        // Intentar insertar segundo código (esto DEBE fallar)
        echo "<p>Ahora intentando insertar un SEGUNDO código con usado=0 para el mismo usuario...</p>";
        $stmt = $pdo->prepare("INSERT INTO codigos_recuperacion (usuario_id, codigo, metodo, expiracion, usado) VALUES (1, '222222', 'email', NOW() + INTERVAL 15 MINUTE, 0)");
        $stmt->execute();
        
        echo "<p style='background: #f8d7da; padding: 10px; border-radius: 5px;'>❌ ERROR: Se permitió insertar un segundo código (NO DEBERÍA PASAR)</p>";
        
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
            echo "<p style='background: #d1e7dd; padding: 10px; border-radius: 5px;'>✓ CORRECTO: La restricción UNIQUE bloqueó el duplicado</p>";
            echo "<p style='background: #fff3cd; padding: 10px; border-radius: 5px;'><strong>Error capturado:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        } else {
            echo "<p style='background: #f8d7da; padding: 10px; border-radius: 5px;'>❌ Error inesperado: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    // 5. Limpiar códigos de prueba
    echo "<h2>5️⃣ Limpieza de códigos de prueba</h2>";
    $pdo->query("DELETE FROM codigos_recuperacion WHERE usuario_id = 1 AND usado = 0");
    echo "<p style='background: #d1e7dd; padding: 15px; border-radius: 5px;'>✓ Códigos de prueba eliminados</p>";
    
    // 6. Recomendaciones
    echo "<hr>";
    echo "<h2>📋 RECOMENDACIONES</h2>";
    echo "<div style='background: #cfe2ff; padding: 20px; border-radius: 10px;'>";
    echo "<p><strong>Si estás viendo el error al cambiar la contraseña:</strong></p>";
    echo "<ol>";
    echo "<li>Verifica que estés usando el formulario correcto: <code>cambiar_password.php</code></li>";
    echo "<li>Asegúrate de que NO estés mezclando el flujo de recuperación de contraseña con el cambio de contraseña</li>";
    echo "<li>El archivo <code>cambiar_password_process.php</code> NO inserta en <code>codigos_recuperacion</code>, solo actualiza la contraseña</li>";
    echo "<li>Si el error persiste, ejecuta: <a href='limpiar_codigos_duplicados_final.php'>limpiar_codigos_duplicados_final.php</a></li>";
    echo "</ol>";
    echo "<p><strong>Archivos que SÍ insertan en codigos_recuperacion:</strong></p>";
    echo "<ul>";
    echo "<li><code>recuperar_password_process.php</code> - Solicitud inicial de recuperación</li>";
    echo "<li><code>solicitar_nuevo_codigo.php</code> - Solicitar nuevo código</li>";
    echo "</ul>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px;'>";
    echo "❌ <strong>Error de conexión:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='cambiar_password.php'>← Cambiar Contraseña</a> | <a href='recuperar_password.php'>Recuperar Contraseña</a></p>";
echo "<p><small>Diagnóstico ejecutado el: " . date('Y-m-d H:i:s') . "</small></p>";
?>
