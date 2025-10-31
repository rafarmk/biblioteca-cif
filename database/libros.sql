CREATE TABLE IF NOT EXISTS `libros` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `editorial` varchar(100) DEFAULT NULL,
  `año_publicacion` int(4) DEFAULT NULL,
  `categoria` varchar(50) NOT NULL,
  `num_paginas` int(11) DEFAULT NULL,
  `idioma` varchar(50) DEFAULT 'Español',
  `cantidad_total` int(11) NOT NULL DEFAULT 1,
  `cantidad_disponible` int(11) NOT NULL DEFAULT 1,
  `ubicacion` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `portada_url` varchar(500) DEFAULT NULL,
  `estado` enum('activo','inactivo','mantenimiento') DEFAULT 'activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `isbn_unique` (`isbn`),
  KEY `idx_categoria` (`categoria`),
  KEY `idx_estado` (`estado`),
  KEY `idx_autor` (`autor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `libros` (`titulo`, `autor`, `isbn`, `editorial`, `año_publicacion`, `categoria`, `num_paginas`, `idioma`, `cantidad_total`, `cantidad_disponible`, `ubicacion`, `descripcion`, `estado`) VALUES
('Cien años de soledad', 'Gabriel García Márquez', '978-0307474728', 'Editorial Sudamericana', 1967, 'Ficción', 417, 'Español', 5, 5, 'Estante A-12', 'Una de las obras más importantes de la literatura latinoamericana.', 'activo'),
('El Quijote', 'Miguel de Cervantes', '978-8420412146', 'Real Academia Española', 1605, 'Clásicos', 863, 'Español', 3, 3, 'Estante B-05', 'La novela más influyente de la literatura española.', 'activo'),
('1984', 'George Orwell', '978-0451524935', 'Signet Classics', 1949, 'Ciencia Ficción', 328, 'Español', 4, 4, 'Estante C-18', 'Novela distópica que explora temas de totalitarismo y vigilancia.', 'activo');

CREATE TABLE IF NOT EXISTS `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `color` varchar(20) DEFAULT '#667eea',
  `icono` varchar(50) DEFAULT 'book',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_unique` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `categorias` (`nombre`, `descripcion`, `color`, `icono`) VALUES
('Ficción', 'Novelas y cuentos literarios', '#667eea', 'book-open'),
('Ciencia Ficción', 'Literatura de ciencia ficción y fantasía', '#764ba2', 'rocket'),
('Historia', 'Libros sobre acontecimientos históricos', '#f56565', 'clock'),
('Tecnología', 'Programación, informática y tecnología', '#48bb78', 'laptop');