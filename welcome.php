<?php
session_start();

// Si no hay sesión activa, redirige a login
if (!isset($_SESSION['dni'])) {
    header("Location: login.php");
    exit();
}

// Si hay sesión, redirige al index con parámetro de éxito
header("Location: index.html?success=1");
exit();
?>