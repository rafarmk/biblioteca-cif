-- Base de Datos: Sistema Biblioteca CIF
CREATE DATABASE IF NOT EXISTS bibloteca_cif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bibloteca_cif;

-- Eliminar tablas si existen
DROP TABLE IF EXISTS prestamos;
DROP TABLE IF EXISTS libros;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS administradores;

-- Tabla: administradores
CREATE TABLE administradores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'bibliotecario') DEFAULT 'bibliotecario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: libros
CREATE TABLE libros (
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

-- Tabla: prestamos
CREATE TABLE prestamos (
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

-- Insertar administrador por defecto (password: admin123)
INSERT INTO administradores (nombre, email, password, rol) VALUES
('Administrador', 'admin@cif.edu.sv', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Datos de ejemplo
INSERT INTO usuarios (nombre, email, telefono, direccion) VALUES
('Juan Perez', 'juan.perez@email.com', '7890-1234', 'San Salvador'),
('Maria Gonzalez', 'maria.gonzalez@email.com', '7890-5678', 'Santa Ana');

INSERT INTO libros (titulo, autor, isbn, editorial, anio_publicacion, categoria, cantidad_disponible, ubicacion) VALUES
('Derecho Penal', 'Juan Perez', '978-1234567890', 'Editorial Juridica', 2020, 'Derecho', 5, 'A1'),
('Criminologia Moderna', 'Maria Gonzalez', '978-0987654321', 'Editorial Academica', 2021, 'Criminología', 3, 'B2');