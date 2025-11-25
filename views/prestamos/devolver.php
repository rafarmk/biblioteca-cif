<?php
$page_title = "Devolver Libro - Biblioteca CIF";
require_once __DIR__ . '/../layouts/header.php';

if (!isset($_SESSION['logueado'])) {
    header('Location: index.php?ruta=login');
    exit;
}

require_once __DIR__ . '/../layouts/navbar.php';

// Obtener información del préstamo
require_once __DIR__ . '/../../config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$prestamo_id = $_GET['id'] ?? 0;

$query = "SELECT p.*, l.titulo, l.autor, l.isbn, u.nombre as usuario_nombre,
          DATEDIFF(p.fecha_devolucion_esperada, CURDATE()) as dias_restantes,
          DATEDIFF(CURDATE(), p.fecha_prestamo) as dias_prestamo
          FROM prestamos p
          INNER JOIN libros l ON p.libro_id = l.id
          INNER JOIN usuarios u ON p.usuario_id = u.id
          WHERE p.id = ? AND p.usuario_id = ? AND p.estado = 'activo'";

$stmt = $conn->prepare($query);
$stmt->execute([$prestamo_id, $_SESSION['usuario_id']]);
$prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prestamo) {
    header('Location: index.php?ruta=mis_prestamos&error=prestamo_no_encontrado');
    exit;
}

// Determinar estado
$dias_restantes = $prestamo['dias_restantes'];
$dias_prestamo = $prestamo['dias_prestamo'];
$a_tiempo = $dias_restantes >= 0;
$dias_retraso = $a_tiempo ? 0 : abs($dias_restantes);
?>

<style>
body {
    padding-top: 100px;
    padding-bottom: 50px;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 30px 20px;
}

.status-banner {
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.status-banner.on-time {
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    border: 3px solid #10b981;
}

.status-banner.late {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border: 3px solid #ef4444;
}

.status-icon {
    font-size: 4rem;
    margin-bottom: 15px;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.status-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.status-banner.on-time .status-title {
    color: #065f46;
}

.status-banner.late .status-title {
    color: #991b1b;
}

.status-message {
    font-size: 1.1rem;
    margin-bottom: 0;
}

.status-banner.on-time .status-message {
    color: #047857;
}

.status-banner.late .status-message {
    color: #dc2626;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin: 25px 0;
}

.stat-box {
    background: var(--bg-secondary);
    padding: 20px;
    border-radius: 12px;
    text-align: center;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--accent-primary);
    margin-bottom: 5px;
}

.stat-label {
    color: var(--text-secondary);
    font-size: 0.85rem;
    font-weight: 600;
}

.card {
    background: var(--bg-card);
    border-radius: 15px;
    box-shadow: 0 8px 30px var(--shadow-color);
    padding: 40px;
    margin-bottom: 20px;
}

.card-header {
    border-bottom: 3px solid var(--accent-primary);
    padding-bottom: 20px;
    margin-bottom: 30px;
}

.card-header h2 {
    color: var(--text-primary);
    margin: 0;
    font-size: 2rem;
    font-weight: 700;
}

.book-info {
    background: var(--bg-secondary);
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
}

.book-info h3 {
    color: var(--text-primary);
    margin: 0 0 15px 0;
    font-size: 1.5rem;
}

.book-detail {
    color: var(--text-secondary);
    margin: 8px 0;
    font-size: 0.95rem;
}

.book-detail strong {
    color: var(--text-primary);
}

.review-section {
    margin-top: 30px;
}

.review-title {
    color: var(--text-primary);
    font-size: 1.3rem;
    margin-bottom: 20px;
    font-weight: 600;
}

.rating-container {
    text-align: center;
    margin-bottom: 30px;
    padding: 20px;
    background: var(--bg-secondary);
    border-radius: 12px;
}

.rating-label {
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.star-rating {
    display: flex;
    justify-content: center;
    gap: 10px;
    font-size: 3rem;
}

.star {
    cursor: pointer;
    color: #cbd5e1;
    transition: all 0.3s;
}

.star:hover,
.star.active {
    color: #fbbf24;
    transform: scale(1.2);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 0.95rem;
}

textarea.form-control {
    width: 100%;
    padding: 15px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-input);
    color: var(--text-primary);
    font-size: 1rem;
    resize: vertical;
    min-height: 150px;
    font-family: inherit;
    box-sizing: border-box;
}

textarea.form-control:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 3px var(--accent-shadow);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 15px;
    background: var(--bg-secondary);
    border-radius: 10px;
    margin-bottom: 25px;
}

