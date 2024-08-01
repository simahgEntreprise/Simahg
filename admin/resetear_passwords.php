<?php
/**
 * RESETEAR CONTRASEÑAS DE USUARIOS
 * Cambia todas las contraseñas a: 123456
 */

$host = 'localhost';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🔄 Resetear Contraseñas - SIMAHG</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #667eea;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }
        .alert {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .alert-success {
            background: #d4edda;
            border-left: 5px solid #28a745;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border-left: 5px solid #dc3545;
            color: #721c24;
        }
        .alert-info {
            background: #d1ecf1;
            border-left: 5px solid #17a2b8;
            color: #0c5460;
        }
        .user-item {
            background: #f8f9fa;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-item.success {
            border-left: 5px solid #28a745;
            background: #d4edda;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1.1em;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .btn-danger {
            background: #dc3545;
        }
        code {
            background: #f4f4f4;
            padding: 4px 10px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            color: #e83e8c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Resetear Contraseñas</h1>

<?php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buscar tabla de usuarios
    $tables = $pdo->query("SHOW TABLES LIKE '%usuario%'")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<div class='alert alert-danger'>";
        echo "<h3>❌ Error</h3>";
        echo "<p>No se encontró la tabla de usuarios en la base de datos.</p>";
        echo "</div>";
        exit;
    }
    
    $userTable = $tables[0];
    
    // Verificar si se solicitó el reset
    if (isset($_GET['confirmar']) && $_GET['confirmar'] == 'si') {
        echo "<div class='alert alert-info'>";
        echo "<h3>🔄 Reseteando contraseñas...</h3>";
        echo "</div>";
        
        // Obtener todos los usuarios
        $stmt = $pdo->query("SELECT id, usuario, nombre FROM $userTable");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $nueva_password = sha1('123456'); // Hash SHA1 de "123456"
        $actualizados = 0;
        
        echo "<div style='margin: 20px 0;'>";
        
        foreach ($usuarios as $user) {
            try {
                $stmt = $pdo->prepare("UPDATE $userTable SET password = ? WHERE id = ?");
                $stmt->execute([$nueva_password, $user['id']]);
                
                echo "<div class='user-item success'>";
                echo "<div>";
                echo "<strong>✅ {$user['usuario']}</strong><br>";
                echo "<small>{$user['nombre']}</small>";
                echo "</div>";
                echo "<div>";
                echo "<code>123456</code>";
                echo "</div>";
                echo "</div>";
                
                $actualizados++;
            } catch (PDOException $e) {
                echo "<div class='user-item' style='border-left: 5px solid #dc3545; background: #f8d7da;'>";
                echo "<strong>❌ {$user['usuario']}</strong> - Error: " . $e->getMessage();
                echo "</div>";
            }
        }
        
        echo "</div>";
        
        echo "<div class='alert alert-success'>";
        echo "<h3>🎉 ¡Contraseñas Reseteadas!</h3>";
        echo "<p><strong>$actualizados usuarios actualizados</strong></p>";
        echo "<p>Nueva contraseña para TODOS los usuarios: <code>123456</code></p>";
        echo "</div>";
        
        echo "<div class='alert alert-info'>";
        echo "<h3>🧪 Ahora puedes probar:</h3>";
        echo "<ol style='margin-left: 20px; margin-top: 15px; line-height: 2;'>";
        echo "<li>Ve al <strong>Login</strong></li>";
        echo "<li>Usa cualquier usuario de arriba</li>";
        echo "<li>Contraseña: <code>123456</code></li>";
        echo "<li>¡Deberías poder entrar sin problemas! 🎉</li>";
        echo "</ol>";
        echo "</div>";
        
        echo "<div style='text-align: center; margin-top: 30px;'>";
        echo "<a href='login.php' class='btn'>🚀 Ir al Login</a>";
        echo "<a href='mis_usuarios.php' class='btn' style='background: #6c757d;'>👥 Ver Usuarios</a>";
        echo "</div>";
        
    } else {
        // Mostrar confirmación
        echo "<div class='alert alert-info'>";
        echo "<h3>⚠️ Confirmación Requerida</h3>";
        echo "<p>Este script va a cambiar la contraseña de <strong>TODOS los usuarios</strong> en la tabla <code>$userTable</code> a:</p>";
        echo "<div style='text-align: center; margin: 20px 0;'>";
        echo "<code style='font-size: 2em; padding: 20px;'>123456</code>";
        echo "</div>";
        echo "<p><strong>Hash SHA1:</strong> <code style='font-size: 0.9em;'>7c4a8d09ca3762af61e59520943dc26494f8941b</code></p>";
        echo "</div>";
        
        // Mostrar usuarios que serán afectados
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM $userTable");
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<div class='alert alert-info'>";
        echo "<h3>👥 Usuarios que serán afectados: {$total['total']}</h3>";
        
        $stmt = $pdo->query("SELECT usuario, nombre, rol FROM $userTable ORDER BY id");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div style='margin: 15px 0;'>";
        foreach ($usuarios as $user) {
            $rol = isset($user['rol']) ? $user['rol'] : 'Usuario';
            echo "<div class='user-item'>";
            echo "<div>";
            echo "<strong>{$user['usuario']}</strong><br>";
            echo "<small>{$user['nombre']} - $rol</small>";
            echo "</div>";
            echo "<div>";
            echo "<span style='color: #dc3545;'>➜</span> <code>123456</code>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
        echo "</div>";
        
        echo "<div style='text-align: center; margin-top: 40px;'>";
        echo "<a href='?confirmar=si' class='btn btn-danger' style='font-size: 1.3em; padding: 20px 50px;'>✅ CONFIRMAR RESETEO</a>";
        echo "</div>";
        
        echo "<div style='text-align: center; margin-top: 20px;'>";
        echo "<a href='mis_usuarios.php' class='btn' style='background: #6c757d;'>❌ Cancelar</a>";
        echo "</div>";
    }
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h3>❌ Error de Conexión</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<hr style='margin: 20px 0;'>";
    echo "<h4>🔧 Verifica que:</h4>";
    echo "<ol style='margin-left: 20px; margin-top: 10px;'>";
    echo "<li>XAMPP esté iniciado</li>";
    echo "<li>MySQL esté corriendo</li>";
    echo "<li>La base de datos <code>$dbname</code> exista</li>";
    echo "</ol>";
    echo "</div>";
}
?>

    </div>
</body>
</html>
