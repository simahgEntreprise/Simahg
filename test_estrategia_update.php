<?php
/**
 * PRUEBA DE LA NUEVA ESTRATEGIA: UPDATE en lugar de DELETE+INSERT
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
    
    echo "<h1>🧪 PRUEBA DE ESTRATEGIA UPDATE vs DELETE+INSERT</h1>";
    echo "<hr>";
    
    $usuario_id = 1; // Usuario de prueba
    
    // 1. Limpiar estado inicial
    echo "<h2>1️⃣ Limpieza inicial</h2>";
    $pdo->query("DELETE FROM codigos_recuperacion WHERE usuario_id = $usuario_id AND usado = 0");
    echo "<p style='background: #d1e7dd; padding: 10px; border-radius: 5px;'>✓ Códigos del usuario $usuario_id limpiados</p>";
    
    // 2. Primera solicitud (INSERT)
    echo "<h2>2️⃣ Primera solicitud de código (debería INSERTAR)</h2>";
    
    $codigo1 = sprintf('%06d', mt_rand(0, 999999));
    $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    $metodo = 'email';
    
    $pdo->beginTransaction();
    try {
        $sql_update = "UPDATE codigos_recuperacion 
                       SET codigo = ?, metodo = ?, expiracion = ?, fecha_creacion = CURRENT_TIMESTAMP 
                       WHERE usuario_id = ? AND usado = 0";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$codigo1, $metodo, $expiracion, $usuario_id]);
        
        if ($stmt_update->rowCount() == 0) {
            $sql_insert = "INSERT INTO codigos_recuperacion (usuario_id, codigo, metodo, expiracion, usado) 
                           VALUES (?, ?, ?, ?, 0)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$usuario_id, $codigo1, $metodo, $expiracion]);
            echo "<p style='background: #d1e7dd; padding: 10px; border-radius: 5px;'>✓ INSERTADO nuevo código: <strong>$codigo1</strong></p>";
        } else {
            echo "<p style='background: #cfe2ff; padding: 10px; border-radius: 5px;'>↻ ACTUALIZADO código existente a: <strong>$codigo1</strong></p>";
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='background: #f8d7da; padding: 10px; border-radius: 5px;'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
    // 3. Segunda solicitud sin usar el primero (UPDATE)
    echo "<h2>3️⃣ Segunda solicitud SIN USAR el código anterior (debería ACTUALIZAR)</h2>";
    
    $codigo2 = sprintf('%06d', mt_rand(0, 999999));
    $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    $pdo->beginTransaction();
    try {
        $sql_update = "UPDATE codigos_recuperacion 
                       SET codigo = ?, metodo = ?, expiracion = ?, fecha_creacion = CURRENT_TIMESTAMP 
                       WHERE usuario_id = ? AND usado = 0";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$codigo2, $metodo, $expiracion, $usuario_id]);
        
        if ($stmt_update->rowCount() == 0) {
            $sql_insert = "INSERT INTO codigos_recuperacion (usuario_id, codigo, metodo, expiracion, usado) 
                           VALUES (?, ?, ?, ?, 0)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$usuario_id, $codigo2, $metodo, $expiracion]);
            echo "<p style='background: #d1e7dd; padding: 10px; border-radius: 5px;'>✓ INSERTADO nuevo código: <strong>$codigo2</strong></p>";
        } else {
            echo "<p style='background: #cfe2ff; padding: 10px; border-radius: 5px;'>↻ ACTUALIZADO código existente de <strong>$codigo1</strong> a: <strong>$codigo2</strong></p>";
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='background: #f8d7da; padding: 10px; border-radius: 5px;'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
    // 4. Tercera solicitud (UPDATE)
    echo "<h2>4️⃣ Tercera solicitud consecutiva (debería ACTUALIZAR nuevamente)</h2>";
    
    $codigo3 = sprintf('%06d', mt_rand(0, 999999));
    $expiracion = date('Y-m-d H:i:s', strtotime('+15 minutes'));
    
    $pdo->beginTransaction();
    try {
        $sql_update = "UPDATE codigos_recuperacion 
                       SET codigo = ?, metodo = ?, expiracion = ?, fecha_creacion = CURRENT_TIMESTAMP 
                       WHERE usuario_id = ? AND usado = 0";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([$codigo3, $metodo, $expiracion, $usuario_id]);
        
        if ($stmt_update->rowCount() == 0) {
            $sql_insert = "INSERT INTO codigos_recuperacion (usuario_id, codigo, metodo, expiracion, usado) 
                           VALUES (?, ?, ?, ?, 0)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$usuario_id, $codigo3, $metodo, $expiracion]);
            echo "<p style='background: #d1e7dd; padding: 10px; border-radius: 5px;'>✓ INSERTADO nuevo código: <strong>$codigo3</strong></p>";
        } else {
            echo "<p style='background: #cfe2ff; padding: 10px; border-radius: 5px;'>↻ ACTUALIZADO código existente de <strong>$codigo2</strong> a: <strong>$codigo3</strong></p>";
        }
        
        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='background: #f8d7da; padding: 10px; border-radius: 5px;'>❌ Error: " . $e->getMessage() . "</p>";
    }
    
    // 5. Verificar estado final
    echo "<h2>5️⃣ Estado final de la tabla</h2>";
    $stmt = $pdo->query("SELECT * FROM codigos_recuperacion WHERE usuario_id = $usuario_id ORDER BY fecha_creacion DESC");
    $codigos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #667eea; color: white;'>";
    echo "<th>ID</th><th>Código</th><th>Usado</th><th>Expiración</th><th>Fecha Creación</th>";
    echo "</tr>";
    foreach ($codigos as $codigo) {
        $color = $codigo['usado'] == 0 ? '#fff3cd' : '#d1e7dd';
        echo "<tr style='background: $color;'>";
        echo "<td>{$codigo['id']}</td>";
        echo "<td><strong>{$codigo['codigo']}</strong></td>";
        echo "<td>" . ($codigo['usado'] ? 'Sí' : 'No') . "</td>";
        echo "<td>{$codigo['expiracion']}</td>";
        echo "<td>{$codigo['fecha_creacion']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 6. Contar códigos no usados
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM codigos_recuperacion WHERE usuario_id = $usuario_id AND usado = 0");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<h2>6️⃣ Verificación de restricción UNIQUE</h2>";
    if ($count == 1) {
        echo "<div style='background: #d1e7dd; padding: 20px; border-radius: 10px; border: 3px solid #0f5132;'>";
        echo "<h3 style='color: #0f5132; margin: 0;'>✅ ¡PERFECTO! Solo hay 1 código no usado</h3>";
        echo "<p>La estrategia UPDATE funciona correctamente y respeta la restricción UNIQUE.</p>";
        echo "<p><strong>Beneficios:</strong></p>";
        echo "<ul>";
        echo "<li>✅ No hay error de código duplicado</li>";
        echo "<li>✅ El código anterior se reemplaza automáticamente</li>";
        echo "<li>✅ No se necesita DELETE, solo UPDATE o INSERT</li>";
        echo "<li>✅ Más eficiente y seguro</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px;'>";
        echo "<h3>❌ Problema: Hay $count códigos no usados (debería ser 1)</h3>";
        echo "</div>";
    }
    
    // 7. Limpiar códigos de prueba
    echo "<h2>7️⃣ Limpieza de códigos de prueba</h2>";
    $pdo->query("DELETE FROM codigos_recuperacion WHERE usuario_id = $usuario_id AND usado = 0");
    echo "<p style='background: #d1e7dd; padding: 10px; border-radius: 5px;'>✓ Códigos de prueba eliminados</p>";
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px;'>";
    echo "❌ <strong>Error de conexión:</strong> " . htmlspecialchars($e->getMessage());
    echo "</div>";
}

echo "<hr>";
echo "<h2>📋 CONCLUSIÓN</h2>";
echo "<div style='background: #e7f3ff; padding: 20px; border-radius: 10px;'>";
echo "<p><strong>La nueva estrategia funciona así:</strong></p>";
echo "<ol>";
echo "<li><strong>Primera solicitud:</strong> No hay código previo → Se INSERTA uno nuevo</li>";
echo "<li><strong>Solicitudes siguientes:</strong> Ya hay un código no usado → Se ACTUALIZA con el nuevo código</li>";
echo "<li><strong>Resultado:</strong> Siempre hay máximo 1 código no usado por usuario</li>";
echo "<li><strong>Error eliminado:</strong> Nunca se intenta insertar un duplicado</li>";
echo "</ol>";
echo "<p><strong>🎉 Ahora puedes solicitar códigos de recuperación cuantas veces quieras sin errores.</strong></p>";
echo "</div>";

echo "<hr>";
echo "<p><a href='recuperar_password.php'>← Probar Recuperar Contraseña</a></p>";
echo "<p><small>Prueba ejecutada el: " . date('Y-m-d H:i:s') . "</small></p>";
?>
