<?php
session_start();
header('Content-Type: application/json');

// Verificar autenticación
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

require_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();

// Obtener cantidad de solicitudes pendientes
$query = "SELECT COUNT(*) as total FROM solicitudes_acceso WHERE estado = 'pendiente'";
$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener últimas 5 solicitudes pendientes
$query = "SELECT id, nombre, email, tipo_usuario, fecha_solicitud 
          FROM solicitudes_acceso 
          WHERE estado = 'pendiente' 
          ORDER BY fecha_solicitud DESC 
          LIMIT 5";
$stmt = $db->prepare($query);
$stmt->execute();
$solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'total' => $result['total'],
    'solicitudes' => $solicitudes
]);