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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Panel - Compañía de Ingenieros QBN 601</title>
    <link rel="stylesheet" href="style.css">
</head>
<!-- Added bg-helicopters class for military helicopter background -->
<body class="bg-helicopters">
    <!-- Updated header with military styling -->
    <header class="military-header">
        <div class="header-content">
            <div class="unit-info">
                <h1>ACCESO RESTRINGIDO</h1>
                <h2>PANEL DEL ENCARGADO</h2>
                <p>COMPAÑÍA DE INGENIEROS QBN 601</p>
            </div>
        </div>
    </header>

    <!-- Updated access form with military card styling -->
    <main class="main-content">
        <div class="auth-container">
            <div class="card">
                <div class="card-header">
                    <h3>VERIFICACIÓN DE ACCESO</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-error">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="clave" class="form-label">CLAVE DE ACCESO:</label>
                            <input type="password" id="clave" name="clave" class="form-input" placeholder="Ingrese la clave de acceso" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-full">ACCEDER AL PANEL</button>
                    </form>
                    
                    <div class="auth-links">
                        <a href="index.php" class="btn btn-link">Volver al inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
