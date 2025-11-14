<?php
session_start();
require_once 'config/Database.php';

$database = new Database();
$db = $database->getConnection();

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Debug Solicitudes</title>";
echo "<style>
    body { font-family: monospace; background: #1a1a1a; color: #0f0; padding: 20px; }
    table { border-collapse: collapse; width: 100%; background: #000; margin: 20px 0; }
    th, td { border: 1px solid #0f0; padding: 10px; text-align: left; }
    th { background: #333; }
    .warning { color: #ff0; }
    .error { color: #f00; }
    .success { color: #0f0; }
</style></head><body>";

echo "<h1>🔍 DEBUG: Solicitudes Pendientes</h1>";

// Contar pendientes
$stmt = $db->query("SELECT COUNT(*) FROM solicitudes_registro WHERE estado = 'pendiente'");
$total = $stmt->fetchColumn();

echo "<h2 class='warning'>Total de solicitudes pendientes: {$total}</h2>";

if ($total > 0) {
    // Mostrar todas las pendientes
    $stmt = $db->query("SELECT * FROM solicitudes_registro WHERE estado = 'pendiente' ORDER BY id DESC");
    $solicitudes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Registros encontrados:</h3>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Usuario ID</th><th>Email</th><th>Estado</th><th>Fecha Solicitud</th><th>Días desde solicitud</th></tr>";
    
    foreach ($solicitudes as $sol) {
        $fecha = new DateTime($sol['fecha_solicitud'] ?? 'now');
        $hoy = new DateTime();
        $dias = $hoy->diff($fecha)->days;
        
        $clase = $dias > 30 ? 'error' : ($dias > 7 ? 'warning' : 'success');
        
        echo "<tr class='{$clase}'>";
        echo "<td>{$sol['id']}</td>";
        echo "<td>{$sol['usuario_id']}</td>";
        echo "<td>{$sol['email']}</td>";
        echo "<td>{$sol['estado']}</td>";
        echo "<td>{$sol['fecha_solicitud']}</td>";
        echo "<td>{$dias} días</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3 class='error'>RECOMENDACIÓN:</h3>";
    echo "<p>Si ves registros con más de 30 días, ejecuta el SQL de limpieza que te proporcioné.</p>";
    
} else {
    echo "<h2 class='success'>✅ No hay solicitudes pendientes. El problema está resuelto.</h2>";
}

echo "<hr><a href='index.php?ruta=dashboard' style='color:#0f0'>← Volver al Dashboard</a>";
echo "</body></html>";
?>