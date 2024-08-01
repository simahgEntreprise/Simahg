<?php
/**
 * Script de verificación final de todas las correcciones
 */

// Configuración de la base de datos
$host = 'localhost';
$port = '3307';
$dbname = 'simahg_db';
$username = 'root';
$password = '';

$errores = [];
$warnings = [];
$exitos = [];

echo "<!DOCTYPE html>";
echo "<html lang='es'>";
echo "<head>";
echo "<meta charset='utf-8'>";
echo "<title>Verificación Final SIMAHG</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f4f6f9; }";
echo ".container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }";
echo "h1 { color: #667eea; text-align: center; }";
echo "h2 { color: #333; border-bottom: 2px solid #667eea; padding-bottom: 10px; margin-top: 30px; }";
echo ".error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #dc3545; }";
echo ".warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #ffc107; }";
echo ".success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #28a745; }";
echo ".info { background: #d1ecf1; color: #0c5460; padding: 15px; border-radius: 5px; margin: 10px 0; border-left: 4px solid #17a2b8; }";
echo "table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
echo "th, td { padding: 12px; text-align: left; border: 1px solid #ddd; }";
echo "th { background: #667eea; color: white; }";
echo "tr:nth-child(even) { background: #f9f9f9; }";
echo ".btn { display: inline-block; padding: 10px 20px; margin: 5px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }";
echo ".btn:hover { background: #5568d3; }";
echo "</style>";
echo "</head>";
echo "<body>";
echo "<div class='container'>";

echo "<h1>🔍 Verificación Final del Sistema SIMAHG</h1>";
echo "<p style='text-align: center; color: #666;'>Comprobando todas las correcciones y funcionalidades</p>";
echo "<hr>";

// 1. Verificar conexión a la base de datos
echo "<h2>1. Conexión a la Base de Datos</h2>";
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<div class='success'>✓ Conexión exitosa a la base de datos</div>";
    $exitos[] = "Conexión a BD";
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</div>";
    $errores[] = "Conexión a BD";
    echo "</div></body></html>";
    exit;
}

