UPDATE usuarios 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE id = 5;
SELECT id, nombre, email, LEFT(password, 20) as password_inicio FROM usuarios WHERE id = 5;
