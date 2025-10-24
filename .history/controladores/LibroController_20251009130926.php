<?php
// controllers/LibroController.php
require_once __DIR__ . '/../modelos/Libro.php';
require_once __DIR__ . '/../config/conexion.php';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $anio = $_POST['anio'] ?? '';

    // Crear el objeto
    $libro = new Libro($titulo, $autor, $categoria, $anio);

    // Validar los datos
    if ($libro->validar()) {
        $sql = "INSERT INTO libros (titulo, autor, categoria, anio) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $libro->titulo, $libro->autor, $libro->categoria, $libro->anio);

        if ($stmt->execute()) {
            $mensaje = "✅ Libro guardado correctamente en la base de datos.";
            $tipo = "success";
        } else {
            $mensaje = "❌ Error al guardar el libro: " . $stmt->error;
            $tipo = "danger";
        }
        $stmt->close();
    } else {
        $mensaje = "❌ Error: Datos incompletos.";
        $tipo = "warning";
    }
    $conn->close();
    
    // Redirigir con mensaje
    header("Location: ../vistas/form-Libro.php?mensaje=" . urlencode($mensaje) . "&tipo=" . $tipo);
    exit;
}
?>
f