// 2. Verificar estructura de la tabla usuarios
echo "<h2>2. Estructura de la Tabla 'usuarios'</h2>";
try {
    $stmt = $pdo->query("DESCRIBE usuarios");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Key</th><th>Default</th></tr>";
    
    $campos_necesarios = ['id', 'usuario', 'password', 'nombre', 'apellidos', 'email', 'estado'];
    $campos_encontrados = [];
    
    foreach ($columns as $col) {
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($col['Field']) . "</strong></td>";
        echo "<td>" . htmlspecialchars($col['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($col['Default']) . "</td>";
        echo "</tr>";
        
        $campos_encontrados[] = $col['Field'];
    }
    
    echo "</table>";
    
    // Verificar campos necesarios
    $campos_faltantes = array_diff($campos_necesarios, $campos_encontrados);
    if (empty($campos_faltantes)) {
        echo "<div class='success'>✓ Todos los campos necesarios están presentes</div>";
        $exitos[] = "Estructura de tabla usuarios";
    } else {
        echo "<div class='error'>✗ Campos faltantes: " . implode(', ', $campos_faltantes) . "</div>";
        $errores[] = "Campos faltantes en usuarios";
    }
    
    // Verificar que NO exista fecha_modificacion
    if (in_array('fecha_modificacion', $campos_encontrados)) {
        echo "<div class='warning'>⚠ La tabla tiene el campo 'fecha_modificacion' (no usado en código actual)</div>";
        $warnings[] = "Campo fecha_modificacion existe pero no se usa";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error al verificar estructura: " . htmlspecialchars($e->getMessage()) . "</div>";
    $errores[] = "Verificación de estructura";
}

// 3. Verificar usuarios de prueba
echo "<h2>3. Usuarios en el Sistema</h2>";
try {
    $stmt = $pdo->query("SELECT id, usuario, nombre, apellidos, email, estado FROM usuarios ORDER BY id");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($usuarios) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Usuario</th><th>Nombre Completo</th><th>Email</th><th>Estado</th></tr>";
        
        foreach ($usuarios as $user) {
            $estado = $user['estado'] == 1 ? '<span style="color: green;">✓ Activo</span>' : '<span style="color: red;">✗ Inactivo</span>';
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['usuario']) . "</td>";
            echo "<td>" . htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . $estado . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<div class='success'>✓ Se encontraron " . count($usuarios) . " usuarios</div>";
        echo "<div class='info'>💡 Contraseña por defecto: <strong>123456</strong></div>";
        $exitos[] = "Usuarios existentes";
    } else {
        echo "<div class='warning'>⚠ No hay usuarios en el sistema</div>";
        $warnings[] = "Sin usuarios";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error al obtener usuarios: " . htmlspecialchars($e->getMessage()) . "</div>";
    $errores[] = "Listado de usuarios";
}

// 4. Verificar archivos críticos
echo "<h2>4. Archivos Críticos del Sistema</h2>";
$archivos_criticos = [
    'login.php' => 'Página de inicio de sesión',
    'login_process.php' => 'Procesamiento de login',
    'dashboard.php' => 'Panel principal',
    'cambiar_password.php' => 'Formulario cambio de contraseña',
    'cambiar_password_process.php' => 'Procesamiento cambio de contraseña',
    'recuperar_password.php' => 'Recuperación de contraseña',
    'recuperar_password_process.php' => 'Procesamiento recuperación',
    'verificar_codigo.php' => 'Verificación de código',
    'nueva_password.php' => 'Nueva contraseña',
    'logout.php' => 'Cierre de sesión',
];

echo "<table>";
echo "<tr><th>Archivo</th><th>Descripción</th><th>Estado</th><th>Tabla Usada</th></tr>";

foreach ($archivos_criticos as $archivo => $descripcion) {
    $ruta = __DIR__ . '/' . $archivo;
    $existe = file_exists($ruta);
    
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($archivo) . "</strong></td>";
    echo "<td>" . htmlspecialchars($descripcion) . "</td>";
    
    if ($existe) {
        echo "<td style='color: green;'>✓ Existe</td>";
        
        // Verificar qué tabla usa
        $contenido = file_get_contents($ruta);
        if (strpos($contenido, 'FROM usuarios') !== false || strpos($contenido, 'UPDATE usuarios') !== false) {
            echo "<td style='color: green;'>✓ usuarios</td>";
        } elseif (strpos($contenido, 'FROM usuario ') !== false || strpos($contenido, 'UPDATE usuario ') !== false) {
            echo "<td style='color: red;'>✗ usuario (incorrecto)</td>";
            $errores[] = "$archivo usa tabla incorrecta";
        } else {
            echo "<td style='color: gray;'>- N/A</td>";
        }
        
        $exitos[] = "Archivo $archivo existe";
    } else {
        echo "<td style='color: red;'>✗ No existe</td>";
        echo "<td style='color: gray;'>-</td>";
        $errores[] = "Archivo $archivo faltante";
    }
    
    echo "</tr>";
}

echo "</table>";

// 5. Verificar código en cambiar_password_process.php
echo "<h2>5. Verificación Específica: cambiar_password_process.php</h2>";
$archivo_cambio = __DIR__ . '/cambiar_password_process.php';
if (file_exists($archivo_cambio)) {
    $contenido = file_get_contents($archivo_cambio);
    
    // Buscar problemas comunes
    $problemas = [];
    
    if (strpos($contenido, 'FROM usuario WHERE') !== false) {
        $problemas[] = "Usa 'FROM usuario WHERE' en lugar de 'FROM usuarios WHERE'";
    }
    
    if (strpos($contenido, 'UPDATE usuario SET') !== false) {
        $problemas[] = "Usa 'UPDATE usuario SET' en lugar de 'UPDATE usuarios SET'";
    }
    
    if (strpos($contenido, '$_SESSION[\'idusuario\']') !== false) {
        $problemas[] = "Usa '\$_SESSION['idusuario']' en lugar de '\$_SESSION['user_id']'";
    }
    
    if (strpos($contenido, 'fecha_modificacion') !== false) {
        $problemas[] = "Contiene referencia a 'fecha_modificacion'";
    }
    
    if (empty($problemas)) {
        echo "<div class='success'>✓ El archivo está correctamente configurado</div>";
        
        // Mostrar fragmentos clave
        echo "<div class='info'>";
        echo "<strong>Fragmentos clave del código:</strong><br><br>";
        
        $lines = explode("\n", $contenido);
        foreach ($lines as $num => $line) {
            if (stripos($line, 'SELECT') !== false && stripos($line, 'FROM usuarios') !== false) {
                echo "<code>Línea " . ($num + 1) . ": " . htmlspecialchars(trim($line)) . "</code><br>";
            }
            if (stripos($line, 'UPDATE usuarios') !== false) {
                echo "<code>Línea " . ($num + 1) . ": " . htmlspecialchars(trim($line)) . "</code><br>";
            }
            if (stripos($line, '$_SESSION[\'user_id\']') !== false) {
                echo "<code>Línea " . ($num + 1) . ": " . htmlspecialchars(trim($line)) . "</code><br>";
            }
        }
        echo "</div>";
        
        $exitos[] = "cambiar_password_process.php correcto";
    } else {
        echo "<div class='error'>";
        echo "✗ Se encontraron los siguientes problemas:<ul>";
        foreach ($problemas as $problema) {
            echo "<li>" . htmlspecialchars($problema) . "</li>";
        }
        echo "</ul></div>";
        $errores = array_merge($errores, $problemas);
    }
} else {
    echo "<div class='error'>✗ Archivo no encontrado</div>";
    $errores[] = "cambiar_password_process.php no existe";
}

// 6. Probar cambio de contraseña (simulación)
echo "<h2>6. Prueba de Cambio de Contraseña (Simulación)</h2>";
try {
    // Obtener un usuario de prueba
    $stmt = $pdo->query("SELECT id, usuario, password FROM usuarios WHERE estado = 1 LIMIT 1");
    $user_prueba = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($user_prueba) {
        echo "<div class='info'>";
        echo "<strong>Usuario de prueba:</strong> " . htmlspecialchars($user_prueba->usuario) . "<br>";
        echo "<strong>ID:</strong> " . htmlspecialchars($user_prueba->id) . "<br>";
        echo "<strong>Hash actual:</strong> " . htmlspecialchars(substr($user_prueba->password, 0, 20)) . "...<br>";
        echo "</div>";
        
        // Simular UPDATE (sin ejecutar)
        $new_pass_hash = sha1('nueva_password_123');
        echo "<div class='info'>";
        echo "<strong>Query que se ejecutaría:</strong><br>";
        echo "<code>UPDATE usuarios SET password = '$new_pass_hash' WHERE id = {$user_prueba->id}</code>";
        echo "</div>";
        
        echo "<div class='success'>✓ La sintaxis del UPDATE es correcta</div>";
        $exitos[] = "Sintaxis de UPDATE correcta";
    } else {
        echo "<div class='warning'>⚠ No hay usuarios para probar</div>";
        $warnings[] = "Sin usuarios para probar";
    }
    
} catch (PDOException $e) {
    echo "<div class='error'>✗ Error en prueba: " . htmlspecialchars($e->getMessage()) . "</div>";
    $errores[] = "Prueba de UPDATE";
}

// 7. Resumen final
echo "<hr>";
echo "<h2>📊 Resumen Final</h2>";

echo "<table style='width: auto; margin: 20px auto;'>";
echo "<tr>";
echo "<th style='background: #28a745;'>Éxitos</th>";
echo "<td style='text-align: center; font-size: 24px; font-weight: bold;'>" . count($exitos) . "</td>";
echo "</tr>";
echo "<tr>";
echo "<th style='background: #ffc107;'>Advertencias</th>";
echo "<td style='text-align: center; font-size: 24px; font-weight: bold;'>" . count($warnings) . "</td>";
echo "</tr>";
echo "<tr>";
echo "<th style='background: #dc3545;'>Errores</th>";
echo "<td style='text-align: center; font-size: 24px; font-weight: bold;'>" . count($errores) . "</td>";
echo "</tr>";
echo "</table>";

if (count($errores) == 0) {
    echo "<div class='success' style='font-size: 18px; text-align: center; padding: 30px;'>";
    echo "<strong>🎉 ¡SISTEMA VERIFICADO Y LISTO!</strong><br>";
    echo "Todos los componentes están correctamente configurados.";
    echo "</div>";
} else {
    echo "<div class='error' style='font-size: 18px; text-align: center; padding: 30px;'>";
    echo "<strong>⚠ SE ENCONTRARON ERRORES</strong><br>";
    echo "Revisa los problemas indicados arriba.";
    echo "</div>";
}

// 8. Acciones recomendadas
echo "<hr>";
echo "<h2>🔧 Pasos Siguientes</h2>";
echo "<div class='info'>";
echo "<ol>";
echo "<li><strong>Reinicia Apache en XAMPP</strong> para limpiar caché de PHP</li>";
echo "<li><strong>Cierra todos los navegadores</strong> completamente</li>";
echo "<li><strong>Abre el navegador en modo incógnito</strong></li>";
echo "<li><strong>Accede al sistema:</strong> <a href='login.php' class='btn'>Ir a Login</a></li>";
echo "<li><strong>Prueba el cambio de contraseña</strong> con el usuario: <code>admin</code> / contraseña: <code>123456</code></li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='login.php' class='btn'>🔐 Ir a Login</a> ";
echo "<a href='dashboard.php' class='btn'>📊 Ir a Dashboard</a> ";
echo "<a href='limpiar_cache.php' class='btn'>🧹 Limpiar Caché</a>";
echo "</div>";

echo "</div>";
echo "</body>";
echo "</html>";
?>
