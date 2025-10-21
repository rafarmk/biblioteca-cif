-- =====================================================
-- Base de Datos: Sistema Biblioteca CIF
-- =====================================================

CREATE DATABASE IF NOT EXISTS bibloteca_cif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bibloteca_cif;

-- =====================================================
-- Tabla: usuarios
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: libros
-- =====================================================
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    editorial VARCHAR(100),
    anio_publicacion YEAR,
    categoria VARCHAR(50),
    cantidad_disponible INT DEFAULT 1,
    ubicacion VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_titulo (titulo),
    INDEX idx_autor (autor),
    INDEX idx_isbn (isbn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: prestamos
-- =====================================================
CREATE TABLE IF NOT EXISTS prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_esperada DATE NOT NULL,
    fecha_devolucion_real DATE NULL,
    estado ENUM('activo', 'devuelto', 'atrasado') DEFAULT 'activo',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    INDEX idx_estado (estado),
    INDEX idx_usuario (usuario_id),
    INDEX idx_libro (libro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Datos de ejemplo (opcional)
-- =====================================================

-- Usuarios de ejemplo
INSERT INTO usuarios (nombre, email, telefono, direccion) VALUES
('Juan Pérez', 'juan.perez@email.com', '7890-1234', 'San Salvador, El Salvador'),
('María González', 'maria.gonzalez@email.com', '7890-5678', 'Santa Ana, El Salvador')
ON DUPLICATE KEY UPDATE email=email;

-- Libros de ejemplo
INSERT INTO libros (titulo, autor, isbn, editorial, anio_publicacion, categoria, cantidad_disponible, ubicacion) VALUES
('Derecho Penal Salvadoreño', 'Juan Pérez', '978-1234567890', 'Editorial Jurídica', 2020, 'Derecho', 5, 'Estante A1'),
('Criminología Moderna', 'María González', '978-0987654321', 'Editorial Académica', 2021, 'Criminología', 3,

@"
-- =====================================================
-- Base de Datos: Sistema Biblioteca CIF
-- =====================================================

CREATE DATABASE IF NOT EXISTS bibloteca_cif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bibloteca_cif;

-- =====================================================
-- Tabla: usuarios
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: libros
-- =====================================================
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    editorial VARCHAR(100),
    anio_publicacion YEAR,
    categoria VARCHAR(50),
    cantidad_disponible INT DEFAULT 1,
    ubicacion VARCHAR(50),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_titulo (titulo),
    INDEX idx_autor (autor),
    INDEX idx_isbn (isbn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: prestamos
-- =====================================================
CREATE TABLE IF NOT EXISTS prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_esperada DATE NOT NULL,
    fecha_devolucion_real DATE NULL,
    estado ENUM('activo', 'devuelto', 'atrasado') DEFAULT 'activo',
    notas TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    INDEX idx_estado (estado),
    INDEX idx_usuario (usuario_id),
    INDEX idx_libro (libro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Datos de ejemplo (opcional)
-- =====================================================

-- Usuarios de ejemplo
INSERT INTO usuarios (nombre, email, telefono, direccion) VALUES
('Juan Pérez', 'juan.perez@email.com', '7890-1234', 'San Salvador, El Salvador'),
('María González', 'maria.gonzalez@email.com', '7890-5678', 'Santa Ana, El Salvador')
ON DUPLICATE KEY UPDATE email=email;

-- Libros de ejemplo
INSERT INTO libros (titulo, autor, isbn, editorial, anio_publicacion, categoria, cantidad_disponible, ubicacion) VALUES
('Derecho Penal Salvadoreño', 'Juan Pérez', '978-1234567890', 'Editorial Jurídica', 2020, 'Derecho', 5, 'Estante A1'),
('Criminología Moderna', 'María González', '978-0987654321', 'Editorial Académica', 2021, 'Criminología', 3, 'Estante B2'),
('Procedimientos Policiales', 'Carlos Martínez', '978-1122334455', 'Editorial Policial', 2022, 'Procedimientos', 4, 'Estante C3')
ON DUPLICATE KEY UPDATE isbn=isbn;
