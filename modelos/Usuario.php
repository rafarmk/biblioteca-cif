<?php
// Clase Usuario ubicada en modelos/Usuario.php

class Usuario {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Autenticación de usuario
    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre AS rol_nombre FROM usuarios u 
                                    JOIN roles r ON u.rol_id = r.id 
                                    WHERE u.email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            if (password_verify($password, $usuario['password'])) {
                return $usuario;
            }
        }

        return false;
    }

    // Registro de nuevo usuario
    public function registrar($datos) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, apellidos, email, password, documento, telefono, rol_id) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
        $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("ssssssi", 
            $datos['nombre'], 
            $datos['apellidos'], 
            $datos['email'], 
            $passwordHash, 
            $datos['documento'], 
            $datos['telefono'], 
            $datos['rol_id']
        );

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Error al registrar usuario'];
        }
    }

    // Validación de datos
    public function validar($datos) {
        $errores = [];

        if (empty($datos['nombre'])) $errores[] = "El nombre es obligatorio";
        if (empty($datos['apellidos'])) $errores[] = "Los apellidos son obligatorios";
        if (empty($datos['email'])) $errores[] = "El email es obligatorio";
        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) $errores[] = "El email no es válido";
        if (empty($datos['password'])) $errores[] = "La contraseña es obligatoria";
        if (strlen($datos['password']) < 6) $errores[] = "La contraseña debe tener al menos 6 caracteres";
        if (empty($datos['documento'])) $errores[] = "El documento es obligatorio";
        if (empty($datos['telefono'])) $errores[] = "El teléfono es obligatorio";

        return $errores;
    }

    // Obtener usuario por ID
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc();
    }
}
