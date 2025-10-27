-- Actualizar tabla libros (MySQL 5.7+)

-- Agregar columnas una por una (ignorar errores si ya existen)
ALTER TABLE libros ADD COLUMN isbn VARCHAR(20) NULL AFTER autor;
ALTER TABLE libros ADD COLUMN editorial VARCHAR(150) NULL AFTER isbn;
ALTER TABLE libros ADD COLUMN anio_publicacion INT NULL AFTER editorial;
ALTER TABLE libros ADD COLUMN categoria VARCHAR(100) NULL AFTER anio_publicacion;
ALTER TABLE libros ADD COLUMN ubicacion VARCHAR(100) NULL AFTER categoria;
ALTER TABLE libros ADD COLUMN cantidad_total INT DEFAULT 1 AFTER ubicacion;
ALTER TABLE libros ADD COLUMN cantidad_disponible INT DEFAULT 1 AFTER cantidad_total;
ALTER TABLE libros ADD COLUMN descripcion TEXT NULL AFTER cantidad_disponible;
ALTER TABLE libros ADD COLUMN estado ENUM('disponible', 'prestado', 'no_disponible') DEFAULT 'disponible' AFTER descripcion;
ALTER TABLE libros ADD COLUMN fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP AFTER estado;

-- Verificar estructura
DESCRIBE libros;
