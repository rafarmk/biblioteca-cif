<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitudes Pendientes - Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            padding: 20px;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .notificacion-badge {
            background: #e74c3c;
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1em;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .solicitud-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
        }
        
        .solicitud-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .tipo-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
            white-space: nowrap;
        }
        
        .tipo-operativo { background: #3498db; color: white; }
        .tipo-administrativo { background: #9b59b6; color: white; }
        .tipo-visita { background: #f39c12; color: white; }
        
        .datos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .dato-item {
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .dato-label {
            font-weight: 600;
            color: #666;
            font-size: 0.85em;
            margin-bottom: 5px;
        }
        
        .dato-valor {
            color: #333;
            font-size: 1em;
            word-break: break-word;
        }
        
        .acciones {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1em;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-aprobar {
            background: #27ae60;
            color: white;
            flex: 1;
            min-width: 200px;
        }
        
        .btn-rechazar {
            background: #e74c3c;
            color: white;
            flex: 1;
            min-width: 200px;
        }
        
        .btn-volver {
            background: #667eea;
            color: white;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 100%;
        }
        
        .modal-content h3 {
            margin-bottom: 20px;
            color: #333;
        }
        
        textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-family: inherit;
            font-size: 1em;
            margin-bottom: 20px;
            resize: vertical;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 15px;
        }
        
        .empty-state h2 {
            color: #27ae60;
            margin-top: 20px;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <a href="index.php?ruta=home" class="btn btn-volver">← Volver al Dashboard</a>
    
    <div class="header">
        <div>
            <h1>📋 Solicitudes de Registro Pendientes</h1>
            <p>Administración de nuevos usuarios</p>
        </div>
        <div class="notificacion-badge">
            🔔 <?php echo $total_pendientes; ?> Pendiente<?php echo $total_pendientes != 1 ? 's' : ''; ?>
        </div>
    </div>
    
    <?php if (isset($_SESSION['mensaje_exito'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['mensaje_error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($total_pendientes > 0): ?>
        <?php foreach ($solicitudes as $sol): ?>
            <div class="solicitud-card">
                <div class="solicitud-header">
                    <div>
                        <h3><?php echo htmlspecialchars($sol['nombre_completo'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <small style="color: #666;">
                            Solicitud: <?php echo date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])); ?>
                        </small>
                    </div>
                    <span class="tipo-badge tipo-<?php echo $sol['tipo_usuario']; ?>">
                        <?php echo strtoupper($sol['tipo_usuario']); ?>
                    </span>
                </div>
                
                <div class="datos-grid">
                    <div class="dato-item">
                        <div class="dato-label">📧 Correo</div>
                        <div class="dato-valor"><?php echo htmlspecialchars($sol['correo'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    
                    <div class="dato-item">
                        <div class="dato-label">📱 Teléfono</div>
                        <div class="dato-valor"><?php echo htmlspecialchars($sol['telefono'], ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                    
                    <?php if ($sol['tipo_usuario'] === 'operativo'): ?>
                        <div class="dato-item">
                            <div class="dato-label">🆔 Número ONI</div>
                            <div class="dato-valor"><?php echo htmlspecialchars($sol['numero_oni'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                        <div class="dato-item">
                            <div class="dato-label">🏢 Dependencia</div>
                            <div class="dato-valor"><?php echo htmlspecialchars($sol['dependencia'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    <?php elseif ($sol['tipo_usuario'] === 'administrativo'): ?>
                        <div class="dato-item">
                            <div class="dato-label">🔑 Código Administrativo</div>
                            <div class="dato-valor"><?php echo htmlspecialchars($sol['codigo_administrativo'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    <?php elseif ($sol['tipo_usuario'] === 'visita'): ?>
                        <div class="dato-item">
                            <div class="dato-label">🪪 DUI</div>
                            <div class="dato-valor"><?php echo htmlspecialchars($sol['numero_dui'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                        <div class="dato-item" style="grid-column: 1 / -1;">
                            <div class="dato-label">📝 Motivo de Visita</div>
                            <div class="dato-valor"><?php echo htmlspecialchars($sol['motivo_visita'], ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="acciones">
                    <button class="btn btn-aprobar" onclick="mostrarModal(<?php echo $sol['id']; ?>, 'aprobar')">
                        ✅ Aprobar Solicitud
                    </button>
                    <button class="btn btn-rechazar" onclick="mostrarModal(<?php echo $sol['id']; ?>, 'rechazar')">
                        ❌ Rechazar Solicitud
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <div style="font-size: 4em;">✅</div>
            <h2>¡Todo al día!</h2>
            <p style="color: #666; margin-top: 10px;">No hay solicitudes pendientes de aprobación</p>
        </div>
    <?php endif; ?>
    
    <!-- Modal -->
    <div id="modalAccion" class="modal">
        <div class="modal-content">
            <h3 id="modalTitulo"></h3>
            <form method="POST" id="formAccion">
                <input type="hidden" name="solicitud_id" id="solicitudId">
                
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">
                    Comentario (opcional):
                </label>
                <textarea name="comentario" rows="4" placeholder="Agrega un comentario sobre esta decisión..."></textarea>
                
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="submit" class="btn" id="btnConfirmar" style="flex: 1; min-width: 150px;">
                        Confirmar
                    </button>
                    <button type="button" class="btn" onclick="cerrarModal()" style="flex: 1; min-width: 150px; background: #95a5a6;">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function mostrarModal(solicitudId, accion) {
            const modal = document.getElementById('modalAccion');
            const titulo = document.getElementById('modalTitulo');
            const btnConfirmar = document.getElementById('btnConfirmar');
            const form = document.getElementById('formAccion');
            
            document.getElementById('solicitudId').value = solicitudId;
            
            if (accion === 'aprobar') {
                titulo.textContent = '✅ Aprobar Solicitud de Registro';
                btnConfirmar.className = 'btn btn-aprobar';
                btnConfirmar.textContent = 'Aprobar y Crear Usuario';
                form.action = 'index.php?ruta=solicitudes&accion=aprobar';
            } else {
                titulo.textContent = '❌ Rechazar Solicitud de Registro';
                btnConfirmar.className = 'btn btn-rechazar';
                btnConfirmar.textContent = 'Confirmar Rechazo';
                form.action = 'index.php?ruta=solicitudes&accion=rechazar';
            }
            
            modal.style.display = 'flex';
        }
        
        function cerrarModal() {
            document.getElementById('modalAccion').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('modalAccion');
            if (event.target === modal) {
                cerrarModal();
            }
        }
    </script>
</body>
</html>