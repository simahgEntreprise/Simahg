-- Tabla para códigos de recuperación de contraseña
-- Sistema SIMAHG - Recuperación de contraseña por EMAIL y SMS

CREATE TABLE IF NOT EXISTS codigos_recuperacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    codigo VARCHAR(6) NOT NULL,
    metodo ENUM('email', 'sms') NOT NULL,
    expiracion DATETIME NOT NULL,
    usado TINYINT(1) DEFAULT 0,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario_codigo (usuario_id, codigo),
    INDEX idx_expiracion (expiracion),
    UNIQUE KEY unique_usuario_activo (usuario_id, usado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Agregar columna de teléfono a usuarios si no existe
ALTER TABLE usuarios 
ADD COLUMN IF NOT EXISTS telefono VARCHAR(20) DEFAULT NULL AFTER email;

-- Actualizar usuarios de ejemplo con teléfonos (opcional)
UPDATE usuarios SET telefono = '+51987654321' WHERE usuario = 'admin';
UPDATE usuarios SET telefono = '+51912345678' WHERE usuario = 'supervisor';

-- Limpiar códigos expirados (puedes ejecutar esto periódicamente)
DELETE FROM codigos_recuperacion WHERE expiracion < NOW() OR usado = 1;

SELECT 'Tabla codigos_recuperacion creada correctamente' AS status;
