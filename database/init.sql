-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS biblioteca_cif CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE biblioteca_cif;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20),
    dui VARCHAR(20),
    direccion TEXT,
    tipo_usuario ENUM('administrador', 'personal_administrativo', 'personal_operativo', 'estudiante_mayor', 'estudiante_menor', 'visitante') NOT NULL,
    estado ENUM('activo', 'inactivo', 'pendiente') DEFAULT 'pendiente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de libros
CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    autor VARCHAR(150) NOT NULL,
    isbn VARCHAR(50),
    editorial VARCHAR(100),
    anio_publicacion YEAR,
    categoria VARCHAR(50),
    ubicacion VARCHAR(100),
    cantidad_total INT DEFAULT 1,
    cantidad_disponible INT DEFAULT 1,
    descripcion TEXT,
    imagen_portada VARCHAR(255),
    estado ENUM('disponible', 'prestado', 'mantenimiento') DEFAULT 'disponible',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de préstamos
CREATE TABLE IF NOT EXISTS prestamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libro_id INT NOT NULL,
    usuario_id INT NOT NULL,
    fecha_prestamo DATE NOT NULL,
    fecha_devolucion_esperada DATE NOT NULL,
    fecha_devolucion_real DATE,
    estado ENUM('activo', 'devuelto', 'atrasado') DEFAULT 'activo',
    observaciones TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (nombre, email, password, tipo_usuario, estado) 
VALUES ('Administrador', 'admin@biblioteca.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'administrador', 'activo')
ON DUPLICATE KEY UPDATE id=id;
-- Password: password

-- Insertar algunos libros de ejemplo
INSERT INTO libros (titulo, autor, isbn, categoria, cantidad_total, cantidad_disponible) VALUES
('Cien años de soledad', 'Gabriel García Márquez', '978-0307474728', 'Ficción', 3, 3),
('Don Quijote de la Mancha', 'Miguel de Cervantes', '978-8420412146', 'Clásicos', 2, 2),
('El principito', 'Antoine de Saint-Exupéry', '978-0156012195', 'Infantil', 5, 5),
('1984', 'George Orwell', '978-0451524935', 'Ficción', 3, 3),
('Crónica de una muerte anunciada', 'Gabriel García Márquez', '978-0307387509', 'Ficción', 2, 2)
ON DUPLICATE KEY UPDATE id=id;