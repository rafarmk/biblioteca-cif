-- Actualizar tabla libros con todas las columnas necesarias

-- Verificar y agregar columnas si no existen
ALTER TABLE libros 
ADD COLUMN IF NOT EXISTS isbn VARCHAR(20) NULL AFTER autor,
ADD COLUMN IF NOT EXISTS editorial VARCHAR(150) NULL AFTER isbn,
ADD COLUMN IF NOT EXISTS anio_publicacion INT NULL AFTER editorial,
ADD COLUMN IF NOT EXISTS categoria VARCHAR(100) NULL AFTER anio_publicacion,
ADD COLUMN IF NOT EXISTS ubicacion VARCHAR(100) NULL AFTER categoria,
ADD COLUMN IF NOT EXISTS cantidad_total INT DEFAULT 1 AFTER ubicacion,
ADD COLUMN IF NOT EXISTS cantidad_disponible INT DEFAULT 1 AFTER cantidad_total,
ADD COLUMN IF NOT EXISTS descripcion TEXT NULL AFTER cantidad_disponible,
ADD COLUMN IF NOT EXISTS estado ENUM('disponible', 'prestado', 'no_disponible') DEFAULT 'disponible' AFTER descripcion,
ADD COLUMN IF NOT EXISTS fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP AFTER estado;

-- Verificar la estructura actualizada
DESCRIBE libros;

SELECT ' Tabla libros actualizada correctamente' AS mensaje;
