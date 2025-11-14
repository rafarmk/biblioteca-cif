<?php
// Contar solicitudes pendientes
$stmt = $conexion->prepare("SELECT COUNT(*) as total FROM solicitudes_registro WHERE estado = 'pendiente'");
$stmt->execute();
$pendientes = $stmt->get_result()->fetch_assoc()['total'];
?>

<?php if ($pendientes > 0): ?>
<div style="
    position: fixed;
    top: 20px;
    right: 20px;
    background: #e74c3c;
    color: white;
    padding: 15px 25px;
    border-radius: 50px;
    box-shadow: 0 5px 20px rgba(231, 76, 60, 0.4);
    cursor: pointer;
    z-index: 999;
    animation: shake 3s infinite;
    font-weight: 600;
" onclick="window.location.href='admin/solicitudes_pendientes.php'">
    🔔 <?php echo $pendientes; ?> Solicitudes Pendientes
</div>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}
</style>
<?php endif; ?>