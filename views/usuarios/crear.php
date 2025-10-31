<?php require_once 'views/layouts/navbar.php'; ?>
<style>
.modern-form-container {
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 20px;
}

.form-card {
    background: var(--bg-card);
    border-radius: 16px;
    padding: 50px;
    border: 2px solid var(--border-color);
    box-shadow: 0 8px 32px var(--shadow);
}

[data-theme="premium"] .form-card {
    background: linear-gradient(135deg, #1e2533 0%, #2a3441 100%);
    border-color: rgba(56, 189, 248, 0.2);
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border-color);
}

.form-header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.form-header h2 {
    color: var(--text-primary);
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
}

.form-header i {
    font-size: 2rem;
    color: var(--primary);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin-bottom: 25px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 14px 18px;
    background: #ffffff;
    color: #000000;
    border: 2px solid #d1dce5;
    border-radius: 12px;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #95a5a6;
}

.campo-oculto {
    display: none;
}

.info-box {
    background: rgba(52, 152, 219, 0.1);
    border-left: 4px solid var(--primary);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    color: var(--text-primary);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid var(--border-color);
}

.btn-primary-modern {
    flex: 1;
    padding: 14px 32px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
}

.btn-secondary-modern {
    padding: 12px 24px;
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-secondary-modern:hover {
    background: var(--bg-card);
    border-color: var(--primary);
}

.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.alert-danger {
    background: #fee2e2;
    color: #991b1b;
    border: 2px solid #fecaca;
}

[data-theme="dark"] .form-group input,
[data-theme="dark"] .form-group textarea,
[data-theme="dark"] .form-group select {
    background: #2d3748;
    color: #ffffff;
    border-color: #4a5568;
}

[data-theme="dark"] .form-group input:focus,
[data-theme="dark"] .form-group textarea:focus,
[data-theme="dark"] .form-group select:focus {
    border-color: #3b82f6;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-card {
        padding: 30px;
    }
}

html, body { height: 100%; margin: 0; }
body { display: flex; flex-direction: column; min-height: 100vh; }
.modern-form-container { flex: 1 0 auto; }
footer { flex-shrink: 0; margin-top: auto !important; }
</style>

<div class="modern-form-container">
    <div class="form-card">
        <div class="form-header">
            <div class="form-header-left">
                <i class="fas fa-user-plus"></i>
                <h2>Crear Nuevo Usuario</h2>
            </div>
            <a href="index.php?ruta=usuarios" class="btn-secondary-modern">
                <i class="fas fa-arrow-left"></i>
                Volver
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?ruta=usuarios&accion=crear" id="formUsuario">

            <div class="form-group">
                <label>Tipo de Usuario *</label>
                <select name="tipo_usuario" id="tipo_usuario" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="personal_administrativo">Personal Administrativo</option>
                    <option value="personal_operativo">Personal Operativo</option>
                    <option value="visitas">Visitas</option>
                    <option value="estudiante_mayor">Estudiante (Mayor de Edad)</option>
                    <option value="estudiante_menor">Estudiante (Menor de Edad)</option>
                </select>
            </div>

            <div id="info-identificacion" class="info-box campo-oculto">
                <i class="fas fa-info-circle"></i> <span id="texto-info"></span>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Nombre Completo *</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" placeholder="correo@ejemplo.com">
                </div>
            </div>

            <div class="form-group campo-oculto" id="campo-oni">
                <label>Número ONI *</label>
                <input type="text" name="oni" id="oni" placeholder="Ej: 12345ABC o 123456">
            </div>

            <div class="form-group campo-oculto" id="campo-dui">
                <label>Número de DUI *</label>
                <input type="text" name="dui" id="dui" placeholder="Ej: 12345678-9" maxlength="10">
            </div>

            <div class="form-group campo-oculto" id="campo-token">
                <label>Token Temporal (Auto-generado)</label>
                <input type="text" name="token_temporal" id="token_temporal" readonly style="background: #f0f0f0;">
            </div>

            <div id="datos-tutor" class="campo-oculto">
                <h3 style="color: var(--text-primary); margin: 30px 0 20px;">Datos del Tutor/Encargado</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre del Tutor *</label>
                        <input type="text" name="tutor_nombre" id="tutor_nombre" placeholder="Nombre completo del tutor">
                    </div>

                    <div class="form-group">
                        <label>DUI del Tutor *</label>
                        <input type="text" name="tutor_dui" id="tutor_dui" placeholder="12345678-9" maxlength="10">
                    </div>
                </div>

                <div class="form-group">
                    <label>Contacto del Tutor *</label>
                    <input type="text" name="tutor_contacto" id="tutor_contacto" placeholder="Teléfono o email">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" id="telefono" placeholder="(503) 0000-0000">
                </div>

                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion" id="direccion" rows="3" placeholder="Dirección completa"></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary-modern">
                    <i class="fas fa-save"></i>
                    Guardar Usuario
                </button>
                <a href="index.php?ruta=usuarios" class="btn-secondary-modern">
                    <i class="fas fa-times"></i>
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
const tipoUsuario = document.getElementById('tipo_usuario');
const infoBox = document.getElementById('info-identificacion');
const textoInfo = document.getElementById('texto-info');
const campoOni = document.getElementById('campo-oni');
const campoDui = document.getElementById('campo-dui');
const campoToken = document.getElementById('campo-token');
const datosTutor = document.getElementById('datos-tutor');
const inputOni = document.getElementById('oni');
const inputDui = document.getElementById('dui');
const inputToken = document.getElementById('token_temporal');
const inputTutorNombre = document.getElementById('tutor_nombre');
const inputTutorDui = document.getElementById('tutor_dui');
const inputTutorContacto = document.getElementById('tutor_contacto');

