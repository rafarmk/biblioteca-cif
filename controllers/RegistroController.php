<?php
session_start();

// Verificar que sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?ruta=registro');
    exit;
}

// Conectar a la base de datos
require_once __DIR__ . '/../config/Database.php';
$database = new Database();
$db = $database->getConnection();

try {
    // Obtener y limpiar datos
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $carnet = trim($_POST['carnet'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $tipo_usuario = $_POST['tipo_usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    // Validaciones
    if (empty($nombre) || empty($email) || empty($tipo_usuario) || empty($password)) {
        throw new Exception('Todos los campos obligatorios deben ser completados');
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El correo electrónico no es válido');
    }
    
    if (strlen($password) < 6) {
        throw new Exception('La contraseña debe tener al menos 6 caracteres');
    }
    
    if ($password !== $password_confirm) {
        throw new Exception('Las contraseñas no coinciden');
    }
    
    // Verificar si el email ya existe
    $checkQuery = "SELECT id FROM usuarios WHERE email = :email";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(':email', $email);
    $checkStmt->execute();
    
    if ($checkStmt->fetch()) {
        throw new Exception('Este correo electrónico ya está registrado');
    }
    
    // Hashear la contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insertar usuario (inactivo por defecto, debe ser activado por admin)
    $query = "INSERT INTO usuarios (nombre, email, password, tipo_usuario, carnet, telefono, activo, created_at) 
              VALUES (:nombre, :email, :password, :tipo_usuario, :carnet, :telefono, 0, NOW())";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $passwordHash);
    $stmt->bindParam(':tipo_usuario', $tipo_usuario);
    $stmt->bindParam(':carnet', $carnet);
    $stmt->bindParam(':telefono', $telefono);
    
    if ($stmt->execute()) {
        // Crear solicitud de registro
        $solicitudQuery = "INSERT INTO solicitudes_registro (usuario_id, email, nombre, estado, fecha_solicitud) 
                           VALUES (LAST_INSERT_ID(), :email, :nombre, 'pendiente', NOW())";
        $solicitudStmt = $db->prepare($solicitudQuery);
        $solicitudStmt->bindParam(':email', $email);
        $solicitudStmt->bindParam(':nombre', $nombre);
        $solicitudStmt->execute();
        
        $_SESSION['success'] = '✅ Registro exitoso. Tu cuenta está pendiente de aprobación por un administrador.';
        header('Location: ../index.php?ruta=login');
        exit;
    } else {
        throw new Exception('Error al crear la cuenta');
    }
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: ../index.php?ruta=registro');
    exit;
}
?>