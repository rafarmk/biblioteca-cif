-- Permitir que el campo email sea NULL
ALTER TABLE usuarios MODIFY COLUMN email VARCHAR(150) NULL;

-- Verificar estructura
DESCRIBE usuarios;
