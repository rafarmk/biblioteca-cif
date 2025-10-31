-- Limpiar tabla de libros
DELETE FROM libros;

-- Insertar 10 libros de prueba
INSERT INTO libros (titulo, autor, isbn, editorial, anio_publicacion, categoria, ubicacion, cantidad_total, cantidad_disponible, descripcion, estado) VALUES
('Cien a?os de soledad', 'Gabriel Garc?a M?rquez', '978-0307474728', 'Editorial Sudamericana', 1967, 'Ficci?n', 'Estante A1', 3, 3, 'Obra maestra del realismo m?gico', 'disponible'),
('Don Quijote de la Mancha', 'Miguel de Cervantes', '978-8420412146', 'Editorial Alfaguara', 2005, 'Cl?sico', 'Estante A2', 5, 5, 'La obra cumbre de la literatura espa?ola', 'disponible'),
('1984', 'George Orwell', '978-0451524935', 'Signet Classic', 1949, 'Ciencia Ficci?n', 'Estante B1', 4, 4, 'Distop?a sobre un futuro totalitario', 'disponible'),
('El principito', 'Antoine de Saint-Exup?ry', '978-0156012195', 'Harcourt', 1943, 'Infantil', 'Estante C1', 6, 6, 'F?bula po?tica sobre la amistad', 'disponible'),
('Cr?nica de una muerte anunciada', 'Gabriel Garc?a M?rquez', '978-0307387387', 'Vintage Espa?ol', 1981, 'Ficci?n', 'Estante A1', 2, 2, 'Novela corta sobre un asesinato', 'disponible'),
('El c?digo Da Vinci', 'Dan Brown', '978-0307474278', 'Doubleday', 2003, 'Suspenso', 'Estante B2', 3, 3, 'Thriller sobre conspiraciones', 'disponible'),
('Harry Potter y la piedra filosofal', 'J.K. Rowling', '978-8478884452', 'Salamandra', 1997, 'Fantas?a', 'Estante C2', 5, 5, 'Primera aventura del joven mago', 'disponible'),
('Sapiens: De animales a dioses', 'Yuval Noah Harari', '978-0062316097', 'Harper', 2011, 'Historia', 'Estante D1', 4, 4, 'Breve historia de la humanidad', 'disponible'),
('El alquimista', 'Paulo Coelho', '978-0062315007', 'HarperOne', 1988, 'Autoayuda', 'Estante D2', 3, 3, 'F?bula sobre seguir los sue?os', 'disponible'),
('La sombra del viento', 'Carlos Ruiz Zaf?n', '978-0143034902', 'Penguin Books', 2001, 'Misterio', 'Estante B3', 4, 4, 'Novela en Barcelona de posguerra', 'disponible');