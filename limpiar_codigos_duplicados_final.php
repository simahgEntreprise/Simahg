<?php
/**
 * Script de limpieza final de códigos duplicados
 * Este script elimina TODOS los códigos no usados para prevenir errores de UNIQUE KEY
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
    
    echo "<h2>🧹 LIMPIEZA FINAL DE CÓDIGOS DE RECUPERACIÓN</h2>";
    echo "<hr>";
    
    // 1. Mostrar estado actual
    echo "<h3>📊 Estado ANTES de la limpieza:</h3>";
    $sql = "SELECT COUNT(*) as total, 
                   SUM(CASE WHEN usado = 0 THEN 1 ELSE 0 END) as no_usados,
                   SUM(CASE WHEN usado = 1 THEN 1 ELSE 0 END) as usados,
                   SUM(CASE WHEN usado = 0 AND expiracion < NOW() THEN 1 ELSE 0 END) as expirados_no_usados,
                   SUM(CASE WHEN usado = 0 AND expiracion >= NOW() THEN 1 ELSE 0 END) as activos_no_usados
            FROM codigos_recuperacion";
    $result = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    
    echo "<ul>";
    echo "<li>Total de códigos: <strong>{$result['total']}</strong></li>";
    echo "<li>Códigos no usados: <strong>{$result['no_usados']}</strong></li>";
    echo "<li>Códigos usados: <strong>{$result['usados']}</strong></li>";
    echo "<li>Códigos expirados no usados: <strong>{$result['expirados_no_usados']}</strong></li>";
    echo "<li>Códigos activos no usados: <strong>{$result['activos_no_usados']}</strong></li>";
    echo "</ul>";
    
    // 2. Detectar usuarios con múltiples códigos no usados (problema de UNIQUE KEY)
    echo "<h3>⚠️ Usuarios con múltiples códigos no usados (problema UNIQUE KEY):</h3>";
    $sql = "SELECT usuario_id, COUNT(*) as cantidad 
            FROM codigos_recuperacion 
            WHERE usado = 0 
            GROUP BY usuario_id 
            HAVING cantidad > 1";
    $duplicados = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($duplicados) > 0) {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<strong>⚠️ PROBLEMA DETECTADO:</strong> Hay " . count($duplicados) . " usuario(s) con códigos duplicados no usados:<br>";
        foreach ($duplicados as $dup) {
            echo "- Usuario ID {$dup['usuario_id']}: {$dup['cantidad']} códigos no usados<br>";
        }
        echo "</div>";
    } else {
        echo "<div style='background: #d1e7dd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ No se detectaron códigos duplicados no usados";
        echo "</div>";
    }
    
    // 3. Limpiar TODOS los códigos no usados (tanto activos como expirados)
    echo "<h3>🗑️ Limpiando códigos no usados...</h3>";
    
    $pdo->beginTransaction();
    
    try {
        // Eliminar TODOS los códigos con usado = 0
        $sql_delete = "DELETE FROM codigos_recuperacion WHERE usado = 0";
        $stmt = $pdo->prepare($sql_delete);
        $stmt->execute();
        $eliminados = $stmt->rowCount();
        
        $pdo->commit();
        
        echo "<div style='background: #d1e7dd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>Limpieza completada:</strong> Se eliminaron {$eliminados} códigos no usados";
        echo "</div>";
        
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>Error al limpiar:</strong> " . $e->getMessage();
        echo "</div>";
    }
    
    // 4. Mostrar estado final
    echo "<h3>📊 Estado DESPUÉS de la limpieza:</h3>";
    $result = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    
    echo "<ul>";
    echo "<li>Total de códigos: <strong>{$result['total']}</strong></li>";
    echo "<li>Códigos no usados: <strong>{$result['no_usados']}</strong></li>";
    echo "<li>Códigos usados: <strong>{$result['usados']}</strong></li>";
    echo "</ul>";
    
    // 5. Verificar que no hay duplicados
    echo "<h3>🔍 Verificación final:</h3>";
    $duplicados = $pdo->query("SELECT usuario_id, COUNT(*) as cantidad 
                               FROM codigos_recuperacion 
                               WHERE usado = 0 
                               GROUP BY usuario_id 
                               HAVING cantidad > 1")->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($duplicados) > 0) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "❌ <strong>AÚN HAY PROBLEMAS:</strong> Hay " . count($duplicados) . " usuario(s) con códigos duplicados";
        echo "</div>";
    } else {
        echo "<div style='background: #d1e7dd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "✅ <strong>SISTEMA LIMPIO:</strong> No hay códigos duplicados. El sistema está listo para funcionar correctamente.";
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<h3>📝 Recomendaciones:</h3>";
    echo "<ol>";
    echo "<li>Los usuarios ahora pueden solicitar nuevos códigos sin problemas</li>";
    echo "<li>El sistema eliminará automáticamente códigos antiguos antes de crear nuevos</li>";
    echo "<li>Las transacciones garantizan que no habrá conflictos de UNIQUE KEY</li>";
    echo "<li>Si el problema persiste, verifica que no haya múltiples peticiones simultáneas del mismo usuario</li>";
    echo "</ol>";
    
    echo "<div style='background: #cfe2ff; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<strong>ℹ️ NOTA:</strong> Este script se puede ejecutar de forma segura en cualquier momento para limpiar códigos no usados.";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; margin: 10px 0;'>";
    echo "❌ <strong>Error de conexión:</strong> " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='recuperar_password.php'>← Volver a Recuperar Contraseña</a></p>";
echo "<p><small>Script ejecutado el: " . date('Y-m-d H:i:s') . "</small></p>";
?>
