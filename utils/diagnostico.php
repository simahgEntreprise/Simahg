<?php
echo "<h1>✅ XAMPP funciona perfectamente</h1>";
echo "<p>Fecha y hora: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Probar CodeIgniter
echo "<h2>Probando CodeIgniter:</h2>";
echo "<p>Directorio: " . __DIR__ . "</p>";
echo "<p>index.php existe: " . (file_exists('index.php') ? '✅ SÍ' : '❌ NO') . "</p>";
echo "<p>system/ existe: " . (is_dir('system') ? '✅ SÍ' : '❌ NO') . "</p>";
echo "<p>application/ existe: " . (is_dir('application') ? '✅ SÍ' : '❌ NO') . "</p>";

// Probar conexión a base de datos
echo "<h2>Probando MySQL:</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;port=3307;dbname=simahg_db', 'root', '');
    echo "<p>Conexión a MySQL: ✅ EXITOSA</p>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
    $result = $stmt->fetch();
    echo "<p>Usuarios en la BD: " . $result['total'] . "</p>";
    
} catch (Exception $e) {
    echo "<p>Error en MySQL: ❌ " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><a href='index.php/login'>🔑 Ir al Login del Sistema</a></p>";
echo "<p><a href='index.php/test'>🧪 Ir al Test de CodeIgniter</a></p>";
?>
