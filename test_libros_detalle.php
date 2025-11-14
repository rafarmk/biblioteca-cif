<?php
session_start();
$_SESSION['logueado'] = true;
$_SESSION['rol'] = 'administrador';

require_once 'config/Database.php';

echo "<h1>🔍 TEST DETALLADO DE LIBROS</h1>";
echo "<style>body{font-family:Arial;padding:40px;background:#1a1a2e;color:white;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid white;padding:10px;text-align:left;}</style>";

$db = new Database();
$conn = $db->getConnection();

// Test 1: Contar libros
$stmt = $conn->query("SELECT COUNT(*) as total FROM libros");
$total = $stmt->fetch()['total'];
echo "<h2>✅ Total de libros en BD: <span style='color:#4ade80'>$total</span></h2>";

// Test 2: Ver estructura de tabla
echo "<h2>📋 Estructura de tabla libros:</h2>";
$stmt = $conn->query("DESCRIBE libros");
$columnas = $stmt->fetchAll();
echo "<table>";
echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th></tr>";
foreach ($columnas as $col) {
    echo "<tr><td>{$col['Field']}</td><td>{$col['Type']}</td><td>{$col['Null']}</td><td>{$col['Key']}</td></tr>";
}
echo "</table>";

// Test 3: Primeros 5 libros
if ($total > 0) {
    echo "<h2>📚 Primeros 5 libros:</h2>";
    $stmt = $conn->query("SELECT * FROM libros LIMIT 5");
    $libros = $stmt->fetchAll();
    
    echo "<table>";
    echo "<tr><th>ID</th><th>ISBN</th><th>Título</th><th>Autor</th><th>Estado</th><th>Disponibles</th></tr>";
    foreach ($libros as $libro) {
        echo "<tr>";
        echo "<td>{$libro['id']}</td>";
        echo "<td>{$libro['isbn']}</td>";
        echo "<td>" . htmlspecialchars($libro['titulo']) . "</td>";
        echo "<td>" . htmlspecialchars($libro['autor']) . "</td>";
        echo "<td>{$libro['estado']}</td>";
        echo "<td>{$libro['cantidad_disponible']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// Test 4: Verificar controlador
echo "<h2>🎮 Verificar LibroController:</h2>";
if (file_exists('controllers/LibroController.php')) {
    echo "<p style='color:#4ade80'>✅ LibroController.php existe</p>";
    
    require_once 'controllers/LibroController.php';
    $controller = new LibroController();
    echo "<p style='color:#4ade80'>✅ LibroController se puede instanciar</p>";
    
    // Capturar output
    ob_start();
    try {
        $controller->index();
        $output = ob_get_clean();
        
        if (empty($output)) {
            echo "<p style='color:#ef4444'>❌ El método index() no genera output</p>";
        } else {
            echo "<p style='color:#4ade80'>✅ El método index() genera output (" . strlen($output) . " bytes)</p>";
            echo "<details><summary>Ver output</summary><pre>" . htmlspecialchars(substr($output, 0, 500)) . "...</pre></details>";
        }
    } catch (Exception $e) {
        ob_end_clean();
        echo "<p style='color:#ef4444'>❌ Error en index(): " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:#ef4444'>❌ LibroController.php NO existe</p>";
}

// Test 5: Verificar vista
echo "<h2>👁️ Verificar vista:</h2>";
if (file_exists('views/libros/index.php')) {
    echo "<p style='color:#4ade80'>✅ views/libros/index.php existe</p>";
} else {
    echo "<p style='color:#ef4444'>❌ views/libros/index.php NO existe</p>";
}

echo "<hr>";
echo "<h2>🔗 Enlaces de prueba:</h2>";
echo "<a href='index.php?ruta=libros' style='color:#4ade80; font-size:20px; display:block; margin:10px 0;'>→ Ir a Libros (ruta normal)</a>";
echo "<a href='views/libros/index.php' style='color:#4ade80; font-size:20px; display:block; margin:10px 0;'>→ Vista directa (si funciona, es problema del controlador)</a>";
?>