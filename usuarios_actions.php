<?php
/**
 * Procesamiento de acciones para gestión de usuarios - SIMAHG
 */

session_start();
header('Content-Type: application/json');

// Verificar que sea administrador
require_once 'includes/config_common.php';

if (!esAdmin()) {
    echo json_encode(['success' => false, 'message' => 'No tienes permisos']);
    exit();
}

// Conexión a BD
$pdo = getDBConnection();

// Obtener acción
$action = $_POST['action'] ?? '';

try {
    
    switch ($action) {
        
        case 'editar':
            $user_id = $_POST['user_id'] ?? 0;
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $id_perfil = $_POST['id_perfil'] ?? 0;
            
            // Validaciones
            if (empty($nombre) || empty($apellidos) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                exit();
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['success' => false, 'message' => 'Email inválido']);
                exit();
            }
            
            // Actualizar usuario
            $sql = "UPDATE usuarios 
                    SET nombre = ?, apellidos = ?, email = ?, telefono = ?, id_perfil = ? 
                    WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $apellidos, $email, $telefono, $id_perfil, $user_id]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Usuario actualizado correctamente'
            ]);
            break;
            
        case 'cambiar_estado':
            $user_id = $_POST['user_id'] ?? 0;
            $estado = $_POST['estado'] ?? 1;
            
            // No permitir desactivar al usuario actual
            if ($user_id == $_SESSION['user_id'] && $estado == 0) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'No puedes desactivarte a ti mismo'
                ]);
                exit();
            }
            
            $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$estado, $user_id]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Estado actualizado correctamente'
            ]);
            break;
            
        case 'resetear_password':
            $user_id = $_POST['user_id'] ?? 0;
            
            // Contraseña por defecto: 123456
            $password_default = sha1('123456');
            
            $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$password_default, $user_id]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Contraseña reseteada a: 123456'
            ]);
            break;
            
        case 'crear':
            $usuario = trim($_POST['usuario'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $id_perfil = $_POST['id_perfil'] ?? 0;
            $password = sha1('123456'); // Contraseña por defecto
            
            // Validaciones
            if (empty($usuario) || empty($nombre) || empty($apellidos) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Campos obligatorios incompletos']);
                exit();
            }
            
            // Verificar si el usuario ya existe
            $sql_check = "SELECT id FROM usuarios WHERE usuario = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$usuario]);
            
            if ($stmt_check->rowCount() > 0) {
                echo json_encode(['success' => false, 'message' => 'El usuario ya existe']);
                exit();
            }
            
            // Crear usuario
            $sql = "INSERT INTO usuarios (usuario, password, nombre, apellidos, email, telefono, id_perfil, estado) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$usuario, $password, $nombre, $apellidos, $email, $telefono, $id_perfil]);
            
            echo json_encode([
                'success' => true, 
                'message' => 'Usuario creado con contraseña: 123456'
            ]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error de base de datos: ' . $e->getMessage()
    ]);
}
?>
