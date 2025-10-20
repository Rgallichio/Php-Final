<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $contrasena = $_POST['contrasena'];

    $sql = "SELECT * FROM users WHERE dni = '$dni'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($contrasena, $user['contrasena'])) {
            $_SESSION['dni'] = $user['dni'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "DNI no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Compañía de Ingenieros QBN 601</title>
    <link rel="stylesheet" href="style.css">
</head>
<!-- Added bg-soldier class for military background -->

<body class="bg-soldier">
    <!-- Updated header with military styling -->


    <header class="military-header">
        <div class="header-content">
            <div class="unit-info">
                <h1 class="titulo-principal">
                    <h1 class="titulo-principal">
          <img src="img/fondurri.png"</h1>
                </h1>
            </div>
            <div class="nav-buttons">
    </header>

    <!-- Updated login form with military card styling -->
    <main class="main-content">
        <div class="auth-container">
            <div class="card">
                <div class="card-header">
                    <h3>INICIAR SESIÓN</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" id="dni" name="dni" class="form-input" placeholder="Ingrese su DNI"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="contrasena" class="form-label">CONTRASEÑA:</label>
                            <input type="password" id="contrasena" name="contrasena" class="form-input"
                                placeholder="Ingrese su contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">INGRESAR</button>
                    </form>

                    <div class="auth-links">
                        <a href="register.php" class="btn btn-link">¿No tiene cuenta? Registrarse</a>
                        <a href="recuperar.php" class="btn btn-link">Recuperar datos de acceso</a>
                        <a href="index.php" class="btn btn-link">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>

<!-- Updated login form with military card styling -->
<main class="main-content">
    <div class="auth-container">
        <div class="card">
            <div class="card-header">
                <h3>INICIAR SESIÓN</h3>
            </div>
            <div class="card-body">
                <div class="">
                    <a href="clave_panel.php" class="btn btn-secondary btn-full">ACCESO PANEL DEL ENCARGADO</a>
                </div>
                <?php if (isset($_SESSION['registro_exitoso'])): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($_SESSION['registro_exitoso']); ?>
                    </div>
                    <?php unset($_SESSION['registro_exitoso']); ?>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>