<?php
include "db.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $dni = $_POST['dni'];
    $seccion = $_POST['seccion'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (nombre, apellido, correo, dni, seccion, contrasena)
            VALUES ('$nombre', '$apellido', '$correo', '$dni', '$seccion', '$contrasena')";

    if ($conn->query($sql) === TRUE) {
        // ✅ Mostrar mensaje de éxito
        echo "<p>✅ Registro exitoso. <a href='login.php'>Inicie sesión aquí</a></p>";
        exit();
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required><br>
        <input type="text" name="apellido" placeholder="Apellido" required><br>
        <input type="email" name="correo" placeholder="Correo electrónico" required><br>
        <input type="text" name="dni" placeholder="DNI" required><br>
        <select name="seccion" required>
            <option value="">Seleccione una sección</option>
            <option value="Sec. Comando y Servicio">Sec. Comando y Servicio</option>
            <option value="Descontaminación">Descontaminación</option>
            <option value="Otro">Otro</option>
        </select><br>
        <input type="password" name="contrasena" placeholder="Contraseña" required><br>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
