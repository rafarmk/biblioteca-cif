<?php
session_start();
require_once '../config/database.php';

// Verificar que es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit;
}

// Procesar aprobación/rechazo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $solicitud_id = (int)$_POST['solicitud_id'];
    $accion = $_POST['accion']; // 'aprobar' o 'rechazar'
    $comentario = htmlspecialchars(trim($_POST['comentario']), ENT_QUOTES, 'UTF-8');
    
    if ($accion === 'aprobar') {
        // Crear usuario en la tabla principal
        $stmt = $conexion->prepare("SELECT * FROM solicitudes_registro WHERE id = ?");
        $stmt->bind_param("i", $solicitud_id);
        $stmt->execute();
        $solicitud = $stmt->get_result()->fetch_assoc();
        
        // Generar contraseña temporal
        $password_temporal = bin2hex(random_bytes(4)); // 8 caracteres
        $password_hash = password_hash($password_temporal, PASSWORD_DEFAULT);
        
        // Crear usuario
        $username = strtolower(str_replace(' ', '.', $solicitud['nombre_completo']));
        $rol = $solicitud['tipo_usuario'];
        
        $stmt_user = $conexion->prepare("
            INSERT INTO usuarios (username, password, nombre_completo, correo, rol, activo)
            VALUES (?, ?, ?, ?, ?, 1)
        ");
        $stmt_user->bind_param("sssss", $username, $password_hash, $solicitud['nombre_completo'], $solicitud['correo'], $rol);
        
        if ($stmt_user->execute()) {
            // Actualizar solicitud
            $stmt_update = $conexion->prepare("
                UPDATE solicitudes_registro 
                SET estado = 'aprobada', 
                    fecha_respuesta = NOW(), 
                    admin_respuesta = ?,
                    comentario_admin = ?,
                    password_hash = ?
                WHERE id = ?
            ");
            $stmt_update->bind_param("issi", $_SESSION['usuario_id'], $comentario, $password_temporal, $solicitud_id);
            $stmt_update->execute();
            
            // TODO: Enviar correo con credenciales
            $mensaje_exito = "Usuario aprobado. Contraseña temporal: $password_temporal";
        }
        
    } else if ($accion === 'rechazar') {
        $stmt = $conexion->prepare("
            UPDATE solicitudes_registro 
            SET estado = 'rechazada', 
                fecha_respuesta = NOW(), 
                admin_respuesta = ?,
                comentario_admin = ?
            WHERE id = ?
        ");
        $stmt->bind_param("isi", $_SESSION['usuario_id'], $comentario, $solicitud_id);
        $stmt->execute();
        
        $mensaje_exito = "Solicitud rechazada correctamente";
    }
}

// Obtener solicitudes pendientes
$query = "
    SELECT 
        s.*,
        COUNT(*) OVER() as total_pendientes
    FROM solicitudes_registro s
    WHERE s.estado = 'pendiente'
    ORDER BY s.fecha_solicitud DESC
";
$solicitudes = $conexion->query($query);
$total_pendientes = $solicitudes->num_rows > 0 ? $solicitudes->fetch_assoc()['total_pendientes'] : 0;
$solicitudes->data_seek(0);
?>

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
        }
        
        .notificacion-badge {
            background: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 1.2em;
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
        }
        
        .tipo-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9em;
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
            padding: 10px;
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
        }
        
        .acciones {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1em;
            transition: all 0.3s;
        }
        
        .btn-aprobar {
            background: #27ae60;
            color: white;
            flex: 1;
        }
        
        .btn-rechazar {
            background: #e74c3c;
            color: white;
            flex: 1;
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
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
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
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>📋 Solicitudes de Registro Pendientes</h1>
            <p>Administración de nuevos usuarios</p>
        </div>
        <div class="notificacion-badge">
            🔔 <?php echo $total_pendientes; ?> Pendientes
        </div>
    </div>
    
    <?php if (isset($mensaje_exito)): ?>
        <div style="background: #d4edda; padding: 15px; border-radius: 10px; margin-bottom: 20px; color: #155724;">
            ✅ <?php echo $mensaje_exito; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($total_pendientes > 0): ?>
        <?php while ($sol = $solicitudes->fetch_assoc()): ?>
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
        <?php endwhile; ?>
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
            <form method="POST">
                <input type="hidden" name="solicitud_id" id="solicitudId">
                <input type="hidden" name="accion" id="accionTipo">
                
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">
                    Comentario (opcional):
                </label>
                <textarea name="comentario" rows="4" placeholder="Agrega un comentario sobre esta decisión..."></textarea>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn" id="btnConfirmar" style="flex: 1;">
                        Confirmar
                    </button>
                    <button type="button" class="btn" onclick="cerrarModal()" style="flex: 1; background: #95a5a6;">
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
            
            document.getElementById('solicitudId').value = solicitudId;
            document.getElementById('accionTipo').value = accion;
            
            if (accion === 'aprobar') {
                titulo.textContent = '✅ Aprobar Solicitud de Registro';
                btnConfirmar.className = 'btn btn-aprobar';
                btnConfirmar.textContent = 'Aprobar y Crear Usuario';
            } else {
                titulo.textContent = '❌ Rechazar Solicitud de Registro';
                btnConfirmar.className = 'btn btn-rechazar';
                btnConfirmar.textContent = 'Confirmar Rechazo';
            }
            
            modal.style.display = 'flex';
        }
        
        function cerrarModal() {
            document.getElementById('modalAccion').style.display = 'none';
        }
        
        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalAccion');
            if (event.target === modal) {
                cerrarModal();
            }
        }
    </script>
</body>
</html>