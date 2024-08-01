-- Actualizar emails de usuarios para el sistema de recuperación
-- SIMAHG - Sistema de Gestión

-- Usuario: admin (Administrador)
-- Email actualizado con Gmail real
UPDATE usuarios SET email = 'lothararbaiza0506@gmail.com' WHERE usuario = 'admin';

-- Opcional: Actualizar otros usuarios con emails válidos
-- Descomenta y modifica según necesites

-- Usuario: jperez (Trabajador)
-- UPDATE usuarios SET email = 'juan.perez@tuempresa.com' WHERE usuario = 'jperez';

-- Usuario: mgarcia (Supervisor)
-- UPDATE usuarios SET email = 'maria.garcia@tuempresa.com' WHERE usuario = 'mgarcia';

-- Usuario: prodriguez (Trabajador)
-- UPDATE usuarios SET email = 'pedro.rodriguez@tuempresa.com' WHERE usuario = 'prodriguez';

-- Verificar cambios
SELECT 
    id,
    usuario,
    CONCAT(nombre, ' ', apellidos) as nombre_completo,
    email,
    telefono,
    CASE 
        WHEN email LIKE '%@gmail.com' OR email LIKE '%@%.%' THEN '✅ Email válido'
        ELSE '⚠️ Email de prueba'
    END as estado_email
FROM usuarios
ORDER BY id;
