<?php
session_start();

echo "<h1>🎉 Página de Éxito - Login Directo</h1>";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    echo "<div style='background: #d4edda; padding: 20px;'>";
    echo "<h2>✅ Sesión Activa</h2>";
    echo "<p>User ID: " . $_SESSION['user_id'] . "</p>";
    echo "<p>Username: " . $_SESSION['username'] . "</p>";
    echo "<p>Hora: " . date('Y-m-d H:i:s') . "</p>";
    echo "</div>";
    
    echo "<h3>¡Esto prueba que:</h3>";
    echo "<ul>";
    echo "<li>✅ La base de datos funciona</li>";
    echo "<li>✅ Las credenciales son correctas</li>";
    echo "<li>✅ PHP sessions funcionan</li>";
    echo "<li>✅ La redirección funciona</li>";
    echo "</ul>";
    
    echo "<p><strong>El problema está en CodeIgniter</strong>, no en tu servidor ni base de datos.</p>";
    
} else {
    echo "<p>❌ No hay sesión activa</p>";
}

echo "<hr>";
echo "<p><a href='test_login_directo.php'>⬅️ Volver al Test</a></p>";
echo "<p><a href='/simahg/index.php/login/simple'>🔑 Probar Login CodeIgniter</a></p>";
?>
