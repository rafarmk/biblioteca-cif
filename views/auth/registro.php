-- ============================================
-- SCRIPT DE CREACIÓN DE BASE DE DATOS
-- Sistema de Registro de Estudiantes
-- ============================================

DROP DATABASE IF EXISTS sistema_estudiantes;

CREATE DATABASE sistema_estudiantes 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE sistema_estudiantes;

CREATE TABLE estudiantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad INT NOT NULL,
    carrera VARCHAR(50) NOT NULL,
    semestre INT NOT NULL,
    promedio DECIMAL(3,1) NOT NULL,
    estado_academico VARCHAR(30) NOT NULL,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    activo BOOLEAN DEFAULT TRUE,
    CHECK (edad >= 15 AND edad <= 60),
    CHECK (semestre >= 1 AND semestre <= 12),
    CHECK (promedio >= 0 AND promedio <= 10),
    INDEX idx_nombre (nombre),
    INDEX idx_carrera (carrera),
    INDEX idx_promedio (promedio),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO estudiantes (nombre, edad, carrera, semestre, promedio, estado_academico) VALUES
('Juan Pérez García', 20, 'Ingeniería', 3, 8.5, 'Muy Bueno'),
('María López Sánchez', 22, 'Medicina', 5, 9.2, 'Excelente'),
('Carlos Rodríguez Mora', 19, 'Derecho', 2, 7.8, 'Bueno'),
('Ana Martínez Cruz', 21, 'Administración', 4, 9.5, 'Excelente'),
('Luis Hernández Ruiz', 23, 'Contabilidad', 6, 8.0, 'Muy Bueno'),
('Laura González Díaz', 20, 'Ingeniería', 3, 9.8, 'Excelente'),
('Pedro Ramírez Torres', 24, 'Medicina', 7, 7.2, 'Bueno'),
('Isabel Flores Mendoza', 19, 'Derecho', 1, 8.8, 'Muy Bueno'),
('Roberto Morales Castro', 22, 'Administración', 5, 6.5, 'Regular'),
('Carmen Vargas Ortiz', 21, 'Contabilidad', 4, 9.0, 'Excelente');

SELECT 'Base de datos creada exitosamente' AS mensaje;
SELECT COUNT(*) AS registros_insertados FROM estudiantes;