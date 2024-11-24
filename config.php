<?php
/**
 * Archivo de configuración centralizado - SIMAHG
 * Sistema de Mantenimiento y Administración de Hidrogas
 */

// Configuración de Base de Datos
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_NAME', 'simahg_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Configuración de la aplicación
define('APP_NAME', 'SIMAHG');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/simahg');

// Configuración de sesión
define('SESSION_TIMEOUT', 3600); // 1 hora en segundos

// Configuración de permisos por rol
define('ROLES', [
    'ADMINISTRADOR' => 1,
    'SUPERVISOR' => 2,
    'TECNICO' => 3,
    'TRABAJADOR' => 4
]);

// Niveles de stock para alertas
define('STOCK_MINIMO', 10);
define('STOCK_CRITICO', 5);

// Días de anticipación para alertas de mantenimiento
define('DIAS_ALERTA_MANTENIMIENTO', 7);

// Zona horaria
date_default_timezone_set('America/Lima');

/**
 * Función para obtener conexión PDO
 */
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Error de conexión: " . $e->getMessage());
        die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
    }
}

/**
 * Función para formatear fechas
 */
function formatearFecha($fecha, $formato = 'd/m/Y H:i') {
    return date($formato, strtotime($fecha));
}

/**
 * Función para sanitizar entrada de usuario
 */
function sanitizar($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Función para encriptar contraseñas
 */
function encriptarPassword($password) {
    return sha1($password);
}

/**
 * Función para generar respuesta JSON
 */
function respuestaJSON($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}
# Update 1764801943
