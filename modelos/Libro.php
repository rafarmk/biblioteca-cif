<?php
// Clase Libro ubicada en modelos/Libros.php

// Definimos la clase Libro que representa un ejemplar en la biblioteca
class Libro {
    // Atributos públicos que describen las propiedades del libro
    public $titulo;
    public $autor;
    public $categoria;
    public $anio;

    // Método constructor: se ejecuta al crear un nuevo objeto
    public function __construct($titulo, $autor, $categoria, $anio) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->anio = $anio;
    }

    // Método para mostrar la información del libro como texto
    public function mostrar() {
        return $this->titulo . ', ' . $this->autor . ', ' . $this->anio;
    }

    // Método para validar que los atributos estén completos
    public function validar() {
        return !empty($this->titulo) && !empty($this->autor) && !empty($this->anio);
    }
}
?>
