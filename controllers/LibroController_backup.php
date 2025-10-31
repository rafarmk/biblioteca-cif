/**
 * Guardar nuevo libro
 * Ruta: /index.php?ruta=libros/guardar (POST)
 */
public function guardar() {
    // Verificar que sea POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?ruta=libros');
        exit;
    }

    // ==========================================
    // VALIDAR CAMPOS OBLIGATORIOS
    // ==========================================
    $errores = [];

    if (empty($_POST['titulo'])) {
        $errores[] = "El título es obligatorio";
    }

    if (empty($_POST['autor'])) {
        $errores[] = "El autor es obligatorio";
    }

    // Validar ISBN si se proporciona
    if (!empty($_POST['isbn'])) {
        if ($this->modelo->existeISBN($_POST['isbn'])) {
            $errores[] = "Ya existe un libro con ese ISBN";
        }
    }

    // Validar año
    if (!empty($_POST['anio_publicacion'])) {
        $anio = intval($_POST['anio_publicacion']);
        if ($anio < 1000 || $anio > date('Y')) {
            $errores[] = "El año no es válido";
        }
    }

    // Si hay errores, regresar al formulario
    if (!empty($errores)) {
        $_SESSION['error'] = implode('. ', $errores);
        $_SESSION['datos_form'] = $_POST;
        header('Location: index.php?ruta=libros/crear');
        exit;
    }

    // ==========================================
    // PREPARAR DATOS LIMPIOS
    // ==========================================
    $datos = [
        'isbn' => !empty($_POST['isbn']) ? trim($_POST['isbn']) : null,
        'titulo' => trim($_POST['titulo']),
        'subtitulo' => !empty($_POST['subtitulo']) ? trim($_POST['subtitulo']) : null,
        'autor' => trim($_POST['autor']),
        'editorial' => !empty($_POST['editorial']) ? trim($_POST['editorial']) : null,
        'anio_publicacion' => !empty($_POST['anio_publicacion']) ? intval($_POST['anio_publicacion']) : null,
        'categoria_id' => !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null,
        'idioma' => !empty($_POST['idioma']) ? trim($_POST['idioma']) : 'Español',
        'paginas' => !empty($_POST['paginas']) ? intval($_POST['paginas']) : null,
        'descripcion' => !empty($_POST['descripcion']) ? trim($_POST['descripcion']) : null,
        'tipo' => !empty($_POST['tipo']) ? $_POST['tipo'] : 'fisico',
        'total_copias' => !empty($_POST['total_copias']) ? intval($_POST['total_copias']) : 1
    ];

    // ==========================================
    // GUARDAR EN BASE DE DATOS
    // ==========================================
    if ($this->modelo->crear($datos)) {
        $_SESSION['exito'] = "✅ Libro '{$datos['titulo']}' registrado exitosamente";
    } else {
        $_SESSION['error'] = "❌ Error al registrar el libro. Intente nuevamente.";
    }

    // Redirigir a lista
    header('Location: index.php?ruta=libros');
    exit;
}