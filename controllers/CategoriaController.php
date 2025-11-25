<?php
require_once __DIR__ . '/../config/Database.php';

class CategoriaController {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        require_once __DIR__ . '/../views/categorias/index.php';
    }

    public function crear() {
        require_once __DIR__ . '/../views/categorias/crear.php';
    }

    public function store() {
        try {
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $color = $_POST['color'] ?? '#3b82f6';
            $icono = $_POST['icono'] ?? '📚';

            $stmt = $this->db->prepare("
                INSERT INTO categorias (nombre, descripcion, color, icono)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$nombre, $descripcion, $color, $icono]);

            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Categoría creada exitosamente'
            ];

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=categorias');
        exit;
    }

    public function editar() {
        $id = $_GET['id'] ?? 0;
        require_once __DIR__ . '/../views/categorias/editar.php';
    }

    public function update() {
        try {
            $id = $_POST['id'];
            $nombre = trim($_POST['nombre']);
            $descripcion = trim($_POST['descripcion']);
            $color = $_POST['color'];
            $icono = $_POST['icono'];
            $estado = $_POST['estado'];

            $stmt = $this->db->prepare("
                UPDATE categorias 
                SET nombre = ?, descripcion = ?, color = ?, icono = ?, estado = ?
                WHERE id = ?
            ");
            $stmt->execute([$nombre, $descripcion, $color, $icono, $estado, $id]);

            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => '✅ Categoría actualizada'
            ];

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=categorias');
        exit;
    }

    public function eliminar() {
        try {
            $id = $_GET['id'] ?? 0;

            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM libros WHERE categoria_id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['total'] > 0) {
                $_SESSION['mensaje'] = [
                    'tipo' => 'warning',
                    'texto' => '⚠️ No se puede eliminar. Hay ' . $result['total'] . ' libro(s) con esta categoría'
                ];
            } else {
                $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
                $stmt->execute([$id]);

                $_SESSION['mensaje'] = [
                    'tipo' => 'success',
                    'texto' => '✅ Categoría eliminada'
                ];
            }

        } catch (PDOException $e) {
            $_SESSION['mensaje'] = [
                'tipo' => 'danger',
                'texto' => '❌ Error: ' . $e->getMessage()
            ];
        }

        header('Location: index.php?ruta=categorias');
        exit;
    }
}