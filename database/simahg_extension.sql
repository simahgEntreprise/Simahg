-- ========================================================
-- SIMAHG - Extensión de Base de Datos
-- Agregando tablas para Gestión de Equipos y EPPs
-- ========================================================

USE `simahg_db`;

-- --------------------------------------------------------
-- Tabla: categorias_equipos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorias_equipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias_equipos` (`nombre`, `descripcion`) VALUES
('Excavadoras', 'Equipos de excavación y movimiento de tierra'),
('Compactadores', 'Equipos para compactación de suelos'),
('Generadores', 'Equipos generadores de energía eléctrica'),
('Bombas', 'Equipos de bombeo de agua y otros fluidos'),
('Herramientas Eléctricas', 'Herramientas y equipos eléctricos'),
('Vehículos', 'Vehículos y equipos de transporte');

-- --------------------------------------------------------
-- Tabla: equipos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `equipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL UNIQUE,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `marca` varchar(100),
  `modelo` varchar(100),
  `numero_serie` varchar(100),
  `año_fabricacion` year,
  `id_categoria` int(11) NOT NULL,
  `fecha_adquisicion` date,
  `costo_adquisicion` decimal(12,2),
  `ubicacion` varchar(200),
  `estado` enum('operativo','mantenimiento','fuera_servicio','dado_baja') NOT NULL DEFAULT 'operativo',
  `horas_operacion` decimal(10,2) DEFAULT 0.00,
  `vida_util_años` int(11) DEFAULT 10,
  `responsable` varchar(150),
  `observaciones` text,
  `fecha_ultima_inspeccion` date,
  `fecha_proximo_mantenimiento` date,
  `certificaciones_vigentes` json,
  `documentos_adjuntos` json,
  `imagen_url` varchar(255),
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creado_por` int(11),
  `actualizado_por` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_categoria`) REFERENCES `categorias_equipos` (`id`),
  FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo para equipos
INSERT INTO `equipos` (`codigo`, `nombre`, `descripcion`, `marca`, `modelo`, `numero_serie`, `año_fabricacion`, `id_categoria`, `fecha_adquisicion`, `costo_adquisicion`, `ubicacion`, `estado`, `responsable`, `fecha_proximo_mantenimiento`, `creado_por`) VALUES
('EXC-001', 'Excavadora Hidráulica 320', 'Excavadora para trabajos de construcción pesada', 'Caterpillar', 'CAT 320D', 'CAT320D2023001', 2023, 1, '2023-01-15', 250000.00, 'Almacén Principal', 'operativo', 'Juan Pérez', '2025-01-15', 1),
('GEN-001', 'Generador Eléctrico 100KW', 'Generador de respaldo para obras', 'Cummins', 'C100D5', 'CUM100D2022001', 2022, 3, '2022-06-10', 45000.00, 'Obra Norte', 'operativo', 'María García', '2024-12-10', 1),
('BOM-001', 'Bomba de Agua Centrífuga', 'Bomba para drenaje de excavaciones', 'Grundfos', 'CR45-2', 'GRU452023001', 2023, 4, '2023-03-20', 8500.00, 'Almacén Herramientas', 'mantenimiento', 'Pedro Rodríguez', '2024-12-01', 1);

-- --------------------------------------------------------
-- Tabla: tipos_mantenimiento
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `tipos_mantenimiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `frecuencia_dias` int(11) DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tipos_mantenimiento` (`nombre`, `descripcion`, `frecuencia_dias`) VALUES
('Preventivo Mensual', 'Mantenimiento preventivo mensual', 30),
('Preventivo Trimestral', 'Mantenimiento preventivo trimestral', 90),
('Preventivo Semestral', 'Mantenimiento preventivo semestral', 180),
('Preventivo Anual', 'Mantenimiento preventivo anual', 365),
('Correctivo', 'Mantenimiento correctivo por falla', NULL),
('Emergencia', 'Mantenimiento de emergencia', NULL);

