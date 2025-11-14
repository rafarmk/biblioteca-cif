-- Base de Datos: Sistema Biblioteca CIF
-- Creación completa con todas las tablas necesarias

-- Eliminar tablas en orden correcto (respetando foreign keys)
DROP TABLE IF EXISTS prestamos;
DROP TABLE IF EXISTS multas;
DROP TABLE IF EXISTS reservas;
DROP TABLE IF EXISTS libros;
DROP TABLE IF EXISTS categorias;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS administradores;

-- Tabla: categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: usuarios (con todos los campos necesarios)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    dui VARCHAR(10),
    direccion TEXT,
    tipo_usuario ENUM('administrador', 'gestionador', 'personal_administrativo', 'personal_operativo', 'estudiante_mayor', 'estudiante_menor', 'visitante') NOT NULL DEFAULT 'estudiante_mayor',
    password VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'pendiente', 'inactivo', 'suspendido') NOT NULL DEFAULT 'pendiente',
    puede_prestar BOOLEAN DEFAULT FALSE,
    dias_max_prestamo INT DEFAULT 7,
    max_libros_simultaneos INT DEFAULT 3,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_tipo_usuario (tipo_usuario),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: libros
CREATE TABLE libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(20) UNIQUE,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    editorial VARCHAR(255),
    categoria_id INT,
    anio_publicacion YEAR,
    num_paginas INT,
    idioma VARCHAR(50) DEFAULT 'Español',
    descripcion TEXT,
    ubicacion VARCHAR(100),
    cantidad_total INT NOT NULL DEFAULT 1,
    cantidad_disponible INT NOT NULL DEFAULT 1,
    estado ENUM('disponible', 'prestado', 'en_reparacion', 'perdido') DEFAULT 'disponible',
    imagen_portada VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    INDEX idx_titulo (titulo),
    INDEX idx_autor (autor),
    INDEX idx_isbn (isbn),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: prestamos
CREATE TABLE prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    fecha_prestamo TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_devolucion_esperada DATE NOT NULL,
    fecha_devolucion_real TIMESTAMP NULL,
    estado ENUM('activo', 'devuelto', 'vencido', 'perdido') DEFAULT 'activo',
    notas TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_libro (libro_id),
    INDEX idx_estado (estado),
    INDEX idx_fecha_prestamo (fecha_prestamo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: multas
CREATE TABLE multas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestamo_id INT NOT NULL,
    usuario_id INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    dias_retraso INT NOT NULL,
    estado ENUM('pendiente', 'pagada', 'condonada') DEFAULT 'pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_pago TIMESTAMP NULL,
    FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla: reservas
CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    libro_id INT NOT NULL,
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activa', 'cumplida', 'cancelada', 'expirada') DEFAULT 'activa',
    fecha_expiracion DATE NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id),
    INDEX idx_libro (libro_id),
    INDEX idx_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================
-- DATOS INICIALES
-- ========================================

-- Insertar categorías básicas
INSERT INTO categorias (nombre, descripcion) VALUES
('Ficción', 'Novelas y cuentos de ficción'),
('No Ficción', 'Libros informativos y educativos'),
('Ciencia', 'Libros de ciencias naturales y exactas'),
('Historia', 'Libros de historia y biografías'),
('Tecnología', 'Libros de informática y tecnología'),
('Arte', 'Libros de arte, música y cultura'),
('Infantil', 'Libros para niños'),
('Juvenil', 'Libros para jóvenes'),
('Derecho', 'Libros de leyes y derecho'),
('Criminología', 'Libros de criminología y criminalística');

-- Insertar usuario administrador por defecto
-- Email: admin@biblioteca.com
-- Contraseña: admin123
INSERT INTO usuarios (nombre, email, tipo_usuario, password, estado, puede_prestar, dias_max_prestamo, max_libros_simultaneos) 
VALUES ('Administrador del Sistema', 'admin@biblioteca.com', 'administrador', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo', TRUE, 30, 10);

-- Insertar usuarios de ejemplo
INSERT INTO usuarios (nombre, email, telefono, dui, direccion, tipo_usuario, password, estado, puede_prestar) VALUES
('Juan Pérez', 'juan.perez@email.com', '7890-1234', '12345678-9', 'San Salvador', 'estudiante_mayor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo', TRUE),
('María González', 'maria.gonzalez@email.com', '7890-5678', '98765432-1', 'Santa Ana', 'estudiante_mayor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'activo', TRUE);

-- Insertar libros de ejemplo
INSERT INTO libros (isbn, titulo, autor, editorial, categoria_id, anio_publicacion, num_paginas, descripcion, cantidad_total, cantidad_disponible, ubicacion) VALUES
('978-0-06-112008-4', 'Cien años de soledad', 'Gabriel García Márquez', 'Editorial Sudamericana', 1, 1967, 471, 'Obra maestra del realismo mágico', 3, 3, 'A1'),
('978-0-14-118280-3', 'El Principito', 'Antoine de Saint-Exupéry', 'Editorial Planeta', 7, 1943, 96, 'Clásico de la literatura infantil', 5, 5, 'A2'),
('978-84-376-0494-7', 'Don Quijote de la Mancha', 'Miguel de Cervantes', 'Editorial Espasa', 1, 1605, 1023, 'Obra cumbre de la literatura española', 2, 2, 'A3'),
('978-1234567890', 'Derecho Penal', 'Juan Pérez López', 'Editorial Jurídica', 9, 2020, 450, 'Manual de derecho penal', 5, 5, 'B1'),
('978-0987654321', 'Criminología Moderna', 'María González', 'Editorial Académica', 10, 2021, 380, 'Fundamentos de criminología', 3, 3, 'B2');

-- Vista para estadísticas rápidas
CREATE OR REPLACE VIEW v_estadisticas AS
SELECT 
    (SELECT COUNT(*) FROM libros) as total_libros,
    (SELECT COUNT(*) FROM usuarios WHERE estado = 'activo') as total_usuarios_activos,
    (SELECT COUNT(*) FROM prestamos WHERE estado = 'activo') as prestamos_activos,
    (SELECT COUNT(*) FROM prestamos WHERE estado = 'vencido') as prestamos_vencidos,
    (SELECT SUM(cantidad_disponible) FROM libros) as libros_disponibles;