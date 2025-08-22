<?php
session_start();
include "db.php";
include "utils_viandas.php";

if (!isset($_SESSION['dni'])) {
    header("Location: login.php");
    exit();
}

$dni = $_SESSION['dni'];
$cancelar = $_POST['cancelar'] ?? [];

if (!empty($cancelar)) {
    $user_id = null;
    $dni_esc = $conn->real_escape_string($dni);
    $res_user = $conn->query("SELECT id FROM users WHERE dni = '$dni_esc' LIMIT 1");
    if ($res_user && $res_user->num_rows === 1) {
        $user_id = intval($res_user->fetch_assoc()['id']);
        foreach ($cancelar as $tipo) {
            $tipo = $conn->real_escape_string($tipo);
            // Eliminamos los pedidos de las últimas 12 horas del tipo seleccionado
            $conn->query("DELETE FROM viandas WHERE user_id = $user_id AND tipo = '$tipo' AND fecha >= (NOW() - INTERVAL 12 HOUR)");
        }
        // Importante: NO tocamos 'ultimo_pedido' para mantener el cooldown
        $_SESSION['alerta_ok'] = "Se canceló la vianda seleccionada.";
    }
}

header("Location: index.php");
exit();
?>