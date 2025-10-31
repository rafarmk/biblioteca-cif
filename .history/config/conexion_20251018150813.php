<?php
// config/conexion.php

class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $baseDatos = "biblioteca_cif";
    private $conexion;

    public function conectar() {
        $this->conexion = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->baseDatos . ";charset=utf8mb4";
            $this->conexion = new PDO($dsn, $this->usuario, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        
        return $this->conexion;
    }

    public function cerrar() {
        $this->conexion = null;
    }
}
?>
```

---

## 📝 **PASOS:**

1. **Abre:** `config/conexion.php` en VS Code
2. **Selecciona TODO** (Ctrl+A)
3. **Borra todo**
4. **Copia el código de arriba**
5. **Pega** (Ctrl+V)
6. **Guarda** (Ctrl+S)

---

## ✅ **AHORA PRUEBA DE NUEVO:**

1. **Recarga la página en tu navegador:**
```
   http://localhost:8000/index.php?ruta=usuarios