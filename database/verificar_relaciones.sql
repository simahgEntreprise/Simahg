-- ============================================================================
-- Script para VERIFICAR el Estado de las Relaciones en SIMAHG
-- ============================================================================
-- Ejecutar en phpMyAdmin para diagnosticar el problema
-- ============================================================================

USE `simahg_db`;

-- ============================================================================
-- 1. VERIFICAR FOREIGN KEYS EXISTENTES
-- ============================================================================
SELECT 
    '=== FOREIGN KEYS ACTUALES ===' AS Info;

SELECT 
    TABLE_NAME AS 'Tabla',
    COLUMN_NAME AS 'Columna',
    CONSTRAINT_NAME AS 'Nombre Constraint',
    REFERENCED_TABLE_NAME AS 'Tabla Referencias',
    REFERENCED_COLUMN_NAME AS 'Columna Referenciada'
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_SCHEMA = 'simahg_db'
    AND TABLE_SCHEMA = 'simahg_db'
ORDER BY
    TABLE_NAME;

-- ============================================================================
-- 2. VERIFICAR MOTORES DE TABLAS (deben ser InnoDB para FK)
-- ============================================================================
SELECT 
    '=== MOTORES DE TABLAS ===' AS Info;

SELECT 
    TABLE_NAME AS 'Tabla',
    ENGINE AS 'Motor',
    CASE 
        WHEN ENGINE = 'InnoDB' THEN '✅ OK'
        ELSE '❌ Cambiar a InnoDB'
    END AS 'Estado'
FROM 
    INFORMATION_SCHEMA.TABLES
WHERE 
    TABLE_SCHEMA = 'simahg_db'
    AND TABLE_TYPE = 'BASE TABLE'
ORDER BY
    TABLE_NAME;

-- ============================================================================
-- 3. VERIFICAR ÍNDICES (necesarios para FK)
-- ============================================================================
SELECT 
    '=== ÍNDICES EN TABLAS ===' AS Info;

SELECT 
    TABLE_NAME AS 'Tabla',
    INDEX_NAME AS 'Índice',
    COLUMN_NAME AS 'Columna',
    INDEX_TYPE AS 'Tipo'
FROM 
    INFORMATION_SCHEMA.STATISTICS
WHERE 
    TABLE_SCHEMA = 'simahg_db'
    AND INDEX_NAME != 'PRIMARY'
ORDER BY
    TABLE_NAME,
    INDEX_NAME;

-- ============================================================================
-- 4. CONTAR RELACIONES ESPERADAS vs EXISTENTES
-- ============================================================================
SELECT 
    '=== RESUMEN ===' AS Info;

SELECT 
    'Relaciones Esperadas' AS Descripcion,
    '4' AS Cantidad,
    '(usuarios->perfiles, menu->modulos, permisos->perfiles, permisos->menu)' AS Detalle
UNION ALL
SELECT 
    'Relaciones Encontradas' AS Descripcion,
    COUNT(*) AS Cantidad,
    GROUP_CONCAT(CONSTRAINT_NAME SEPARATOR ', ') AS Detalle
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_SCHEMA = 'simahg_db'
    AND TABLE_SCHEMA = 'simahg_db';

-- ============================================================================
-- 5. DIAGNÓSTICO FINAL
-- ============================================================================
SELECT 
    '=== DIAGNÓSTICO ===' AS Info;

SELECT 
    CASE 
        WHEN COUNT(*) = 0 THEN '❌ NO hay Foreign Keys creadas. Ejecuta fix_relaciones.sql'
        WHEN COUNT(*) < 4 THEN '⚠️ Faltan algunas Foreign Keys. Ejecuta fix_relaciones.sql'
        WHEN COUNT(*) >= 4 THEN '✅ Todas las Foreign Keys están creadas correctamente'
    END AS Diagnostico
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_SCHEMA = 'simahg_db'
    AND TABLE_SCHEMA = 'simahg_db';
