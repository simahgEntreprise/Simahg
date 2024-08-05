-- --------------------------------------------------------
-- Estructura de base de datos para SIMAHG - Sistema de Gestión
-- Creado para MySQL con XAMPP (Puerto 3307)
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

-- Base de datos: `simahg_db`
CREATE DATABASE IF NOT EXISTS `simahg_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `simahg_db`;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `perfiles`
CREATE TABLE `perfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos para la tabla `perfiles`
INSERT INTO `perfiles` (`id`, `nombre`, `descripcion`, `estado`) VALUES
(1, 'Administrador', 'Acceso completo al sistema', 1),
(2, 'Supervisor', 'Acceso de supervisión y reportes', 1),
(3, 'Operador', 'Acceso básico para operaciones', 1),
(4, 'Usuario', 'Acceso limitado de consulta', 1);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL UNIQUE,
  `usuario` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `id_perfil` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos para la tabla `usuarios`
-- Contraseña por defecto para todos: 123456 (encriptada con SHA1)
INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `usuario`, `password`, `id_perfil`, `estado`) VALUES
(1, 'Administrador', 'del Sistema', 'admin@simahg.com', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 1, 1),
(2, 'Juan Carlos', 'Pérez López', 'juan@simahg.com', 'jperez', '7c4a8d09ca3762af61e59520943dc26494f8941b', 2, 1),
(3, 'María Isabel', 'García Torres', 'maria@simahg.com', 'mgarcia', '7c4a8d09ca3762af61e59520943dc26494f8941b', 3, 1),
(4, 'Pedro Antonio', 'Rodríguez Silva', 'pedro@simahg.com', 'prodriguez', '7c4a8d09ca3762af61e59520943dc26494f8941b', 4, 1);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `modulos`
CREATE TABLE `modulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `controlador` varchar(100) NOT NULL,
  `icono` varchar(50) DEFAULT 'fa-folder',
  `orden` int(11) DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos para la tabla `modulos`
INSERT INTO `modulos` (`id`, `nombre`, `descripcion`, `controlador`, `icono`, `orden`, `estado`) VALUES
(1, 'Dashboard', 'Panel principal del sistema', 'home', 'fa-dashboard', 1, 1),
(2, 'Configuración', 'Gestión de usuarios y configuraciones', 'configuracion', 'fa-cogs', 2, 1),
(3, 'EPP', 'Gestión de Equipos de Protección Personal', 'epp', 'fa-shield', 3, 1),
(4, 'Procesos', 'Gestión de procesos y flujos de trabajo', 'proceso', 'fa-tasks', 4, 1),
(5, 'Reportes', 'Generación de reportes y estadísticas', 'reportes', 'fa-bar-chart', 5, 1);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `menu`
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `id_modulo` int(11) NOT NULL,
  `icono` varchar(50) DEFAULT 'fa-circle-o',
  `orden` int(11) DEFAULT 0,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_modulo`) REFERENCES `modulos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos para la tabla `menu`
INSERT INTO `menu` (`id`, `nombre`, `url`, `id_modulo`, `icono`, `orden`, `estado`) VALUES
(1, 'Dashboard', 'home/index', 1, 'fa-dashboard', 1, 1),
(2, 'Usuarios', 'configuracion/usuarios', 2, 'fa-users', 1, 1),
(3, 'Perfiles', 'configuracion/perfiles', 2, 'fa-user-circle', 2, 1),
(4, 'Lista EPP', 'epp/index', 3, 'fa-list', 1, 1),
(5, 'Asignación EPP', 'epp/asignacion', 3, 'fa-plus', 2, 1),
(6, 'Procesos Activos', 'proceso/activos', 4, 'fa-play', 1, 1),
(7, 'Historial Procesos', 'proceso/historial', 4, 'fa-history', 2, 1),
(8, 'Reporte Usuarios', 'reportes/usuarios', 5, 'fa-file-text', 1, 1),
(9, 'Reporte EPP', 'reportes/epp', 5, 'fa-file-pdf-o', 2, 1);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `permisos`
CREATE TABLE `permisos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `crear` tinyint(1) DEFAULT 0,
  `leer` tinyint(1) DEFAULT 1,
  `actualizar` tinyint(1) DEFAULT 0,
  `eliminar` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_perfil`) REFERENCES `perfiles` (`id`),
  FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permisos para Administrador (todos los permisos)
INSERT INTO `permisos` (`id_perfil`, `id_menu`, `crear`, `leer`, `actualizar`, `eliminar`) VALUES
(1, 1, 1, 1, 1, 1), (1, 2, 1, 1, 1, 1), (1, 3, 1, 1, 1, 1), (1, 4, 1, 1, 1, 1),
(1, 5, 1, 1, 1, 1), (1, 6, 1, 1, 1, 1), (1, 7, 1, 1, 1, 1), (1, 8, 1, 1, 1, 1), (1, 9, 1, 1, 1, 1);

-- Permisos para Supervisor 
INSERT INTO `permisos` (`id_perfil`, `id_menu`, `crear`, `leer`, `actualizar`, `eliminar`) VALUES
(2, 1, 0, 1, 0, 0), (2, 4, 1, 1, 1, 0), (2, 5, 1, 1, 1, 0), 
(2, 6, 0, 1, 1, 0), (2, 7, 0, 1, 0, 0), (2, 8, 0, 1, 0, 0), (2, 9, 0, 1, 0, 0);

-- Permisos para Operador
INSERT INTO `permisos` (`id_perfil`, `id_menu`, `crear`, `leer`, `actualizar`, `eliminar`) VALUES
(3, 1, 0, 1, 0, 0), (3, 4, 0, 1, 0, 0), (3, 5, 1, 1, 0, 0), (3, 6, 0, 1, 0, 0);

-- Permisos para Usuario 
INSERT INTO `permisos` (`id_perfil`, `id_menu`, `crear`, `leer`, `actualizar`, `eliminar`) VALUES
(4, 1, 0, 1, 0, 0), (4, 4, 0, 1, 0, 0), (4, 7, 0, 1, 0, 0);

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `sesiones`
CREATE TABLE `sesiones` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Estructura de tabla para la tabla `configuraciones`
CREATE TABLE `configuraciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) NOT NULL UNIQUE,
  `valor` text NOT NULL,
  `descripcion` text,
  `tipo` enum('texto','numero','booleano','json') DEFAULT 'texto',
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos para la tabla `configuraciones`
INSERT INTO `configuraciones` (`clave`, `valor`, `descripcion`, `tipo`) VALUES
('sistema_nombre', 'SIMAHG - Sistema de Gestión', 'Nombre del sistema', 'texto'),
('sistema_version', '2.0.0', 'Versión actual del sistema', 'texto'),
('empresa_nombre', 'Tu Empresa S.A.', 'Nombre de la empresa', 'texto'),
('empresa_direccion', 'Av. Principal 123, Lima, Perú', 'Dirección de la empresa', 'texto'),
('empresa_telefono', '+51 1 234-5678', 'Teléfono de la empresa', 'texto'),
('sistema_mantenimiento', '0', 'Modo mantenimiento del sistema', 'booleano'),
('sesion_timeout', '7200', 'Tiempo de expiración de sesión en segundos', 'numero');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
# Update 1764801941
