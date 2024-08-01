<?php
/**
 * CONSULTA REAL DE USUARIOS - SIMAHG_DB
 * Muestra exactamente qué usuarios existen en tu base de datos
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
    <title>👥 Mis Usuarios - SIMAHG</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
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
        .user-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        .user-card h2 {
            font-size: 2em;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .credential-box {
            background: rgba(255,255,255,0.15);
            padding: 20px;
            border-radius: 10px;
            margin: 15px 0;
            backdrop-filter: blur(10px);
        }
        .credential-box strong {
            display: block;
            margin-bottom: 10px;
            font-size: 0.9em;
            opacity: 0.9;
        }
        .credential-value {
            background: rgba(0,0,0,0.3);
            padding: 15px 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 1.5em;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .copy-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8em;
        }
        .copy-btn:hover {
            background: #f0f0f0;
        }
        .badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }
        .test-btn {
            background: white;
            color: #667eea;
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
        .test-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }
        .alert {
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .alert-danger {
            background: #f8d7da;
            border-left: 5px solid #dc3545;
            color: #721c24;
        }
        .alert-warning {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            color: #856404;
        }
        .alert-info {
            background: #d1ecf1;
            border-left: 5px solid #17a2b8;
            color: #0c5460;
        }
        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 20px;
            border-radius: 10px;
            overflow-x: auto;
            margin: 15px 0;
        }
    </style>
    <script>
        function copiarTexto(texto, btn) {
            navigator.clipboard.writeText(texto).then(function() {
                var originalText = btn.innerHTML;
                btn.innerHTML = '✓ Copiado';
                btn.style.background = '#28a745';
                btn.style.color = 'white';
                setTimeout(function() {
                    btn.innerHTML = originalText;
                    btn.style.background = 'white';
                    btn.style.color = '#667eea';
                }, 2000);
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>👥 Usuarios de SIMAHG</h1>

<?php
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Verificar qué tabla existe
    $tables = $pdo->query("SHOW TABLES LIKE '%usuario%'")->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "<div class='alert alert-danger'>";
        echo "<h3>❌ No se encontró tabla de usuarios</h3>";
        echo "<p>No existe ninguna tabla llamada 'usuario' o 'usuarios' en la base de datos <strong>$dbname</strong></p>";
        echo "</div>";
        exit;
    }
    
    $userTable = $tables[0]; // Usar la primera tabla que encuentre
    
    // Consultar usuarios
    $stmt = $pdo->query("SELECT * FROM $userTable ORDER BY id LIMIT 20");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($usuarios)) {
        echo "<div class='alert alert-warning'>";
        echo "<h3>⚠️ La tabla <code>$userTable</code> está vacía</h3>";
        echo "<p>No hay usuarios en la base de datos. Necesitas crear usuarios primero.</p>";
        echo "<h4>💡 Solución: Ejecuta este SQL en phpMyAdmin</h4>";
        echo "<pre>";
        echo "-- Ir a phpMyAdmin → Base de datos: $dbname → SQL\n";
        echo "-- Copiar y pegar este código:\n\n";
        echo "INSERT INTO $userTable (usuario, password, nombre, apellidos, email, rol, activo) VALUES\n";
        echo "('admin', SHA1('123456'), 'Administrador', 'Sistema', 'admin@simahg.com', 'Administrador', 1),\n";
        echo "('supervisor1', SHA1('123456'), 'Juan Carlos', 'Pérez', 'supervisor@simahg.com', 'Supervisor', 1),\n";
        echo "('almacenero1', SHA1('123456'), 'Pedro', 'López', 'almacen@simahg.com', 'Almacenero', 1),\n";
        echo "('trabajador1', SHA1('123456'), 'Carlos', 'Ramírez', 'trabajador1@simahg.com', 'Trabajador', 1),\n";
        echo "('trabajador2', SHA1('123456'), 'María', 'González', 'trabajador2@simahg.com', 'Trabajador', 1);";
        echo "</pre>";
        echo "<div style='text-align: center;'>";
        echo "<a href='http://localhost/phpmyadmin' target='_blank' class='test-btn'>🚀 Abrir phpMyAdmin</a>";
        echo "</div>";
        echo "</div>";
        exit;
    }
    
    // Mostrar usuarios encontrados
    echo "<div class='alert alert-info'>";
    echo "<strong>✅ Se encontraron " . count($usuarios) . " usuarios en la tabla <code>$userTable</code></strong>";
    echo "</div>";
    
    // Emojis por rol
    $roleEmojis = [
        'Administrador' => '👑',
        'Supervisor' => '👨‍💼',
        'Almacenero' => '📦',
        'Trabajador' => '👷'
    ];
    
    foreach ($usuarios as $user) {
        $rol = isset($user['rol']) ? $user['rol'] : 'Usuario';
        $emoji = isset($roleEmojis[$rol]) ? $roleEmojis[$rol] : '👤';
        $nombre = (isset($user['nombre']) ? $user['nombre'] : 'N/A') . ' ' . (isset($user['apellidos']) ? $user['apellidos'] : '');
        $usuario = isset($user['usuario']) ? $user['usuario'] : 'N/A';
        $email = isset($user['email']) ? $user['email'] : 'N/A';
        $estado = isset($user['activo']) ? ($user['activo'] ? '✅ Activo' : '❌ Inactivo') : 'N/A';
        
        echo "<div class='user-card'>";
        echo "<h2>$emoji $nombre <span class='badge' style='background: rgba(255,255,255,0.3);'>$rol</span></h2>";
        
        echo "<div class='credential-box'>";
        echo "<strong>👤 USUARIO</strong>";
        echo "<div class='credential-value'>";
        echo "<span>$usuario</span>";
        echo "<button class='copy-btn' onclick=\"copiarTexto('$usuario', this)\">📋 Copiar</button>";
        echo "</div>";
        echo "</div>";
        
        echo "<div class='credential-box'>";
        echo "<strong>🔑 CONTRASEÑA</strong>";
        echo "<div class='credential-value'>";
        echo "<span>123456</span>";
        echo "<button class='copy-btn' onclick=\"copiarTexto('123456', this)\">📋 Copiar</button>";
        echo "</div>";
        echo "<small style='opacity: 0.8; display: block; margin-top: 10px;'>* Contraseña por defecto. Si ya la cambiaste, usa la nueva.</small>";
        echo "</div>";
        
        echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;'>";
        echo "<div style='background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px;'>";
        echo "<strong style='opacity: 0.8; font-size: 0.9em;'>📧 Email</strong><br>";
        echo "<span style='font-size: 1.1em;'>$email</span>";
        echo "</div>";
        echo "<div style='background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px;'>";
        echo "<strong style='opacity: 0.8; font-size: 0.9em;'>📊 Estado</strong><br>";
        echo "<span style='font-size: 1.1em;'>$activo</span>";
        echo "</div>";
        echo "</div>";
        
        echo "</div>";
    }
    
    echo "<div class='alert alert-info'>";
    echo "<h3>🧪 Cómo Probar</h3>";
    echo "<ol style='margin-left: 20px; margin-top: 15px; line-height: 1.8;'>";
    echo "<li><strong>Login:</strong> Copia cualquier usuario y contraseña de arriba</li>";
    echo "<li><strong>Ve al login:</strong> Click en el botón de abajo</li>";
    echo "<li><strong>Pega las credenciales:</strong> Usuario y contraseña</li>";
    echo "<li><strong>Prueba cambiar contraseña:</strong> Una vez dentro, ve al menú de usuario → 🔑 Cambiar Contraseña</li>";
    echo "</ol>";
    echo "</div>";
    
    echo "<div style='text-align: center; margin-top: 40px;'>";
    echo "<a href='login.php' class='test-btn' style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-size: 1.3em; padding: 20px 50px;'>🚀 IR AL LOGIN</a>";
    echo "</div>";
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>";
    echo "<h3>❌ Error de Conexión</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<hr style='margin: 20px 0;'>";
    echo "<h4>🔧 Verifica que:</h4>";
    echo "<ol style='margin-left: 20px; margin-top: 10px;'>";
    echo "<li>XAMPP esté iniciado (Apache y MySQL en verde)</li>";
    echo "<li>La base de datos <code>$dbname</code> exista en phpMyAdmin</li>";
    echo "<li>Las credenciales sean correctas (usuario: <code>$username</code>, password: <code>" . (empty($password) ? '(vacía)' : '***') . "</code>)</li>";
    echo "</ol>";
    echo "<div style='text-align: center; margin-top: 20px;'>";
    echo "<a href='http://localhost/phpmyadmin' target='_blank' class='test-btn'>🚀 Abrir phpMyAdmin</a>";
    echo "</div>";
    echo "</div>";
}
?>

    </div>
</body>
</html>