tipoUsuario.addEventListener('change', function() {
    campoOni.classList.add('campo-oculto');
    campoDui.classList.add('campo-oculto');
    campoToken.classList.add('campo-oculto');
    datosTutor.classList.add('campo-oculto');
    infoBox.classList.add('campo-oculto');

    inputOni.removeAttribute('required');
    inputDui.removeAttribute('required');
    inputTutorNombre.removeAttribute('required');
    inputTutorDui.removeAttribute('required');
    inputTutorContacto.removeAttribute('required');

    inputOni.value = '';
    inputDui.value = '';
    inputToken.value = '';

    const tipo = this.value;

    switch(tipo) {
        case 'personal_administrativo':
            infoBox.classList.remove('campo-oculto');
            textoInfo.textContent = 'El personal administrativo se identifica con Número ONI (puede contener números y letras)';
            campoOni.classList.remove('campo-oculto');
            inputOni.setAttribute('required', 'required');
            inputOni.placeholder = 'Ej: 12345ABC';
            break;

        case 'personal_operativo':
            infoBox.classList.remove('campo-oculto');
            textoInfo.textContent = 'El personal operativo se identifica con Número ONI (solo números)';
            campoOni.classList.remove('campo-oculto');
            inputOni.setAttribute('required', 'required');
            inputOni.placeholder = 'Ej: 123456';
            break;

        case 'visitas':
            infoBox.classList.remove('campo-oculto');
            textoInfo.textContent = 'Las visitas se identifican con número de DUI';
            campoDui.classList.remove('campo-oculto');
            inputDui.setAttribute('required', 'required');
            break;

        case 'estudiante_mayor':
            infoBox.classList.remove('campo-oculto');
            textoInfo.textContent = 'Los estudiantes mayores de edad se identifican con número de DUI';
            campoDui.classList.remove('campo-oculto');
            inputDui.setAttribute('required', 'required');
            break;

        case 'estudiante_menor':
            infoBox.classList.remove('campo-oculto');
            textoInfo.textContent = 'Se generará un token temporal automáticamente para identificar al estudiante.';
            campoToken.classList.remove('campo-oculto');
            inputToken.value = 'TOKEN-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9).toUpperCase();
            break;
    }
});

document.getElementById('dui')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 8) {
        value = value.slice(0, 8) + '-' + value.slice(8, 9);
    }
    e.target.value = value;
});

document.getElementById('tutor_dui')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 8) {
        value = value.slice(0, 8) + '-' + value.slice(8, 9);
    }
    e.target.value = value;
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>
