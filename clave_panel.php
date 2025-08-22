<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clave_ingresada = $_POST['clave'] ?? '';

    if ($clave_ingresada === '12345') {
        $_SESSION['acceso_panel'] = true;
        header("Location: panel.php");
        exit();
    } else {
        $error = "Clave incorrecta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Panel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Acceso al Panel del Encargado</h2>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Ingrese la clave de acceso:</label><br><br>
        <input type="password" name="clave" required>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
