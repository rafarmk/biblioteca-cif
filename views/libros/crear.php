<?php require_once 'views/layouts/navbar.php'; ?>
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
input[type="number"],
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
[data-theme="dark"] input[type="number"],
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
[data-theme="premium"] input[type="number"],
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
    background: linear-gradient(135deg, #64748b 0%, #475569 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
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
.form-group input[type="text"],
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
        <h1> Nuevo Libro</h1>
        <a href="index.php?ruta=libros" class="btn btn-info"><i class="fas fa-arrow-left"></i> Volver
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
        <form action="index.php?ruta=libros&accion=crear" method="POST">

            <div class="form-group">
                <label for="titulo"> Título *</label>
                <input type="text" name="titulo" id="titulo" required placeholder="Título del libro">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="autor"> Autor *</label>
                    <input type="text" name="autor" id="autor" required placeholder="Nombre del autor">
                </div>

                <div class="form-group">
                    <label for="isbn"> ISBN</label>
                    <input type="text" name="isbn" id="isbn" placeholder="Código ISBN">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="editorial"> Editorial</label>
                    <input type="text" name="editorial" id="editorial" placeholder="Editorial">
                </div>

                <div class="form-group">
                    <label for="anio_publicacion"> Año de Publicación</label>
                    <input type="number" name="anio_publicacion" id="anio_publicacion" 
                           min="1800" max="<?php echo date('Y'); ?>" placeholder="Año">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="categoria"> Categoría</label>
                    <select name="categoria" id="categoria">
                        <option value="">Seleccione una categoría</option>
                        <option value="Ficción">Ficción</option>
                        <option value="No Ficción">No Ficción</option>
                        <option value="Ciencia">Ciencia</option>
                        <option value="Historia">Historia</option>
                        <option value="Tecnología">Tecnología</option>
                        <option value="Arte">Arte</option>
                        <option value="Biografía">Biografía</option>
                        <option value="Infantil">Infantil</option>
                        <option value="Juvenil">Juvenil</option>
                        <option value="Académico">Académico</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ubicacion"> Ubicación</label>
                    <input type="text" name="ubicacion" id="ubicacion" placeholder="Estante/Sección">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="cantidad_total"> Cantidad Total *</label>
                    <input type="number" name="cantidad_total" id="cantidad_total" 
                           required min="1" value="1" placeholder="Cantidad">
                </div>

                <div class="form-group">
                    <label for="estado"> Estado</label>
                    <select name="estado" id="estado">
                        <option value="disponible">Disponible</option>
                        <option value="no_disponible">No Disponible</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion"> Descripción</label>
                <textarea name="descripcion" id="descripcion" placeholder="Descripción breve del libro"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                 Crear Libro
            </button>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>


