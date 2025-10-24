<?php
class BibliotecacifController {
    public function index() {
        echo "<h1>Bienvenido a Biblioteca CIF</h1>";
        echo "<p>Sistema funcionando correctamente</p>";
        echo '<a href="/biblioteca-cif/auth">Ir a Login</a>';
    }
}
