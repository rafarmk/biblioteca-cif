<?php
// controladores/libroController.php

// Incluimos la clase Libro y la conexión
require_once '../modelos/Libros.php';
require_once '../config/conexion.php'; // ← Corrección aquí

// Simulamos datos recibidos (luego vendrán del formulario)
$titulo = "Criminalística Forense";
$autor = "Dra. Ana Torres";
$categoria = "Investigación";
$anio = 2021;

// Creamos el objeto
$libro = new Libro($titulo, $autor, $categoria, $anio);

// Validamos los datos
if ($libro->validar()) {
    // Preparamos la consulta SQL
    $sql = "INSERT INTO libros (titulo, autor, categoria, anio)
            VALUES (?, ?, ?, ?)";

    // Usamos prepared statements para seguridad
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $libro->titulo, $libro->autor, $libro->categoria, $libro->anio);

    // Ejecutamos y verificamos
    if ($stmt->execute()) {
        echo "<p>✅ Libro guardado correctamente en la base de datos.</p>";
    } else {
        echo "<p>❌ Error al guardar el libro: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "<p>❌ Error: Datos incompletos.</p>";
}

$conn->close();
?>