-- --------------------------------------------------------
-- Tabla: mantenimientos
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mantenimientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL UNIQUE,
  `id_equipo` int(11) NOT NULL,
  `id_tipo_mantenimiento` int(11) NOT NULL,
  `fecha_programada` date NOT NULL,
  `fecha_inicio` datetime,
  `fecha_fin` datetime,
  `estado` enum('programado','en_proceso','completado','cancelado') NOT NULL DEFAULT 'programado',
  `descripcion_trabajo` text,
  `tecnico_responsable` varchar(150),
  `costo_materiales` decimal(10,2) DEFAULT 0.00,
  `costo_mano_obra` decimal(10,2) DEFAULT 0.00,
  `costo_total` decimal(10,2) DEFAULT 0.00,
  `observaciones` text,
  `diagnostico` text,
  `trabajos_realizados` text,
  `repuestos_utilizados` json,
  `proximo_mantenimiento` date,
  `calificacion` enum('excelente','bueno','regular','deficiente') DEFAULT NULL,
  `documentos_adjuntos` json,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `creado_por` int(11),
  `actualizado_por` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_equipo`) REFERENCES `equipos` (`id`),
  FOREIGN KEY (`id_tipo_mantenimiento`) REFERENCES `tipos_mantenimiento` (`id`),
  FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabla: categorias_epp
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categorias_epp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `requiere_certificacion` tinyint(1) DEFAULT 0,
  `vida_util_dias` int(11) DEFAULT 365,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categorias_epp` (`nombre`, `descripcion`, `requiere_certificacion`, `vida_util_dias`) VALUES
('Protección de Cabeza', 'Cascos y gorros de seguridad', 1, 365),
('Protección Respiratoria', 'Mascarillas y equipos respiratorios', 1, 180),
('Protección de Manos', 'Guantes de seguridad', 0, 90),
('Protección de Pies', 'Botas y calzado de seguridad', 1, 365),
('Protección Visual', 'Gafas y caretas de protección', 0, 180),
('Protección Auditiva', 'Tapones y cascos auditivos', 0, 365),
('Ropa de Trabajo', 'Uniformes y ropa de protección', 0, 180),
('Arneses y Cuerdas', 'Equipos para trabajo en altura', 1, 730);

