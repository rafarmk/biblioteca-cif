<?php
if (!isset($_SESSION['logueado']) || $_SESSION['logueado'] !== true) {
    header('Location: index.php?ruta=login');
    exit();
}

require_once __DIR__ . '/../layouts/navbar.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Biblioteca CIF</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1f3a 0%, #2d3561 100%);
            min-height: 100vh;
            color: #fff;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 80px 20px 40px;
        }
        
        .card {
            background: rgba(45, 53, 97, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(91, 143, 215, 0.2);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }
        
        h1 {
            color: #5b8fd7;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #b8c5d6;
            font-weight: 600;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            background: rgba(26, 31, 58, 0.5);
            border: 1px solid rgba(91, 143, 215, 0.3);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #5b8fd7;
        }
        
        .btn {
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #5b8fd7 0%, #4a7bc4 100%);
            color: white;
        }
        
        .btn-secondary {
            background: rgba(91, 143, 215, 0.2);
            color: #5b8fd7;
            border: 2px solid #5b8fd7;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        
        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-danger {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>✏️ Editar Usuario</h1>
            
            <?php if (isset($_SESSION['mensaje'])): ?>
                <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>">
                    <?= htmlspecialchars($_SESSION['mensaje']['texto']) ?>
                </div>
                <?php unset($_SESSION['mensaje']); ?>
            <?php endif; ?>
            
            <form method="POST" action="index.php?ruta=usuarios&accion=editar&id=<?= $usuario['id'] ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
                
                <div class="row">
                    <div class="form-group">
                        <label>Nombre Completo *</label>
                        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="tel" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>DUI</label>
                        <input type="text" name="dui" value="<?= htmlspecialchars($usuario['dui'] ?? '') ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label>Tipo de Usuario *</label>
                        <select name="tipo_usuario" required>
                            <option value="administrador" <?= $usuario['tipo_usuario'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                            <option value="gestionador" <?= $usuario['tipo_usuario'] === 'gestionador' ? 'selected' : '' ?>>Gestionador</option>
                            <option value="personal_administrativo" <?= $usuario['tipo_usuario'] === 'personal_administrativo' ? 'selected' : '' ?>>Personal Administrativo</option>
                            <option value="personal_operativo" <?= $usuario['tipo_usuario'] === 'personal_operativo' ? 'selected' : '' ?>>Personal Operativo</option>
                            <option value="estudiante_mayor" <?= $usuario['tipo_usuario'] === 'estudiante_mayor' ? 'selected' : '' ?>>Estudiante Mayor</option>
                            <option value="estudiante_menor" <?= $usuario['tipo_usuario'] === 'estudiante_menor' ? 'selected' : '' ?>>Estudiante Menor</option>
                            <option value="visitante" <?= $usuario['tipo_usuario'] === 'visitante' ? 'selected' : '' ?>>Visitante</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Estado *</label>
                        <select name="estado" required>
                            <option value="activo" <?= $usuario['estado'] === 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="pendiente" <?= $usuario['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="inactivo" <?= $usuario['estado'] === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                            <option value="suspendido" <?= $usuario['estado'] === 'suspendido' ? 'selected' : '' ?>>Suspendido</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Dirección</label>
                    <textarea name="direccion" rows="3"><?= htmlspecialchars($usuario['direccion'] ?? '') ?></textarea>
                </div>
                
                <div class="row">
                    <div class="form-group">
                        <label>Días Máximos de Préstamo</label>
                        <input type="number" name="dias_max_prestamo" value="<?= htmlspecialchars($usuario['dias_max_prestamo'] ?? 7) ?>" min="1">
                    </div>
                    
                    <div class="form-group">
                        <label>Máximo de Libros Simultáneos</label>
                        <input type="number" name="max_libros_simultaneos" value="<?= htmlspecialchars($usuario['max_libros_simultaneos'] ?? 3) ?>" min="1">
                    </div>
                </div>
                
                <div class="form-group">
                    <label style="display:flex;align-items:center;gap:10px;">
                        <input type="checkbox" name="puede_prestar" value="1" <?= ($usuario['puede_prestar'] ?? 1) ? 'checked' : '' ?> style="width:auto;">
                        Puede realizar préstamos
                    </label>
                </div>
                
                <div style="display:flex;gap:15px;margin-top:30px;">
                    <button type="submit" class="btn btn-primary">
                        ✅ Actualizar Usuario
                    </button>
                    <a href="index.php?ruta=usuarios" class="btn btn-secondary">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>