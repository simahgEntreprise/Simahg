-- ========================================
-- EXTENSIONES ADICIONALES PARA SIMAHG
-- Módulo de Solicitudes de EPPs
-- Compatible con estructura existente
-- ========================================

USE `simahg_db`;

-- Tabla de solicitudes de EPPs (usa epp_items que ya existe)
CREATE TABLE IF NOT EXISTS `solicitudes_epp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario que solicita el EPP',
  `id_epp` int(11) NOT NULL COMMENT 'EPP solicitado (referencia a epp_items)',
  `cantidad` int(11) NOT NULL,
  `justificacion` text NOT NULL,
  `estado` enum('PENDIENTE','APROBADA','RECHAZADA','ENTREGADA') NOT NULL DEFAULT 'PENDIENTE',
  `id_aprobador` int(11) DEFAULT NULL COMMENT 'Usuario que aprueba/rechaza',
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_aprobacion` timestamp NULL DEFAULT NULL,
  `fecha_entrega` timestamp NULL DEFAULT NULL,
  `motivo_rechazo` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_solicitudes_usuario` (`id_usuario`),
  KEY `idx_solicitudes_epp` (`id_epp`),
  KEY `idx_solicitudes_estado` (`estado`),
  CONSTRAINT `fk_solicitudes_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_solicitudes_epp` FOREIGN KEY (`id_epp`) REFERENCES `epp_items` (`id`),
  CONSTRAINT `fk_solicitudes_aprobador` FOREIGN KEY (`id_aprobador`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo para solicitudes (opcional - verificar que existan usuarios)
INSERT IGNORE INTO `solicitudes_epp` (`id_usuario`, `id_epp`, `cantidad`, `justificacion`, `estado`, `fecha_solicitud`) 
SELECT 
    u.id, 
    e.id, 
    2, 
    'Solicitud de prueba para validación del sistema', 
    'PENDIENTE', 
    NOW()
FROM usuarios u, epp_items e 
WHERE u.id_perfil = 4 AND e.id = (SELECT MIN(id) FROM epp_items)
LIMIT 1;

-- Tabla para historial de entregas (usa estructura existente)
CREATE TABLE IF NOT EXISTS `historial_entregas_epp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_epp` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_entrega` timestamp NOT NULL DEFAULT current_timestamp(),
  `entregado_por` int(11) NOT NULL COMMENT 'ID del usuario que hizo la entrega',
  `firma_trabajador` text DEFAULT NULL COMMENT 'Firma digital o confirmación',
  `observaciones` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_historial_solicitud` (`id_solicitud`),
  KEY `idx_historial_usuario` (`id_usuario`),
  CONSTRAINT `fk_historial_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitudes_epp` (`id`),
  CONSTRAINT `fk_historial_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `fk_historial_epp` FOREIGN KEY (`id_epp`) REFERENCES `epp_items` (`id`),
  CONSTRAINT `fk_historial_entregador` FOREIGN KEY (`entregado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para notificaciones/alertas
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL COMMENT 'Usuario destinatario',
  `tipo` enum('SOLICITUD_PENDIENTE','MANTENIMIENTO_PROXIMO','STOCK_BAJO','CERTIFICACION_VENCE','GENERAL') NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `url` varchar(255) DEFAULT NULL COMMENT 'URL relacionada',
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notificaciones_usuario` (`id_usuario`, `leida`),
  CONSTRAINT `fk_notificaciones_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
