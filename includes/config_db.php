<?php
/**
 * Configuración Centralizada de Base de Datos - SIMAHG
 * Este archivo centraliza la conexión a la BD para facilitar el acceso compartido
 */

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================

// Para uso local (cada uno en su propia máquina)
define('DB_HOST_LOCAL', 'localhost');
define('DB_PORT_LOCAL', '3307');

// Para uso compartido en red local
// INSTRUCCIONES:
// 1. Reemplaza 'TU_IP_LOCAL' con la IP del servidor (ejemplo: 192.168.1.100)
// 2. Para obtener tu IP en Mac: System Preferences > Network
//    o ejecuta en terminal: ipconfig getifaddr en0
define('DB_HOST_REMOTE', 'TU_IP_LOCAL'); // Ejemplo: 192.168.1.100
define('DB_PORT_REMOTE', '3306');

// Credenciales de la base de datos
define('DB_NAME', 'simahg_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Modo de conexión: 'local' o 'remote'
// Cambia a 'remote' cuando quieras que otros se conecten
define('DB_MODE', 'local');

// ============================================
// FUNCIÓN PARA OBTENER CONEXIÓN PDO
// ============================================

/**
 * Obtiene una conexión PDO a la base de datos
 * @return PDO
 * @throws PDOException
 */
function getDBConnection() {
    try {
        $host = (DB_MODE === 'remote') ? DB_HOST_REMOTE : DB_HOST_LOCAL;
        $port = (DB_MODE === 'remote') ? DB_PORT_REMOTE : DB_PORT_LOCAL;
        
        $dsn = "mysql:host={$host};port={$port};dbname=" . DB_NAME . ";charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        return $pdo;
        
    } catch (PDOException $e) {
        // Log del error
        error_log("Error de conexión a BD: " . $e->getMessage());
        
        // Mensaje amigable al usuario
        if (DB_MODE === 'remote') {
            die("❌ Error: No se puede conectar al servidor remoto ({$host}:{$port}). Verifica que el servidor MySQL esté configurado para aceptar conexiones remotas.");
        } else {
            die("❌ Error: No se puede conectar a la base de datos local. Verifica que XAMPP esté corriendo.");
        }
    }
}

/**
 * Verifica si la conexión a la base de datos está disponible
 * @return bool
 */
function testDBConnection() {
    try {
        $pdo = getDBConnection();
        $pdo->query("SELECT 1");
        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Obtiene información de la conexión actual
 * @return array
 */
function getDBInfo() {
    $host = (DB_MODE === 'remote') ? DB_HOST_REMOTE : DB_HOST_LOCAL;
    $port = (DB_MODE === 'remote') ? DB_PORT_REMOTE : DB_PORT_LOCAL;
    
    return [
        'mode' => DB_MODE,
        'host' => $host,
        'port' => $port,
        'database' => DB_NAME,
        'status' => testDBConnection() ? 'Conectado ✓' : 'Desconectado ✗'
    ];
}
