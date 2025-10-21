<?php
// modelos/Libro.php

class Libro {
    private $titulo;
    private $autor;
    private $categoria;
    private $anio;

    // Constructor
    public function __construct($titulo = "", $autor = "", $categoria = "", $anio = null) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->anio = $anio;
    }

    // Getters
    public function getTitulo() {
        return $this->titulo;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getAnio() {
        return $this->anio;
    }

    // Setters
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setAutor($autor) {
        $this->autor = $autor;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function setAnio($anio) {
        $this->anio = $anio;
    }

    // Validar que todos los campos estén completos
    public function validar() {
        return !empty($this->titulo) && 
               !empty($this->autor) && 
               !empty($this->categoria) && 
               !empty($this->anio);
    }

    // Mostrar información del libro
    public function mostrar() {
        return "<strong>Título:</strong> {$this->titulo}<br>" .
               "<strong>Autor:</strong> {$this->autor}<br>" .
               "<strong>Categoría:</strong> {$this->categoria}<br>" .
               "<strong>Año:</strong> {$this->anio}";
    }

    // Método para guardar en archivo (opcional)
    public function guardarEnArchivo($archivo = 'libros.txt') {
        // Asegurar que usamos la ruta absoluta desde la raíz del proyecto
        $rutaArchivo = __DIR__ . '/../' . $archivo;
        $datos = "{$this->titulo}|{$this->autor}|{$this->categoria}|{$this->anio}\n";
        
        // Intentar crear el directorio si no existe
        $directorio = dirname($rutaArchivo);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        // Intentar crear el archivo si no existe con manejo de errores
        if (!file_exists($rutaArchivo)) {
            $create = @file_put_contents($rutaArchivo, '');
            if ($create === false) {
                error_log("No se pudo crear el archivo: " . $rutaArchivo);
                return false;
            }
            @chmod($rutaArchivo, 0666);
        }
        
        // Guardar datos
        $resultado = @file_put_contents($rutaArchivo, $datos, FILE_APPEND | LOCK_EX);
        
        if ($resultado === false) {
            error_log("No se pudo escribir en el archivo: " . $rutaArchivo);
        }
        
        return $resultado !== false;
    }
}
?>