<!DOCTYPE html>
<html>
<head>
    <title>Test Click</title>
    <style>
        body { padding: 50px; background: #1a1a2e; color: white; font-family: Arial; }
        .btn { padding: 15px 30px; margin: 10px; background: #4ade80; color: #000; 
               border: none; border-radius: 10px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #22c55e; }
    </style>
</head>
<body>
    <h1>🧪 TEST DE CLICKS</h1>
    
    <h2>Test 1: Link normal</h2>
    <a href="index.php?ruta=prestamos&accion=crear" class="btn">Click aquí (LINK)</a>
    
    <h2>Test 2: Botón con onclick</h2>
    <button onclick="window.location.href='index.php?ruta=prestamos&accion=crear'" class="btn">
        Click aquí (BUTTON onclick)
    </button>
    
    <h2>Test 3: Botón con JavaScript</h2>
    <button class="btn" id="testBtn">Click aquí (BUTTON JS)</button>
    
    <h2>Test 4: Link directo</h2>
    <a href="index.php?ruta=prestamos" class="btn">Ir a Préstamos (sin acción)</a>
    
    <script>
        document.getElementById('testBtn').addEventListener('click', function() {
            alert('Click detectado! Redirigiendo...');
            window.location.href = 'index.php?ruta=prestamos&accion=crear';
        });
    </script>
</body>
</html>