-- ============================================================================
-- Script para VERIFICAR y RECREAR las Relaciones (FOREIGN KEYS) en SIMAHG
-- ============================================================================
-- Ejecutar este archivo en phpMyAdmin para corregir las relaciones
-- ============================================================================

USE `simahg_db`;

-- ============================================================================
-- PASO 1: ELIMINAR FOREIGN KEYS EXISTENTES (si existen)
-- ============================================================================
-- Esto evita errores de "constraint ya existe"

SET FOREIGN_KEY_CHECKS = 0;

-- Eliminar FK de tabla usuarios (si existe)
ALTER TABLE `usuarios` DROP FOREIGN KEY IF EXISTS `usuarios_ibfk_1`;
ALTER TABLE `usuarios` DROP FOREIGN KEY IF EXISTS `fk_usuarios_perfiles`;

-- Eliminar FK de tabla menu (si existe)
ALTER TABLE `menu` DROP FOREIGN KEY IF EXISTS `menu_ibfk_1`;
ALTER TABLE `menu` DROP FOREIGN KEY IF EXISTS `fk_menu_modulos`;

-- Eliminar FK de tabla permisos (si existen)
ALTER TABLE `permisos` DROP FOREIGN KEY IF EXISTS `permisos_ibfk_1`;
ALTER TABLE `permisos` DROP FOREIGN KEY IF EXISTS `permisos_ibfk_2`;
ALTER TABLE `permisos` DROP FOREIGN KEY IF EXISTS `fk_permisos_perfiles`;
ALTER TABLE `permisos` DROP FOREIGN KEY IF EXISTS `fk_permisos_menu`;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- PASO 2: RECREAR FOREIGN KEYS CON NOMBRES EXPLÍCITOS
-- ============================================================================

-- Relación: usuarios -> perfiles
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_perfiles` 
  FOREIGN KEY (`id_perfil`) 
  REFERENCES `perfiles` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- Relación: menu -> modulos
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_menu_modulos` 
  FOREIGN KEY (`id_modulo`) 
  REFERENCES `modulos` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;

-- Relación: permisos -> perfiles
ALTER TABLE `permisos`
  ADD CONSTRAINT `fk_permisos_perfiles` 
  FOREIGN KEY (`id_perfil`) 
  REFERENCES `perfiles` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

-- Relación: permisos -> menu
ALTER TABLE `permisos`
  ADD CONSTRAINT `fk_permisos_menu` 
  FOREIGN KEY (`id_menu`) 
  REFERENCES `menu` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

-- ============================================================================
-- PASO 3: VERIFICAR QUE LAS RELACIONES SE CREARON CORRECTAMENTE
-- ============================================================================

-- Consulta para ver todas las FOREIGN KEYS creadas
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_SCHEMA = 'simahg_db'
    AND TABLE_SCHEMA = 'simahg_db'
ORDER BY
    TABLE_NAME,
    COLUMN_NAME;

-- ============================================================================
-- MENSAJE DE CONFIRMACIÓN
-- ============================================================================
SELECT 'Relaciones (FOREIGN KEYS) recreadas exitosamente! ✅' AS Status;
SELECT 'Ahora puedes ir al Diseñador de phpMyAdmin y verás las relaciones.' AS Instruccion;

-- ============================================================================
-- NOTAS IMPORTANTES:
-- ============================================================================
-- ON DELETE RESTRICT: No permite eliminar un registro si tiene dependencias
-- ON DELETE CASCADE: Elimina automáticamente los registros dependientes
-- ON UPDATE CASCADE: Actualiza automáticamente las referencias cuando cambia la clave
-- ============================================================================
