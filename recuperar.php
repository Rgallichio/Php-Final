<?php
include "db.php";
session_start();

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);

    if (!empty($nombre) && !empty($apellido)) {
        $sql = "SELECT dni, nombre, apellido FROM users WHERE LOWER(nombre) = LOWER(?) AND LOWER(apellido) = LOWER(?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $apellido);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $mensaje = "Usuario encontrado: " . htmlspecialchars($user['nombre']) . " " . htmlspecialchars($user['apellido']) . " - DNI: " . htmlspecialchars($user['dni']);
        } else {
            $error = "No se encontró ningún usuario con ese nombre y apellido.";
        }
        $stmt->close();
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Usuario - Compañía de Ingenieros QBN 601</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-soldier">
    <header>
              <img src="img/fondurri.png" class="military-header">

        <div class="header-content">
            <div class="unit-info">


            </div>
            <div class="nav-buttons">
    </header>

    <main class="main-content">
        <div class="auth-container">
            <div class="card">
                <div class="card-header">
                    <h3>RECUPERAR DATOS DE USUARIO</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($mensaje)): ?>
                        <div class="alert alert-success">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="nombre" class="form-label">NOMBRE:</label>
                            <input type="text" id="nombre" name="nombre" class="form-input"
                                placeholder="Ingrese su nombre" required>
                        </div>

                        <div class="form-group">
                            <label for="apellido" class="form-label">APELLIDO:</label>
                            <input type="text" id="apellido" name="apellido" class="form-input"
                                placeholder="Ingrese su apellido" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">BUSCAR USUARIO</button>
                    </form>

                    <div class="auth-links">
                        <a href="login.php" class="btn btn-link">Volver al login</a>
                        <a href="register.php" class="btn btn-link">Registrarse</a>
                        <a href="index.php" class="btn btn-link">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>