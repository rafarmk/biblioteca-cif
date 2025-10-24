<?php
// Clase Usuario ubicada en modelos/Usuario.php

// Definimos la clase Usuario que representa a una persona que puede solicitar préstamos
class Usuario {
    // Atributos públicos que describen al usuario
    public $nombre;
    public $documento;
    public $tipo;

    // Método constructor: se ejecuta al crear una nueva instancia
    public function __construct($nombre, $documento, $tipo) {
        $this->nombre = $nombre;
        $this->documento = $documento;
        $this->tipo = $tipo;
    }

    // Método para mostrar la información del usuario como texto
    public function mostrar() {
        return "Nombre: " . $this->nombre . ", Documento: " . $this->documento . ", Tipo: " . $this->tipo;
    }

    // Método para validar que todos los campos estén completos
    public function validar() {
        return !empty($this->nombre) && !empty($this->documento) && !empty($this->tipo);
    }
}
?>