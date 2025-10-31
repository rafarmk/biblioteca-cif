<?php
require_once 'config/Database.php';

class CatalogoController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function index() {
        // Buscar libros si hay query
        $buscar = $_GET['buscar'] ?? '';
        
        if (!empty($buscar)) {
            $query = "SELECT * FROM libros 
                      WHERE titulo LIKE :buscar 
                      OR autor LIKE :buscar 
                      OR isbn LIKE :buscar 
                      OR categoria LIKE :buscar
                      ORDER BY titulo ASC";
            $stmt = $this->db->prepare($query);
            $buscarParam = "%$buscar%";
            $stmt->bindParam(':buscar', $buscarParam);
        } else {
            $query = "SELECT * FROM libros ORDER BY titulo ASC";
            $stmt = $this->db->prepare($query);
        }
        
        $stmt->execute();
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/layouts/navbar.php';
        require_once 'views/catalogo/index.php';
    }
}
?>
