<?php
// test-libro.php

// Incluimos la clase Libro desde la carpeta modelos
require_once 'modelos/Libro.php';

// Creamos un nuevo objeto Libro con datos de ejemplo
$libro = new Libro("Criminalística Forense", "Dra. Ana Torres", "Investigación", 2021);

// Validamos que los campos estén completos
if ($libro->validar()) {
    // Si es válido, mostramos la información del libro
    echo "<h3>Libro registrado correctamente:</h3>";
    echo "<p>" . $libro->mostrar() . "</p>";
} else {
    // Si falta algún dato, mostramos un mensaje de error
    echo "<p>Error: Faltan datos obligatorios.</p>";
}
?>