-- --------------------------------------------------------
-- Tabla: epp_items
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `epp_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) NOT NULL UNIQUE,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text,
  `marca` varchar(100),
  `modelo` varchar(100),
  `talla` varchar(20),
  `color` varchar(50),
  `id_categoria` int(11) NOT NULL,
  `stock_minimo` int(11) DEFAULT 5,
  `stock_actual` int(11) DEFAULT 0,
  `stock_maximo` int(11) DEFAULT 100,
  `costo_unitario` decimal(10,2),
  `proveedor` varchar(150),
  `fecha_vencimiento` date,
  `normas_certificacion` varchar(255),
  `ubicacion_almacen` varchar(100),
  `estado` enum('activo','descontinuado','agotado') NOT NULL DEFAULT 'activo',
  `requiere_entrenamiento` tinyint(1) DEFAULT 0,
  `imagen_url` varchar(255),
  `ficha_tecnica_url` varchar(255),
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creado_por` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_categoria`) REFERENCES `categorias_epp` (`id`),
  FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo para EPP
INSERT INTO `epp_items` (`codigo`, `nombre`, `descripcion`, `marca`, `modelo`, `talla`, `id_categoria`, `stock_minimo`, `stock_actual`, `stock_maximo`, `costo_unitario`, `proveedor`, `fecha_vencimiento`, `normas_certificacion`, `ubicacion_almacen`, `creado_por`) VALUES
('CASCO-001', 'Casco de Seguridad Blanco', 'Casco de seguridad industrial color blanco', '3M', 'H-700', 'Única', 1, 10, 25, 50, 45.00, 'Distribuidora 3M Perú', '2026-12-31', 'ANSI Z89.1, OSHA', 'Almacén A-01', 1),
('MASC-001', 'Mascarilla N95', 'Mascarilla de protección respiratoria N95', '3M', '8210', 'Única', 2, 50, 150, 200, 8.50, 'Distribuidora 3M Perú', '2025-06-30', 'NIOSH N95', 'Almacén A-02', 1),
('GUAN-001', 'Guantes de Látex', 'Guantes desechables de látex', 'Ansell', 'TouchNTuff', 'L', 3, 20, 100, 500, 2.50, 'Ansell Perú', '2025-12-31', 'EN 374', 'Almacén A-03', 1),
('BOOT-001', 'Botas de Seguridad', 'Botas con puntera de acero', 'Caterpillar', 'Colorado', '42', 4, 5, 15, 30, 120.00, 'CAT Footwear Perú', '2027-01-31', 'ASTM F2413', 'Almacén B-01', 1);

-- --------------------------------------------------------
-- Tabla: epp_entregas
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `epp_entregas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_entrega` varchar(50) NOT NULL UNIQUE,
  `id_usuario` int(11) NOT NULL,
  `id_epp_item` int(11) NOT NULL,
  `cantidad_entregada` int(11) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `fecha_devolucion_programada` date,
  `fecha_devolucion_real` date,
  `estado` enum('entregado','en_uso','devuelto','perdido','dañado') NOT NULL DEFAULT 'entregado',
  `observaciones_entrega` text,
  `observaciones_devolucion` text,
  `entregado_por` int(11) NOT NULL,
  `recibido_por` int(11),
  `motivo_devolucion` varchar(255),
  `condicion_devolucion` enum('excelente','bueno','regular','malo','destruido'),
  `costo_reposicion` decimal(10,2) DEFAULT 0.00,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`id_epp_item`) REFERENCES `epp_items` (`id`),
  FOREIGN KEY (`entregado_por`) REFERENCES `usuarios` (`id`),
  FOREIGN KEY (`recibido_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Tabla: inspecciones_epp
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `inspecciones_epp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_entrega` int(11) NOT NULL,
  `fecha_inspeccion` date NOT NULL,
  `inspector` varchar(150) NOT NULL,
  `estado_equipo` enum('excelente','bueno','regular','malo','fuera_servicio') NOT NULL,
  `observaciones` text,
  `requiere_reemplazo` tinyint(1) DEFAULT 0,
  `fecha_proximo_control` date,
  `documentos_adjuntos` json,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `creado_por` int(11),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_entrega`) REFERENCES `epp_entregas` (`id`),
  FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Actualizar tabla de módulos con los nuevos módulos
-- --------------------------------------------------------
INSERT INTO `modulos` (`nombre`, `descripcion`, `controlador`, `icono`, `orden`, `estado`) VALUES
('Equipos', 'Gestión de equipos de construcción', 'equipos', 'fa-cogs', 6, 1),
('Mantenimiento', 'Gestión de mantenimientos', 'mantenimiento', 'fa-wrench', 7, 1) 
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- --------------------------------------------------------
-- Actualizar tabla de menú con las nuevas opciones
-- --------------------------------------------------------
INSERT INTO `menu` (`nombre`, `url`, `id_modulo`, `icono`, `orden`, `estado`) VALUES
('Lista de Equipos', 'equipos/lista', 6, 'fa-list', 1, 1),
('Agregar Equipo', 'equipos/agregar', 6, 'fa-plus', 2, 1),
('Categorías de Equipos', 'equipos/categorias', 6, 'fa-tags', 3, 1),
('Mantenimientos Programados', 'mantenimiento/programados', 7, 'fa-calendar', 1, 1),
('Historial Mantenimientos', 'mantenimiento/historial', 7, 'fa-history', 2, 1),
('Crear Mantenimiento', 'mantenimiento/crear', 7, 'fa-plus-circle', 3, 1),
('Gestión de EPP', 'epp/gestion', 3, 'fa-shield', 3, 1),
('Entregas de EPP', 'epp/entregas', 3, 'fa-hand-o-right', 4, 1),
('Inspecciones EPP', 'epp/inspecciones', 3, 'fa-search', 5, 1)
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- --------------------------------------------------------
-- Permisos para los nuevos módulos (Administrador)
-- --------------------------------------------------------
INSERT IGNORE INTO `permisos` (`id_perfil`, `id_menu`, `crear`, `leer`, `actualizar`, `eliminar`)
SELECT 1, m.id, 1, 1, 1, 1 
FROM `menu` m 
WHERE m.id NOT IN (SELECT pm.id_menu FROM `permisos` pm WHERE pm.id_perfil = 1);

-- --------------------------------------------------------
-- Triggers para actualizar stock automáticamente
-- --------------------------------------------------------
DELIMITER $$

CREATE TRIGGER `actualizar_stock_epp_entrega` AFTER INSERT ON `epp_entregas`
FOR EACH ROW
BEGIN
    UPDATE `epp_items` 
    SET `stock_actual` = `stock_actual` - NEW.cantidad_entregada 
    WHERE `id` = NEW.id_epp_item;
END$$

CREATE TRIGGER `actualizar_stock_epp_devolucion` AFTER UPDATE ON `epp_entregas`
FOR EACH ROW
BEGIN
    IF NEW.estado = 'devuelto' AND OLD.estado != 'devuelto' THEN
        UPDATE `epp_items` 
        SET `stock_actual` = `stock_actual` + NEW.cantidad_entregada 
        WHERE `id` = NEW.id_epp_item;
    END IF;
END$$

DELIMITER ;

-- ========================================================
-- Fin de la extensión de base de datos
-- ========================================================
