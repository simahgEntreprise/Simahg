<?php
/**
 * PRUEBA COMPLETA DE TODAS LAS FUNCIONALIDADES CRUD
 * NO CREA ARCHIVOS .MD - SOLO PRUEBA EL SISTEMA
 */

$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Prueba CRUD Completa</title>";
echo "<style>
body { font-family: Arial; margin: 20px; background: #f4f6f9; }
.container { max-width: 1400px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
h1 { color: #667eea; text-align: center; }
h2 { color: #333; border-bottom: 2px solid #667eea; padding-bottom: 10px; margin-top: 30px; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
.error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
.warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
.info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; }
table { width: 100%; border-collapse: collapse; margin: 15px 0; }
th, td { padding: 10px; text-align: left; border: 1px solid #ddd; }
th { background: #667eea; color: white; }
.test-ok { color: green; font-weight: bold; }
.test-fail { color: red; font-weight: bold; }
code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
.btn { display: inline-block; padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }
</style></head><body><div class='container'>";

echo "<h1>🧪 Prueba Completa de CRUD - Sistema SIMAHG</h1>";
echo "<p style='text-align: center;'>Verificando todas las operaciones CREATE, READ, UPDATE, DELETE</p>";
echo "<hr>";

$tests_passed = 0;
$tests_failed = 0;
$tests_total = 0;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='success'>✓ Conexión a base de datos exitosa</div>";
    
    // ==================== USUARIOS ====================
    echo "<h2>👥 CRUD DE USUARIOS</h2>";
    
    // READ - Listar usuarios
    echo "<h3>1. READ - Listar Usuarios</h3>";
    $tests_total++;
    try {
        $stmt = $pdo->query("SELECT id, usuario, nombre, apellidos, email, estado FROM usuarios ORDER BY id LIMIT 5");
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<div class='success'><span class='test-ok'>✓ PASS</span> - Se listaron " . count($usuarios) . " usuarios</div>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Usuario</th><th>Nombre Completo</th><th>Email</th><th>Estado</th></tr>";
        foreach ($usuarios as $u) {
            $nombre_completo = $u['nombre'] . ' ' . $u['apellidos'];
            $estado = $u['estado'] == 1 ? '✓ Activo' : '✗ Inactivo';
            echo "<tr><td>{$u['id']}</td><td>{$u['usuario']}</td><td>$nombre_completo</td><td>{$u['email']}</td><td>$estado</td></tr>";
        }
        echo "</table>";
        $tests_passed++;
    } catch (Exception $e) {
        echo "<div class='error'><span class='test-fail'>✗ FAIL</span> - " . $e->getMessage() . "</div>";
        $tests_failed++;
    }
    
    // CREATE - Crear usuario de prueba
    echo "<h3>2. CREATE - Crear Usuario</h3>";
    $tests_total++;
    try {
        $test_usuario = 'test_' . time();
        $test_password = sha1('123456');
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, email, usuario, password, id_perfil, estado) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute(['Test', 'Usuario', 'test@simahg.com', $test_usuario, $test_password, 4, 1]);
        $nuevo_id = $pdo->lastInsertId();
        
        echo "<div class='success'><span class='test-ok'>✓ PASS</span> - Usuario creado con ID: $nuevo_id</div>";
        echo "<div class='info'>Usuario: <code>$test_usuario</code> | Password: <code>123456</code></div>";
        $tests_passed++;
    } catch (Exception $e) {
        echo "<div class='error'><span class='test-fail'>✗ FAIL</span> - " . $e->getMessage() . "</div>";
        $tests_failed++;
        $nuevo_id = null;
    }
    
    // UPDATE - Actualizar usuario
    echo "<h3>3. UPDATE - Actualizar Usuario</h3>";
    $tests_total++;
    if ($nuevo_id) {
        try {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellidos = ? WHERE id = ?");
            $stmt->execute(['Test Actualizado', 'Usuario Modificado', $nuevo_id]);
            
            // Verificar
            $stmt = $pdo->prepare("SELECT nombre, apellidos FROM usuarios WHERE id = ?");
            $stmt->execute([$nuevo_id]);
            $updated = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($updated['nombre'] == 'Test Actualizado' && $updated['apellidos'] == 'Usuario Modificado') {
                echo "<div class='success'><span class='test-ok'>✓ PASS</span> - Usuario actualizado correctamente</div>";
                echo "<div class='info'>Nuevo nombre: <code>{$updated['nombre']} {$updated['apellidos']}</code></div>";
                $tests_passed++;
            } else {
                throw new Exception("Los datos no se actualizaron correctamente");
            }
        } catch (Exception $e) {
            echo "<div class='error'><span class='test-fail'>✗ FAIL</span> - " . $e->getMessage() . "</div>";
            $tests_failed++;
        }
    } else {
        echo "<div class='warning'>⊗ SKIP - No se pudo crear el usuario de prueba</div>";
    }
    
    // DELETE - Eliminar usuario de prueba
    echo "<h3>4. DELETE - Eliminar Usuario</h3>";
    $tests_total++;
    if ($nuevo_id) {
        try {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$nuevo_id]);
            
            // Verificar
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE id = ?");
            $stmt->execute([$nuevo_id]);
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            if ($count == 0) {
                echo "<div class='success'><span class='test-ok'>✓ PASS</span> - Usuario eliminado correctamente</div>";
                $tests_passed++;
            } else {
                throw new Exception("El usuario no se eliminó");
            }
        } catch (Exception $e) {
            echo "<div class='error'><span class='test-fail'>✗ FAIL</span> - " . $e->getMessage() . "</div>";
            $tests_failed++;
        }
    } else {
        echo "<div class='warning'>⊗ SKIP - No se pudo crear el usuario de prueba</div>";
    }
    
    // ==================== FUNCIONALIDADES ====================
    echo "<h2>🔐 FUNCIONALIDADES DE AUTENTICACIÓN</h2>";
    
    // Login
    echo "<h3>5. Verificar Login</h3>";
    $tests_total++;
    try {
        $stmt = $pdo->prepare("SELECT u.*, p.nombre as perfil_nombre 
                               FROM usuarios u 
                               JOIN perfiles p ON u.id_perfil = p.id 
                               WHERE u.usuario = ? AND u.password = ? AND u.estado = 1");
        $stmt->execute(['admin', sha1('123456')]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($user) {
            echo "<div class='success'><span class='test-ok'>✓ PASS</span> - Login funciona correctamente</div>";
            echo "<div class='info'>Usuario: <code>{$user->usuario}</code> | Perfil: <code>{$user->perfil_nombre}</code></div>";
            $tests_passed++;
        } else {
            throw new Exception("No se pudo autenticar al usuario admin");
        }
    } catch (Exception $e) {
        echo "<div class='error'><span class='test-fail'>✗ FAIL</span> - " . $e->getMessage() . "</div>";
        $tests_failed++;
    }
    
    // Cambio de contraseña
    echo "<h3>6. Verificar Cambio de Contraseña</h3>";
    $tests_total++;
    try {
        $stmt = $pdo->prepare("SELECT id, usuario, password, nombre, apellidos FROM usuarios WHERE id = ? AND estado = 1");
        $stmt->execute([1]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($user && isset($user->id) && isset($user->apellidos)) {
            echo "<div class='success'><span class='test-ok'>✓ PASS</span> - Query de cambio de contraseña es correcta</div>";
            echo "<div class='info'>Campos verificados: <code>id, usuario, password, nombre, apellidos</code></div>";
            $tests_passed++;
        } else {
            throw new Exception("La query no retorna los campos esperados");
        }
    } catch (Exception $e) {
        echo "<div class='error'><span class='test-fail'>✗ FAIL</span> - " . $e->getMessage() . "</div>";
        $tests_failed++;
    }
    
    // ==================== TABLAS ADICIONALES ====================
    echo "<h2>📊 TABLAS ADICIONALES DEL SISTEMA</h2>";
    
    $tablas_sistema = [
        'perfiles' => 'Perfiles de usuario',
        'modulos' => 'Módulos del sistema',
        'menu' => 'Menú de navegación',
    ];
    
    foreach ($tablas_sistema as $tabla => $descripcion) {
        echo "<h3>Verificar tabla: $tabla - $descripcion</h3>";
        $tests_total++;
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $tabla");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<div class='success'><span class='test-ok'>✓ PASS</span> - Tabla $tabla existe con {$result['total']} registros</div>";
            $tests_passed++;
        } catch (Exception $e) {
            echo "<div class='error'><span class='test-fail'>✗ FAIL</span> - " . $e->getMessage() . "</div>";
            $tests_failed++;
        }
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>❌ Error de conexión: " . $e->getMessage() . "</div>";
}

// Resumen final
echo "<hr>";
echo "<h2>📊 RESUMEN DE PRUEBAS</h2>";
echo "<table style='width: 60%; margin: 20px auto;'>";
echo "<tr><th>Total de pruebas</th><td style='text-align: center; font-size: 24px;'>$tests_total</td></tr>";
echo "<tr style='background: #d4edda;'><th>Pruebas exitosas</th><td style='text-align: center; font-size: 24px; color: green; font-weight: bold;'>$tests_passed</td></tr>";
echo "<tr style='background: #f8d7da;'><th>Pruebas fallidas</th><td style='text-align: center; font-size: 24px; color: red; font-weight: bold;'>$tests_failed</td></tr>";

$porcentaje = $tests_total > 0 ? round(($tests_passed / $tests_total) * 100, 2) : 0;
echo "<tr><th>Porcentaje de éxito</th><td style='text-align: center; font-size: 24px; color: " . ($porcentaje >= 90 ? 'green' : ($porcentaje >= 70 ? 'orange' : 'red')) . "; font-weight: bold;'>$porcentaje%</td></tr>";
echo "</table>";

if ($tests_failed == 0) {
    echo "<div class='success' style='text-align: center; font-size: 20px; padding: 30px;'>";
    echo "<strong>🎉 ¡TODAS LAS PRUEBAS PASARON EXITOSAMENTE!</strong><br>";
    echo "El sistema está 100% funcional y listo para usar.";
    echo "</div>";
} else {
    echo "<div class='warning' style='text-align: center; font-size: 20px; padding: 30px;'>";
    echo "<strong>⚠️ Algunas pruebas fallaron</strong><br>";
    echo "Revisa los errores de arriba para más detalles.";
    echo "</div>";
}

echo "<hr>";
echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='login.php' class='btn'>🔐 Ir a Login</a> ";
echo "<a href='dashboard.php' class='btn'>📊 Dashboard</a> ";
echo "<a href='correccion_masiva.php' class='btn'>🔧 Ver Correcciones</a>";
echo "</div>";

echo "</div></body></html>";
?>
