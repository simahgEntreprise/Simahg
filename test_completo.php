<?php
/**
 * PRUEBA COMPLETA DE FUNCIONALIDADES - SIMAHG
 * Verifica que todo el sistema funcione correctamente
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de BD
require_once 'includes/config_common.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Pruebas SIMAHG</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        h1 { color: #667eea; margin-bottom: 30px; }
        .test-section {
            background: #f8f9fa;
            padding: 20px;
            margin: 15px 0;
            border-radius: 10px;
            border-left: 5px solid #667eea;
        }
        .test-section h3 { color: #333; margin-bottom: 15px; }
        .result { padding: 10px; margin: 5px 0; border-radius: 5px; }
        .success { background: #d4edda; color: #155724; border-left: 4px solid #28a745; }
        .error { background: #f8d7da; color: #721c24; border-left: 4px solid #dc3545; }
        .info { background: #d1ecf1; color: #0c5460; border-left: 4px solid #17a2b8; }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin: 5px;
            font-weight: 600;
        }
        .btn:hover { background: #764ba2; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; }
        th { background: #667eea; color: white; }
        tr:hover { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Pruebas Completas del Sistema SIMAHG</h1>
        
        <?php
        $total_tests = 0;
        $passed_tests = 0;
        $failed_tests = 0;
        
        // ==================== TEST 1: CONEXIÓN A BD ====================
        echo "<div class='test-section'>";
        echo "<h3>📊 Test 1: Conexión a Base de Datos</h3>";
        $total_tests++;
        
        try {
            $test_query = $pdo->query("SELECT COUNT(*) as total FROM usuario");
            $total_users = $test_query->fetch(PDO::FETCH_ASSOC)['total'];
            echo "<div class='result success'>✅ Conexión exitosa a base de datos</div>";
            echo "<div class='result info'>ℹ️ Total de usuarios en BD: $total_users</div>";
            $passed_tests++;
        } catch (Exception $e) {
            echo "<div class='result error'>❌ Error de conexión: " . $e->getMessage() . "</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 2: USUARIOS ====================
        echo "<div class='test-section'>";
        echo "<h3>👥 Test 2: Gestión de Usuarios</h3>";
        $total_tests++;
        
        try {
            $stmt = $pdo->query("SELECT usuario, nombre, rol, activo FROM usuarios LIMIT 5");
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($usuarios) > 0) {
                echo "<div class='result success'>✅ Usuarios encontrados: " . count($usuarios) . "</div>";
                echo "<table>";
                echo "<tr><th>Usuario</th><th>Nombre</th><th>Rol</th><th>Estado</th></tr>";
                foreach ($usuarios as $u) {
                    $estado = $u['activo'] ? '✅ Activo' : '❌ Inactivo';
                    echo "<tr><td>{$u['usuario']}</td><td>{$u['nombre']}</td><td>{$u['rol']}</td><td>$estado</td></tr>";
                }
                echo "</table>";
                $passed_tests++;
            } else {
                echo "<div class='result error'>❌ No hay usuarios en la base de datos</div>";
                $failed_tests++;
            }
        } catch (Exception $e) {
            echo "<div class='result error'>❌ Error al consultar usuarios: " . $e->getMessage() . "</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 3: TABLAS EPP ====================
        echo "<div class='test-section'>";
        echo "<h3>📦 Test 3: Tablas de EPP</h3>";
        $total_tests++;
        
        try {
            // Verificar tabla epp
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM epp");
            $total_epp = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Verificar tabla solicitud_epp
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM solicitud_epp");
            $total_solicitudes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            echo "<div class='result success'>✅ Tabla 'epp' existe - Total EPP: $total_epp</div>";
            echo "<div class='result success'>✅ Tabla 'solicitud_epp' existe - Total solicitudes: $total_solicitudes</div>";
            
            // Ver algunos EPP
            $stmt = $pdo->query("SELECT nombre, stock FROM epp LIMIT 5");
            $epps = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($epps) > 0) {
                echo "<table>";
                echo "<tr><th>EPP</th><th>Stock</th></tr>";
                foreach ($epps as $epp) {
                    echo "<tr><td>{$epp['nombre']}</td><td>{$epp['stock']}</td></tr>";
                }
                echo "</table>";
            }
            
            $passed_tests++;
        } catch (Exception $e) {
            echo "<div class='result error'>❌ Error en tablas EPP: " . $e->getMessage() . "</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 4: CREAR USUARIO (INSERT) ====================
        echo "<div class='test-section'>";
        echo "<h3>➕ Test 4: Crear Usuario (INSERT)</h3>";
        $total_tests++;
        
        try {
            $test_user = 'test_' . time();
            $test_pass = sha1('123456');
            
            $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, nombre, apellidos, email, rol, activo) VALUES (?, ?, ?, ?, ?, ?, 1)");
            $stmt->execute([$test_user, $test_pass, 'Usuario', 'Prueba', 'test@test.com', 'Trabajador']);
            
            $new_id = $pdo->lastInsertId();
            echo "<div class='result success'>✅ Usuario creado exitosamente - ID: $new_id, Usuario: $test_user</div>";
            $passed_tests++;
        } catch (Exception $e) {
            echo "<div class='result error'>❌ Error al crear usuario: " . $e->getMessage() . "</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 5: LEER USUARIO (SELECT) ====================
        echo "<div class='test-section'>";
        echo "<h3>📖 Test 5: Leer Usuario (SELECT)</h3>";
        $total_tests++;
        
        try {
            if (isset($test_user)) {
                $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
                $stmt->execute([$test_user]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user) {
                    echo "<div class='result success'>✅ Usuario encontrado: {$user['nombre']} {$user['apellidos']}</div>";
                    echo "<div class='result info'>ℹ️ Email: {$user['email']}, Rol: {$user['rol']}</div>";
                    $passed_tests++;
                } else {
                    echo "<div class='result error'>❌ Usuario no encontrado</div>";
                    $failed_tests++;
                }
            }
        } catch (Exception $e) {
            echo "<div class='result error'>❌ Error al leer usuario: " . $e->getMessage() . "</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 6: ACTUALIZAR USUARIO (UPDATE) ====================
        echo "<div class='test-section'>";
        echo "<h3>✏️ Test 6: Actualizar Usuario (UPDATE)</h3>";
        $total_tests++;
        
        try {
            if (isset($test_user)) {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, apellidos = ? WHERE usuario = ?");
                $stmt->execute(['Usuario Actualizado', 'Prueba Modificada', $test_user]);
                
                // Verificar actualización
                $stmt = $pdo->prepare("SELECT nombre, apellidos FROM usuarios WHERE usuario = ?");
                $stmt->execute([$test_user]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && $user['nombre'] == 'Usuario Actualizado') {
                    echo "<div class='result success'>✅ Usuario actualizado correctamente</div>";
                    echo "<div class='result info'>ℹ️ Nuevo nombre: {$user['nombre']} {$user['apellidos']}</div>";
                    $passed_tests++;
                } else {
                    echo "<div class='result error'>❌ Error en actualización</div>";
                    $failed_tests++;
                }
            }
        } catch (Exception $e) {
            echo "<div class='result error'>❌ Error al actualizar usuario: " . $e->getMessage() . "</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 7: ELIMINAR USUARIO (DELETE) ====================
        echo "<div class='test-section'>";
        echo "<h3>🗑️ Test 7: Eliminar Usuario (DELETE)</h3>";
        $total_tests++;
        
        try {
            if (isset($test_user)) {
                $stmt = $pdo->prepare("DELETE FROM usuarios WHERE usuario = ?");
                $stmt->execute([$test_user]);
                
                // Verificar eliminación
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios WHERE usuario = ?");
                $stmt->execute([$test_user]);
                $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                if ($count == 0) {
                    echo "<div class='result success'>✅ Usuario eliminado correctamente</div>";
                    $passed_tests++;
                } else {
                    echo "<div class='result error'>❌ Error al eliminar usuario</div>";
                    $failed_tests++;
                }
            }
        } catch (Exception $e) {
            echo "<div class='result error'>❌ Error al eliminar usuario: " . $e->getMessage() . "</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 8: VERIFICAR PÁGINAS ====================
        echo "<div class='test-section'>";
        echo "<h3>📄 Test 8: Archivos Principales</h3>";
        $total_tests++;
        
        $archivos_importantes = [
            'login.php' => 'Login',
            'login_process.php' => 'Proceso de login',
            'recuperar_password.php' => 'Recuperación de contraseña',
            'cambiar_password.php' => 'Cambio de contraseña',
            'dashboard.php' => 'Dashboard',
            'solicitudes_epp.php' => 'Solicitudes EPP',
            'home.php' => 'Home'
        ];
        
        $archivos_ok = 0;
        foreach ($archivos_importantes as $archivo => $descripcion) {
            if (file_exists($archivo)) {
                echo "<div class='result success'>✅ $descripcion ($archivo) - Existe</div>";
                $archivos_ok++;
            } else {
                echo "<div class='result error'>❌ $descripcion ($archivo) - No existe</div>";
            }
        }
        
        if ($archivos_ok == count($archivos_importantes)) {
            $passed_tests++;
        } else {
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== TEST 9: CONFIG EMAIL ====================
        echo "<div class='test-section'>";
        echo "<h3>📧 Test 9: Configuración de Email</h3>";
        $total_tests++;
        
        if (file_exists('config_email.php')) {
            require_once 'config_email.php';
            if (function_exists('enviarEmail')) {
                echo "<div class='result success'>✅ Configuración de email existe</div>";
                echo "<div class='result info'>ℹ️ Función enviarEmail() disponible</div>";
                $passed_tests++;
            } else {
                echo "<div class='result error'>❌ Función enviarEmail() no encontrada</div>";
                $failed_tests++;
            }
        } else {
            echo "<div class='result error'>❌ Archivo config_email.php no existe</div>";
            $failed_tests++;
        }
        echo "</div>";
        
        // ==================== RESUMEN FINAL ====================
        $percentage = ($total_tests > 0) ? round(($passed_tests / $total_tests) * 100, 2) : 0;
        $status_color = $percentage >= 80 ? '#28a745' : ($percentage >= 60 ? '#ffc107' : '#dc3545');
        
        echo "<div class='test-section' style='border-left-color: $status_color;'>";
        echo "<h3>📊 Resumen de Pruebas</h3>";
        echo "<div style='font-size: 1.2em; margin: 15px 0;'>";
        echo "<strong>Total de pruebas:</strong> $total_tests<br>";
        echo "<strong style='color: #28a745;'>✅ Exitosas:</strong> $passed_tests<br>";
        echo "<strong style='color: #dc3545;'>❌ Fallidas:</strong> $failed_tests<br>";
        echo "<strong>Porcentaje de éxito:</strong> <span style='color: $status_color; font-size: 1.5em;'>$percentage%</span>";
        echo "</div>";
        
        if ($percentage >= 80) {
            echo "<div class='result success'>🎉 ¡Sistema funcionando correctamente!</div>";
        } elseif ($percentage >= 60) {
            echo "<div class='result info'>⚠️ Sistema funcional con algunas advertencias</div>";
        } else {
            echo "<div class='result error'>❌ Sistema requiere atención inmediata</div>";
        }
        echo "</div>";
        ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="login.php" class="btn">🔐 Ir al Login</a>
            <a href="admin/mis_usuarios.php" class="btn">👥 Ver Usuarios</a>
            <a href="dashboard.php" class="btn">📊 Dashboard</a>
            <a href="?" class="btn" style="background: #6c757d;">🔄 Ejecutar de Nuevo</a>
        </div>
    </div>
</body>
</html>
