<?php
/**
 * Clase Auth - Gestión de Autenticación y Autorización
 * SIMAHG - Sistema de Mantenimiento y Administración de Hidrogas
 */

require_once 'config.php';

class Auth {
    
    private $pdo;
    
    public function __construct() {
        $this->pdo = getDBConnection();
    }
    
    /**
     * Verifica si el usuario está autenticado
     */
    public static function check() {
        session_start();
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Verifica si el usuario tiene un rol específico
     */
    public static function hasRole($rolNombre) {
        if (!self::check()) {
            return false;
        }
        
        $rolNombreSesion = strtoupper($_SESSION['rol_nombre'] ?? '');
        $rolNombreBuscado = strtoupper($rolNombre);
        
        return $rolNombreSesion === $rolNombreBuscado;
    }
    
    /**
     * Verifica si el usuario tiene uno de varios roles
     */
    public static function hasAnyRole($roles) {
        foreach ($roles as $rol) {
            if (self::hasRole($rol)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Requiere autenticación - redirige si no está logueado
     */
    public static function require() {
        if (!self::check()) {
            header('Location: ' . APP_URL . '/login.php');
            exit;
        }
    }
    
    /**
     * Requiere un rol específico - redirige si no lo tiene
     */
    public static function requireRole($rolNombre) {
        self::require();
        
        if (!self::hasRole($rolNombre)) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            header('Location: ' . APP_URL . '/dashboard.php');
            exit;
        }
    }
    
    /**
     * Requiere uno de varios roles
     */
    public static function requireAnyRole($roles) {
        self::require();
        
        if (!self::hasAnyRole($roles)) {
            $_SESSION['error'] = 'No tienes permisos para acceder a esta sección';
            header('Location: ' . APP_URL . '/dashboard.php');
            exit;
        }
    }
    
    /**
     * Obtiene el ID del usuario actual
     */
    public static function userId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Obtiene el nombre del usuario actual
     */
    public static function userName() {
        return $_SESSION['nombre'] ?? 'Usuario';
    }
    
    /**
     * Obtiene el rol del usuario actual
     */
    public static function userRole() {
        return $_SESSION['rol_nombre'] ?? 'Usuario';
    }
    
    /**
     * Obtiene el ID del rol del usuario actual
     */
    public static function userRoleId() {
        return $_SESSION['id_perfil'] ?? null;
    }
    
    /**
     * Cierra la sesión del usuario
     */
    public static function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . APP_URL . '/login.php');
        exit;
    }
    
    /**
     * Login de usuario
     */
    public function login($usuario, $password) {
        try {
            $passwordEncriptado = encriptarPassword($password);
            
            $stmt = $this->pdo->prepare("
                SELECT u.*, p.nombre as rol_nombre 
                FROM usuarios u
                INNER JOIN perfiles p ON u.id_perfil = p.id
                WHERE u.usuario = ? AND u.password = ? AND u.estado = 1
            ");
            
            $stmt->execute([$usuario, $passwordEncriptado]);
            $user = $stmt->fetch();
            
            if ($user) {
                // Actualizar último acceso
                $stmtUpdate = $this->pdo->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
                $stmtUpdate->execute([$user['id']]);
                
                // Guardar en sesión
                session_start();
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['apellidos'] = $user['apellidos'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['id_perfil'] = $user['id_perfil'];
                $_SESSION['rol_nombre'] = $user['rol_nombre'];
                $_SESSION['login_time'] = time();
                
                return true;
            }
            
            return false;
            
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verifica permisos para un módulo específico
     */
    public function tienePermiso($modulo) {
        $rolId = self::userRoleId();
        
        // Administrador tiene acceso a todo
        if ($rolId == ROLES['ADMINISTRADOR']) {
            return true;
        }
        
        // Definir permisos por módulo y rol
        $permisos = [
            'usuarios' => [ROLES['ADMINISTRADOR']],
            'equipos' => [ROLES['ADMINISTRADOR'], ROLES['SUPERVISOR'], ROLES['TECNICO']],
            'mantenimientos' => [ROLES['ADMINISTRADOR'], ROLES['SUPERVISOR'], ROLES['TECNICO']],
            'epps' => [ROLES['ADMINISTRADOR'], ROLES['SUPERVISOR'], ROLES['TRABAJADOR']],
            'solicitudes_epp' => [ROLES['ADMINISTRADOR'], ROLES['SUPERVISOR'], ROLES['TRABAJADOR']],
            'reportes' => [ROLES['ADMINISTRADOR'], ROLES['SUPERVISOR']],
            'configuracion' => [ROLES['ADMINISTRADOR']]
        ];
        
        return in_array($rolId, $permisos[$modulo] ?? []);
    }
}
