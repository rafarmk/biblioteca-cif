<?php
session_start();
require_once 'config/Database.php';

echo "<h1>🔍 TEST SISTEMA PRÉSTAMOS</h1>";
echo "<style>body{font-family:Arial;padding:40px;background:#1a1a2e;color:white;}</style>";

// Test 1: Conexión a BD
try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "<p style='color:#4ade80'>✅ Conexión a BD: OK</p>";
} catch (Exception $e) {
    echo "<p style='color:#ef4444'>❌ Error BD: " . $e->getMessage() . "</p>";
    die();
}

// Test 2: Tabla prestamos existe
try {
    $stmt = $conn->query("DESCRIBE prestamos");
    echo "<p style='color:#4ade80'>✅ Tabla prestamos: EXISTE</p>";
} catch (Exception $e) {
    echo "<p style='color:#ef4444'>❌ Tabla prestamos: NO EXISTE</p>";
}

// Test 3: Controlador existe
if (file_exists('controllers/PrestamoController.php')) {
    echo "<p style='color:#4ade80'>✅ PrestamoController.php: EXISTE</p>";
} else {
    echo "<p style='color:#ef4444'>❌ PrestamoController.php: NO EXISTE</p>";
}

// Test 4: Vista crear existe
if (file_exists('views/prestamos/crear.php')) {
    echo "<p style='color:#4ade80'>✅ views/prestamos/crear.php: EXISTE</p>";
} else {
    echo "<p style='color:#ef4444'>❌ views/prestamos/crear.php: NO EXISTE</p>";
}

// Test 5: Rutas en index.php
$indexContent = file_get_contents('index.php');
if (strpos($indexContent, "case 'prestamos':") !== false) {
    echo "<p style='color:#4ade80'>✅ Ruta 'prestamos' en index.php: EXISTE</p>";
} else {
    echo "<p style='color:#ef4444'>❌ Ruta 'prestamos' en index.php: NO EXISTE</p>";
}

// Test 6: Contar registros
try {
    $stmt = $conn->query("SELECT COUNT(*) as total FROM prestamos");
    $total = $stmt->fetch()['total'];
    echo "<p style='color:#4ade80'>✅ Total préstamos en BD: $total</p>";
} catch (Exception $e) {
    echo "<p style='color:#ef4444'>❌ Error al contar: " . $e->getMessage() . "</p>";
}

// Test 7: Contar libros disponibles
try {
    $stmt = $conn->query("SELECT COUNT(*) as total FROM libros WHERE cantidad_disponible > 0");
    $total = $stmt->fetch()['total'];
    echo "<p style='color:#4ade80'>✅ Libros disponibles: $total</p>";
} catch (Exception $e) {
    echo "<p style='color:#ef4444'>❌ Error: " . $e->getMessage() . "</p>";
}

// Test 8: Usuarios aprobados
try {
    $stmt = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 'aprobado'");
    $total = $stmt->fetch()['total'];
    echo "<p style='color:#4ade80'>✅ Usuarios aprobados: $total</p>";
} catch (Exception $e) {
    echo "<p style='color:#ef4444'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr><h2>🧪 TEST DE URLS</h2>";
echo "<a href='index.php?ruta=prestamos' style='color:#4ade80'>Test: index.php?ruta=prestamos</a><br>";
echo "<a href='index.php?ruta=prestamos&accion=crear' style='color:#4ade80'>Test: index.php?ruta=prestamos&accion=crear</a><br>";
echo "<a href='index.php?ruta=prestamos&accion=activos' style='color:#4ade80'>Test: index.php?ruta=prestamos&accion=activos</a><br>";
?>