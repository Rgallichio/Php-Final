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
        $_SESSION['registro_exitoso'] = "Registro completado exitosamente. Puede iniciar sesión con sus credenciales.";
        header("Location: login.php");
        exit();
    } else {
        $error = "Error en el registro: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Compañía de Ingenieros QBN 601</title>
    <link rel="stylesheet" href="style.css">
</head>
<!-- Added military background class -->

<body class="bg-helicopters">

    <!-- Added military header structure -->

    <header>
              <img src="img/fondurri.png" class="military-header">

        <div class="header-content">
            <div class="unit-info">

                </h1>
            </div>
        <div class="nav-buttons">
    </header>

    <!-- Updated main content with military card styling -->
    <main class="main-content">
        <div class="auth-container">
            <div class="card">
                <div class="card-header">
                    <h3>FORMULARIO DE REGISTRO</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="auth-form">
                        <div class="form-row">
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
                        </div>

                        <div class="form-group">
                            <label for="correo" class="form-label">CORREO ELECTRÓNICO:</label>
                            <input type="email" id="correo" name="correo" class="form-input"
                                placeholder="Ingrese su correo electrónico" required>
                        </div>

                        <div class="form-group">
                            <label for="dni" class="form-label">DNI:</label>
                            <input type="text" id="dni" name="dni" class="form-input" placeholder="Ingrese su DNI"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="seccion" class="form-label">SECCIÓN:</label>
                            <select id="seccion" name="seccion" class="form-input" required>
                                <option value="">Seleccione una sección</option>
                                <option value="Sec. Comando y Servicio">Sec. Comando y Servicio</option>
                                <option value="Descontaminación">Descontaminación</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contrasena" class="form-label">CONTRASEÑA:</label>
                            <input type="password" id="contrasena" name="contrasena" class="form-input"
                                placeholder="Ingrese su contraseña" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-full">REGISTRARSE</button>
                    </form>

                    <div class="auth-links">
                        <a href="login.php" class="btn btn-link">¿Ya tiene cuenta? Iniciar sesión</a>
                        <a href="recuperar.php" class="btn btn-link">Recuperar datos de acceso</a>
                        <a href="index.php" class="btn btn-link">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>