.checkbox-group input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.checkbox-group label {
    color: var(--text-primary);
    cursor: pointer;
    margin: 0;
    font-size: 0.95rem;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid var(--border-color);
}

.btn {
    padding: 14px 35px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    font-size: 1rem;
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
}

.btn-success:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    transform: none;
}

.btn-secondary {
    background: #6b7280;
    color: white;
    box-shadow: 0 4px 15px var(--shadow-color);
}

.btn-secondary:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

.info-box {
    background: #dbeafe;
    border-left: 4px solid #3b82f6;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.info-box p {
    margin: 0;
    color: #1e40af;
    font-size: 0.95rem;
}

.late-notice {
    background: #fef3c7;
    border-left: 4px solid #f59e0b;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

.late-notice p {
    margin: 0;
    color: #92400e;
    font-size: 0.95rem;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .card {
        padding: 25px 20px;
    }
    
    .star-rating {
        font-size: 2.5rem;
    }
    
    .form-actions {
        flex-direction: column-reverse;
    }
    
    .btn {
        width: 100%;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="container">
    <!-- Banner de estado -->
    <?php if ($a_tiempo): ?>
        <div class="status-banner on-time">
            <div class="status-icon">😊</div>
            <h2 class="status-title">¡Excelente! Devuelves a tiempo</h2>
            <p class="status-message">Gracias por ser un lector responsable. Tu puntualidad ayuda a que más personas disfruten de nuestros libros.</p>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value"><?= $dias_prestamo ?></div>
                    <div class="stat-label">Días de lectura</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value"><?= $dias_restantes ?></div>
                    <div class="stat-label">Días de anticipación</div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="status-banner late">
            <div class="status-icon">😟</div>
            <h2 class="status-title">Préstamo con retraso</h2>
            <p class="status-message">Este libro debió devolverse hace <?= $dias_retraso ?> día<?= $dias_retraso > 1 ? 's' : '' ?>. Te pedimos mayor puntualidad en futuros préstamos.</p>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value"><?= $dias_prestamo ?></div>
                    <div class="stat-label">Días con el libro</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value" style="color: #ef4444;"><?= $dias_retraso ?></div>
                    <div class="stat-label">Días de retraso</div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h2>📚 Devolver Libro</h2>
        </div>

        <div class="book-info">
            <h3>📖 <?= htmlspecialchars($prestamo['titulo']) ?></h3>
            <div class="book-detail">
                <strong>Autor:</strong> <?= htmlspecialchars($prestamo['autor']) ?>
            </div>
            <div class="book-detail">
                <strong>ISBN:</strong> <?= htmlspecialchars($prestamo['isbn']) ?>
            </div>
            <div class="book-detail">
                <strong>Fecha de préstamo:</strong> <?= date('d/m/Y', strtotime($prestamo['fecha_prestamo'])) ?>
            </div>
            <div class="book-detail">
                <strong>Fecha límite:</strong> <?= date('d/m/Y', strtotime($prestamo['fecha_devolucion_esperada'])) ?>
            </div>
        </div>

        <?php if ($a_tiempo): ?>
            <div class="info-box">
                <p>💡 <strong>¡Ayuda a otros lectores!</strong> Comparte tu opinión sobre este libro. Tu reseña ayuda a la comunidad a descubrir grandes lecturas.</p>
            </div>
        <?php else: ?>
            <div class="late-notice">
                <p>⚠️ <strong>Nota:</strong> Este préstamo tiene <?= $dias_retraso ?> día<?= $dias_retraso > 1 ? 's' : '' ?> de retraso. Te invitamos a dejar una reseña y recordar devolver los futuros préstamos a tiempo.</p>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?ruta=prestamos&accion=procesar_devolucion" id="formDevolucion">
            <input type="hidden" name="prestamo_id" value="<?= $prestamo['id'] ?>">
            <input type="hidden" name="libro_id" value="<?= $prestamo['libro_id'] ?>">
            <input type="hidden" name="calificacion" id="calificacionInput" value="">

            <div class="review-section">
                <div class="review-title">⭐ ¿Qué te pareció este libro?</div>

                <div class="rating-container">
                    <div class="rating-label">Califica tu experiencia</div>
                    <div class="star-rating" id="starRating">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                    </div>
                    <div id="ratingText" style="color: var(--text-secondary); margin-top: 10px; font-size: 0.9rem;">
                        Selecciona una calificación
                    </div>
                </div>

                <div class="form-group">
                    <label>✍️ Comparte tu reseña (Opcional)</label>
                    <textarea name="comentario" class="form-control" 
                              placeholder="¿Qué te gustó del libro? ¿Lo recomendarías? ¿Qué aprendiste? Comparte tus pensamientos..."></textarea>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="sinResena" name="sin_resena" value="1">
                    <label for="sinResena">Prefiero devolver sin dejar reseña</label>
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php?ruta=mis_prestamos" class="btn btn-secondary">
                    ❌ Cancelar
                </a>
                <button type="submit" class="btn btn-success" id="btnDevolver" disabled>
                    ✅ Confirmar Devolución
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const calificacionInput = document.getElementById('calificacionInput');
    const ratingText = document.getElementById('ratingText');
    const btnDevolver = document.getElementById('btnDevolver');
    const sinResenaCheckbox = document.getElementById('sinResena');
    
    let calificacionSeleccionada = 0;
    
    const textos = {
        1: '⭐ No me gustó',
        2: '⭐⭐ Podría mejorar',
        3: '⭐⭐⭐ Me gustó',
        4: '⭐⭐⭐⭐ Muy bueno',
        5: '⭐⭐⭐⭐⭐ ¡Excelente!'
    };
    
    // Manejar clic en estrellas
    stars.forEach(star => {
        star.addEventListener('click', function() {
            calificacionSeleccionada = parseInt(this.dataset.value);
            calificacionInput.value = calificacionSeleccionada;
            
            // Actualizar estrellas
            stars.forEach(s => {
                if (parseInt(s.dataset.value) <= calificacionSeleccionada) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
            
            // Actualizar texto
            ratingText.textContent = textos[calificacionSeleccionada];
            
            // Habilitar botón
            validarFormulario();
        });
        
        // Efecto hover
        star.addEventListener('mouseenter', function() {
            const value = parseInt(this.dataset.value);
            stars.forEach(s => {
                if (parseInt(s.dataset.value) <= value) {
                    s.style.color = '#fbbf24';
                }
            });
        });
        
        star.addEventListener('mouseleave', function() {
            stars.forEach(s => {
                if (!s.classList.contains('active')) {
                    s.style.color = '#cbd5e1';
                }
            });
        });
    });
    
    // Manejar checkbox "sin reseña"
    sinResenaCheckbox.addEventListener('change', validarFormulario);
    
    function validarFormulario() {
        if (sinResenaCheckbox.checked || calificacionSeleccionada > 0) {
            btnDevolver.disabled = false;
        } else {
            btnDevolver.disabled = true;
        }
    }
    
    // Validar antes de enviar
    document.getElementById('formDevolucion').addEventListener('submit', function(e) {
        if (!sinResenaCheckbox.checked && calificacionSeleccionada === 0) {
            e.preventDefault();
            alert('Por favor califica el libro o marca la opción "Prefiero devolver sin dejar reseña"');
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>