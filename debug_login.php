<?php
session_start();
require_once __DIR__ . '/config/Database.php';

$database = new Database();
$db = $database->getConnection();

$email = 'admin@biblioteca.com';
$password = 'admin123';

echo "<h2>🔍 DEBUG LOGIN COMPLETO</h2>";
echo "<hr>";

// 1. VERIFICAR CONEXIÓN
echo "<h3>1️⃣ Verificando conexión a base de datos...</h3>";
try {
    $db->query("SELECT 1");
    echo "<p style='color: green;'>✅ Conexión exitosa</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// 2. BUSCAR USUARIO
echo "<h3>2️⃣ Buscando usuario en la base de datos...</h3>";
echo "<p>Email buscado: <strong>$email</strong></p>";

$stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "<p style='color: red;'>❌ Usuario NO encontrado</p>";
    
    // Mostrar usuarios existentes
    echo "<h3>📋 Usuarios en la base de datos:</h3>";
    $stmt = $db->query("SELECT id, nombre, apellido, email, tipo_usuario, estado FROM usuarios LIMIT 10");
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Tipo</th><th>Estado</th></tr>";
    while ($u = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$u['id']}</td>";
        echo "<td>{$u['nombre']} {$u['apellido']}</td>";
        echo "<td>{$u['email']}</td>";
        echo "<td>{$u['tipo_usuario']}</td>";
        echo "<td>{$u['estado']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<hr>";
    echo "<h3>🔧 SQL para crear el administrador:</h3>";
    $nuevo_hash = password_hash('admin123', PASSWORD_DEFAULT);
    echo "<pre>";
    echo "DELETE FROM usuarios WHERE email = 'admin@biblioteca.com';\n\n";
    echo "INSERT INTO usuarios (nombre, apellido, email, password, tipo_usuario, estado) \n";
    echo "VALUES ('Administrador', 'Sistema', 'admin@biblioteca.com', '$nuevo_hash', 'administrador', 'activo');";
    echo "</pre>";
    
} else {
    echo "<p style='color: green;'>✅ Usuario encontrado</p>";
    
    // 3. MOSTRAR DATOS DEL USUARIO
    echo "<h3>3️⃣ Datos del usuario:</h3>";
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>Campo</th><th>Valor</th></tr>";
    foreach ($usuario as $campo => $valor) {
        if ($campo === 'password') {
            echo "<tr><td>$campo</td><td>" . substr($valor, 0, 30) . "...</td></tr>";
        } else {
            echo "<tr><td>$campo</td><td>$valor</td></tr>";
        }
    }
    echo "</table>";
    
    // 4. VERIFICAR PASSWORD
    echo "<h3>4️⃣ Verificando contraseña...</h3>";
    echo "<p>Contraseña ingresada: <strong>$password</strong></p>";
    echo "<p>Hash en BD: <code>" . substr($usuario['password'], 0, 50) . "...</code></p>";
    
    $verifica = password_verify($password, $usuario['password']);
    
    if ($verifica) {
        echo "<p style='color: green; font-size: 20px;'>✅ ¡PASSWORD CORRECTO!</p>";
    } else {
        echo "<p style='color: red; font-size: 20px;'>❌ PASSWORD INCORRECTO</p>";
        
        // Generar nuevo hash
        echo "<h3>🔧 Generar nuevo hash:</h3>";
        $nuevo_hash = password_hash($password, PASSWORD_DEFAULT);
        echo "<p>Nuevo hash generado:</p>";
        echo "<pre>$nuevo_hash</pre>";
        echo "<h3>SQL para actualizar:</h3>";
        echo "<pre>UPDATE usuarios SET password = '$nuevo_hash' WHERE email = 'admin@biblioteca.com';</pre>";
    }
    
    // 5. VERIFICAR ESTADO
    echo "<h3>5️⃣ Verificando estado de la cuenta...</h3>";
    if ($usuario['estado'] === 'activo') {
        echo "<p style='color: green;'>✅ Estado: ACTIVO</p>";
    } else {
        echo "<p style='color: red;'>❌ Estado: {$usuario['estado']}</p>";
        echo "<p>La cuenta debe estar en estado 'activo' para poder iniciar sesión</p>";
        echo "<h3>SQL para activar:</h3>";
        echo "<pre>UPDATE usuarios SET estado = 'activo' WHERE email = 'admin@biblioteca.com';</pre>";
    }
    
    // 6. VERIFICAR TIPO DE USUARIO
    echo "<h3>6️⃣ Verificando tipo de usuario...</h3>";
    echo "<p>Tipo actual: <strong>{$usuario['tipo_usuario']}</strong></p>";
    if ($usuario['tipo_usuario'] === 'administrador') {
        echo "<p style='color: green;'>✅ Es administrador</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ NO es administrador (irá al catálogo)</p>";
    }
}

echo "<hr>";
echo "<h3>🎯 RESUMEN:</h3>";
if (!$usuario) {
    echo "<p style='color: red; font-size: 18px;'>❌ El usuario no existe. Ejecuta el SQL de arriba para crearlo.</p>";
} else {
    if (!$verifica) {
        echo "<p style='color: red; font-size: 18px;'>❌ La contraseña es incorrecta. Ejecuta el SQL de arriba para actualizarla.</p>";
    } elseif ($usuario['estado'] !== 'activo') {
        echo "<p style='color: red; font-size: 18px;'>❌ La cuenta está inactiva. Ejecuta el SQL de arriba para activarla.</p>";
    } else {
        echo "<p style='color: green; font-size: 18px;'>✅ TODO CORRECTO - El login debería funcionar</p>";
    }
}
?>