<?php
session_start();
include "db.php";
include "utils_viandas.php";

if (!isset($_SESSION['dni'])) {
    header("Location: login.php");
    exit();
}

ensure_vianda_utils($conn);

$dni = $_SESSION['dni'];
$viandas = $_POST['vianda'] ?? [];

if (!empty($viandas)) {
    $user_id = get_user_id_by_dni($conn, $dni);
    if ($user_id) {

        // Bloqueo por 12 horas si ya pidió recientemente (aunque haya cancelado)
        if (en_cooldown_12h($conn, $user_id) || tiene_pedido_ult_12h($conn, $user_id)) {
            $_SESSION['alerta_error'] = "Ya realizaste un pedido recientemente. Solo podés hacer otro pasadas 12 horas.";
            header("Location: index.php");
            exit();
        }

        foreach ($viandas as $tipo) {
            $tipo = $conn->real_escape_string($tipo);
            // Insert con fecha por defecto (CURRENT_TIMESTAMP)
            $conn->query("INSERT INTO viandas (user_id, tipo) VALUES ($user_id, '$tipo')");
        }

        // Registrar cooldown
        registrar_ultimo_pedido($conn, $user_id);

        // Mensaje de éxito (estilo 'PedidoYa')
        $_SESSION['alerta_ok'] = "Pedido enviado con éxito ✔️";
        header("Location: index.php?success=1");
        exit();
    }
}

header("Location: index.php");
exit();
?>