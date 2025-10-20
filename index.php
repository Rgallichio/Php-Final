<?php
session_start();
require 'db.php';
require 'utils_viandas.php';

$logueado = isset($_SESSION['dni']);
$user_id = null;
$bloqueado = false;
$pedido_activo = false;

if ($logueado) {
  ensure_vianda_utils($conn);
  $user_id = get_user_id_by_dni($conn, $_SESSION['dni']);
  if ($user_id) {
    $pedido_activo = tiene_pedido_ult_12h($conn, $user_id);
    $bloqueado = $pedido_activo || en_cooldown_12h($conn, $user_id);
  }
}

// Gestionar alertas (éxito / error) una sola vez
$alerta_ok = $_SESSION['alerta_ok'] ?? null;
$alerta_error = $_SESSION['alerta_error'] ?? null;
unset($_SESSION['alerta_ok'], $_SESSION['alerta_error']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    Compañía de Ingenieros QBN Apoyo a las Emergencias 601</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body class="bg-patrol">

<h1 class="titulo-principal">
          <img src="img/fondurri.png"
  <header class="military-header">
    <div class="header-content">
      <div class="unit-info">
         <br>
          <div class="subtitulo">
          <img src="img/qbn.png" alt="Logo QBN" class="logo-qbn">
        </h1>
      </div>
      <div class="nav-buttons">
        <?php if (!$logueado): ?>
        <?php else: ?>
          <div class="user-status">
            <span class="status-verified">✓ VERIFICADO</span>
            <a href="logout.php" class="btn btn-secondary">CERRAR SESIÓN</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <?php if ($logueado): ?>
    <?php if ($alerta_ok): ?>
      <div class="alert alert-success">
        <?php echo htmlspecialchars($alerta_ok); ?>
      </div>
    <?php endif; ?>
    <?php if ($alerta_error): ?>
      <div class="alert alert-error">
        <?php echo htmlspecialchars($alerta_error); ?>
      </div>
    <?php endif; ?>

    <!-- Updated main content with military card styling -->
    <main class="main-content">
      <div class="card">
        <div class="card-header">
          <h3>SELECCIÓN DE VIANDAS</h3>
        </div>

        <div class="card-body">
          <?php if (!$bloqueado): ?>
            <form action="elegir_vianda.php" method="POST" class="vianda-form">
              <div class="form-group">
                <label class="form-label">Seleccione las viandas deseadas:</label>
                <div class="checkbox-group">
                  <label class="checkbox-item">
                    <input type="checkbox" name="vianda[]" value="almuerzo" />
                    <span class="checkmark"></span>
                    ALMUERZO
                  </label>
                  <label class="checkbox-item">
                    <input type="checkbox" name="vianda[]" value="cena" />
                    <span class="checkmark"></span>
                    CENA
                  </label>
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-full">CONFIRMAR PEDIDO</button>
            </form>
          <?php else: ?>
            <div class="alert alert-warning">
              <strong>PEDIDO ACTIVO:</strong> Ya realizó un pedido. Podrá realizar otro después de 12 horas.
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>CANCELAR VIANDAS</h3>
        </div>
        <div class="card-body">
          <form action="cancelar_vianda.php" method="POST" class="vianda-form">
            <div class="form-group">
              <label class="form-label">Seleccione las viandas a cancelar:</label>
              <div class="checkbox-group">
                <label class="checkbox-item">
                  <input type="checkbox" name="cancelar[]" value="almuerzo" />
                  <span class="checkmark"></span>
                  ALMUERZO
                </label>
                <label class="checkbox-item">
                  <input type="checkbox" name="cancelar[]" value="cena" />
                  <span class="checkmark"></span>
                  CENA
                </label>
              </div>
            </div>
            <button type="submit" class="btn btn-destructive btn-full">CANCELAR VIANDAS</button>
          </form>
        </div>
      </div>

      <!-- Updated panel access button with military styling -->

    </main>
  <?php else: ?>
    <!-- Added welcome section for non-logged users -->
    <main class="main-content">
      <div class="welcome-section">
        <div class="card">
          <div class="card-body text-center">
            <h3>BIENVENIDO AL SISTEMA DE VIANDAS</h3>
            <p>Para acceder al sistema, debe iniciar sesión con sus credenciales.</p>
            <p>Si no posee una cuenta, puede registrarse o recuperar sus datos de acceso.</p>
            <div class="main-content-index">
              <a href="login.php" class="btn btn-primary">INICIAR SESIÓN</a>
              
              <a href="register.php" class="btn btn-secondary">REGISTRARSE</a>
              <a href="recuperar.php" class="btn btn-secondary-MOD">RECUPERAR USUARIO</a>

            </div>
          </div>
        </div>
      </div>
    </main>
  <?php endif; ?>

</body>

</html>