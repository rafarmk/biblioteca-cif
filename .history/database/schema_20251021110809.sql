-- =====================================================
-- Sistema Biblioteca CIF - Esquema de Base de Datos
-- =====================================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS biblioteca_cif_usuarios 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE biblioteca_cif_usuarios;

-- =====================================================
-- Tabla: usuarios
-- =====================================================
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    tipo_usuario ENUM('policia', 'administrativo', 'estudiante') NOT NULL,
    carnet_policial VARCHAR(50) NULL,
    documento_identidad VARCHAR(50) NULL,
    token_temporal VARCHAR(50) NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_tipo_usuario (tipo_usuario),
    INDEX idx_carnet (carnet_policial)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Tabla: libros
-- =====================================================
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    isbn VARCHAR(20) NULL,
    editorial VARCHAR(100) NULL,
    anio_publicacion YEAR NULL,
    categoria VARCHAR(50) NULL,
    cantidad_disponible INT DEFAULT 1,
    ubicacion VARCHAR(100) NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_titulo (titulo),
    INDEX idx_autor (autor),
    INDEX idx_isbn (isbn),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Usuario administrador por defecto
-- Usuario: admin
-- Password: password (hasheado con PASSWORD_DEFAULT)
-- =====================================================
INSERT INTO usuarios (nombre, email, password, tipo_usuario) 
VALUES (
    'Administrador', 
    'admin', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    'administrativo'
) ON DUPLICATE KEY UPDATE email=email;

-- =====================================================
-- Datos de ejemplo para libros (opcional)
-- Puedes comentar o eliminar estos INSERT si no los necesitas
-- =====================================================
INSERT INTO libros (titulo, autor, isbn, editorial, anio_publicacion, categoria, cantidad_disponible, ubicacion) VALUES
('Derecho Penal Salvadoreño', 'Juan Pérez', '978-1234567890', 'Editorial Jurídica', 2020, 'Derecho', 5, 'Estante A1'),
('Criminología Moderna', 'María González', '978-0987654321', 'Editorial Académica', 2021, 'Criminología', 3, 'Estante B2'),
('Procedimientos Policiales', 'Carlos Martínez', '978-1122334455', 'Editorial Policial', 2022, 'Procedimientos', 4, 'Estante C3')
ON DUPLICATE KEY UPDATE isbn=isbn;