<?php
/**
 * LIMPIEZA MASIVA DE CÓDIGOS DE RECUPERACIÓN
 * Elimina códigos duplicados de todos los usuarios
 */

$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Limpieza de Códigos</title>";
echo "<style>
body { font-family: Arial; margin: 20px; background: #f4f6f9; }
.container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
h1 { color: #667eea; text-align: center; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
.info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
.btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
</style></head><body><div class='container'>";

echo "<h1>🧹 Limpieza de Códigos de Recuperación</h1>";
echo "<hr>";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Eliminar TODOS los códigos no usados
    echo "<h2>Paso 1: Eliminar códigos no usados</h2>";
    $stmt = $pdo->prepare("DELETE FROM codigos_recuperacion WHERE usado = 0");
    $stmt->execute();
    $eliminados = $stmt->rowCount();
    
    if ($eliminados > 0) {
        echo "<div class='success'>✓ Se eliminaron $eliminados códigos no usados</div>";
    } else {
        echo "<div class='info'>No había códigos no usados</div>";
    }
    
    // Eliminar códigos expirados
    echo "<h2>Paso 2: Eliminar códigos expirados</h2>";
    $stmt = $pdo->prepare("DELETE FROM codigos_recuperacion WHERE expiracion < NOW()");
    $stmt->execute();
    $expirados = $stmt->rowCount();
    
    if ($expirados > 0) {
        echo "<div class='success'>✓ Se eliminaron $expirados códigos expirados</div>";
    } else {
        echo "<div class='info'>No había códigos expirados</div>";
    }
    
    // Mostrar estado final
    echo "<h2>Paso 3: Estado final</h2>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM codigos_recuperacion");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<div class='success' style='text-align: center; font-size: 18px; padding: 30px;'>";
    echo "<strong>🎉 ¡LIMPIEZA COMPLETADA!</strong><br>";
    echo "Códigos restantes en el sistema: $total<br>";
    echo "Ahora puedes solicitar códigos de recuperación sin problemas.";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='error'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}

echo "<hr>";
echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='recuperar_password.php' class='btn'>🔐 Recuperar Contraseña</a> ";
echo "<a href='login.php' class='btn'>Ir a Login</a>";
echo "</div>";

echo "</div></body></html>";
?>
