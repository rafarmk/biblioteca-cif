<?php require_once __DIR__ . '/../layouts/navbar.php'; ?>
<style id="input-fix">
input, textarea, select {
    background-color: #ffffff !important;
    color: #000000 !important;
    border: 2px solid #d1d5db !important;
    padding: 12px 16px !important;
    border-radius: 8px !important;
}
::placeholder { color: #9ca3af !important; }
label { color: #374151 !important; font-weight: 600 !important; }
[data-theme="dark"] input, [data-theme="dark"] textarea, [data-theme="dark"] select {
    background-color: #374151 !important; color: #ffffff !important;
}
</style>
<style>
/* FORZAR VISIBILIDAD DE INPUTS */
input[type="text"],
input[type="email"], 
input[type="tel"],
select,
textarea {
    background-color: #ffffff !important;
    color: #000000 !important;
    border: 2px solid #cbd5e0 !important;
}

input::placeholder {
    color: #a0aec0 !important;
}

label {
    color: #2d3748 !important;
}

/* Modo Oscuro */
[data-theme="dark"] input[type="text"],
[data-theme="dark"] input[type="email"],
[data-theme="dark"] input[type="tel"],
[data-theme="dark"] select,
[data-theme="dark"] textarea {
    background-color: #2d3748 !important;
    color: #ffffff !important;
    border-color: #4a5568 !important;
}

[data-theme="dark"] label {
    color: #e2e8f0 !important;
}

/* Modo Premium */
[data-theme="premium"] input[type="text"],
[data-theme="premium"] input[type="email"],
[data-theme="premium"] input[type="tel"],
[data-theme="premium"] select,
[data-theme="premium"] textarea {
    background-color: #1a202c !important;
    color: #e2e8f0 !important;
    border-color: rgba(56, 189, 248, 0.4) !important;
}

[data-theme="premium"] label {
    color: #c9d1d9 !important;
}
</style>

<style>
.main-container {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px 40px 20px;
    position: relative;
    z-index: 1;
}

.page-header {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px var(--shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    border: 2px solid var(--border-color);
}

[data-theme="premium"] .page-header {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.page-header h1 {
    font-size: 2rem;
    color: var(--text-primary);
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 15px;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary {
    background: var(--secondary);
    color: white;
}

.btn-secondary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.btn-primary {
    background: var(--primary);
    color: white;
    width: 100%;
    justify-content: center;
    padding: 15px;
    font-size: 16px;
}

.btn-primary:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

.content-card {
    background: var(--bg-card);
    border-radius: 16px;
    box-shadow: 0 4px 20px var(--shadow);
    border: 2px solid var(--border-color);
    padding: 40px;
}

[data-theme="premium"] .content-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}

.form-group select,
.form-group input[type="date"],
.form-group input[type="number"],
.form-group textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group select:focus,
.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    font-weight: 500;
}

.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fecaca;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 2px solid #a7f3d0;
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: stretch;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .content-card {
        padding: 25px;
    }
}
</style>

<div class="main-container">
    <div class="page-header">
        <h1> Nuevo Préstamo</h1>
        <a href="index.php?ruta=prestamos" class="btn btn-secondary">
             Volver
        </a>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="content-card">
        <form action="index.php?ruta=prestamos/guardar" method="POST" id="formPrestamo">
            
            <div class="form-group">
                <label for="usuario_id"> Usuario *</label>
                <select name="usuario_id" id="usuario_id" required>
                    <option value="">Seleccione un usuario</option>
                    <?php if (isset($usuarios) && count($usuarios) > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?php echo $usuario['id']; ?>">
                                <?php echo htmlspecialchars($usuario['nombre']); ?>
                                <?php if (isset($usuario['tipo_usuario'])): ?>
                                    (<?php echo ucfirst($usuario['tipo_usuario']); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="libro_id"> Libro *</label>
                <select name="libro_id" id="libro_id" required>
                    <option value="">Seleccione un libro</option>
                    <?php if (isset($libros) && count($libros) > 0): ?>
                        <?php foreach ($libros as $libro): ?>
                            <option value="<?php echo $libro['id']; ?>" 
                                    data-disponibles="<?php echo $libro['cantidad_disponible'] ?? 0; ?>">
                                <?php echo htmlspecialchars($libro['titulo']); ?>
                                <?php if (isset($libro['autor'])): ?>
                                    - <?php echo htmlspecialchars($libro['autor']); ?>
                                <?php endif; ?>
                                (Disponibles: <?php echo $libro['cantidad_disponible'] ?? 0; ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small style="color: var(--text-secondary); display: block; margin-top: 5px;">
                    Solo se muestran libros con stock disponible
                </small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="fecha_prestamo"> Fecha de Préstamo *</label>
                    <input type="date" 
                           name="fecha_prestamo" 
                           id="fecha_prestamo" 
                           value="<?php echo date('Y-m-d'); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="fecha_devolucion_esperada"> Fecha de Devolución Esperada *</label>
                    <input type="date" 
                           name="fecha_devolucion_esperada" 
                           id="fecha_devolucion_esperada" 
                           value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones"> Observaciones</label>
                <textarea name="observaciones" 
                          id="observaciones" 
                          placeholder="Ingrese observaciones adicionales (opcional)"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                 Registrar Préstamo
            </button>
        </form>
    </div>
</div>

<script>
// Validar disponibilidad del libro
document.getElementById('libro_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const disponibles = parseInt(selectedOption.getAttribute('data-disponibles') || 0);
    
    if (disponibles <= 0) {
        alert(' Este libro no tiene ejemplares disponibles');
        this.value = '';
    }
});

// Validar fechas
document.getElementById('fecha_devolucion_esperada').addEventListener('change', function() {
    const fechaPrestamo = new Date(document.getElementById('fecha_prestamo').value);
    const fechaDevolucion = new Date(this.value);
    
    if (fechaDevolucion <= fechaPrestamo) {
        alert(' La fecha de devolución debe ser posterior a la fecha de préstamo');
        this.value = '';
    }
});

// Confirmar antes de enviar
document.getElementById('formPrestamo').addEventListener('submit', function(e) {
    const usuario = document.getElementById('usuario_id').options[document.getElementById('usuario_id').selectedIndex].text;
    const libro = document.getElementById('libro_id').options[document.getElementById('libro_id').selectedIndex].text;
    
    if (!confirm(`¿Confirmar préstamo?\n\nUsuario: ${usuario}\nLibro: ${libro}`)) {
        e.preventDefault();
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

