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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Compañía Nº 38</title>
  <link rel="stylesheet" href="style2.css" />
</head>
<body>

  <div class="top-bar">
    <h1>Compañía Nº 38</h1>
    <div class="nav-buttons">
      <?php if (!$logueado): ?>
        <a href="register.php">Registrarse</a>
        <a href="login.php">Iniciar Sesión</a>
      <?php else: ?>
        <img class="verificado" src="tilde.png" alt="Verificado">
      <?php endif; ?>
    </div>
  </div>

  <?php if ($logueado): ?>
    <?php if ($alerta_ok): ?>
      <script> alert(<?php echo json_encode($alerta_ok); ?>); </script>
    <?php endif; ?>
    <?php if ($alerta_error): ?>
      <script> alert(<?php echo json_encode($alerta_error); ?>); </script>
    <?php endif; ?>

    <div class="main-content">
      <h2>Elegir Vianda</h2>

      <?php if (!$bloqueado): ?>
        <!-- Mostrar opciones SOLO si NO hay pedido en últimas 12h -->
        <form action="elegir_vianda.php" method="POST">
          <div class="vianda-options">
            <label><input type="checkbox" name="vianda[]" value="almuerzo" /> Almuerzo</label>
            <label><input type="checkbox" name="vianda[]" value="cena" /> Cena</label>
          </div>
          <button type="submit" class="submit-button">Enviar</button>
        </form>
      <?php else: ?>
        <!-- Bloqueado: ocultar "Elegir vianda" y mostrar solo "Cancelar viandas" -->
        <p class="mensaje-bloqueo">Ya hiciste un pedido. Solo podés hacer otro luego de 12 horas.</p>
      <?php endif; ?>

      <h2>Cancelar Viandas</h2>
      <form action="cancelar_vianda.php" method="POST">
        <div class="cancel-options">
          <label><input type="checkbox" name="cancelar[]" value="almuerzo" /> Almuerzo</label>
          <label><input type="checkbox" name="cancelar[]" value="cena" /> Cena</label>
        </div>
        <button type="submit" class="submit-button cancel-button">Cancelar</button>
      </form>
    </div>

    <!-- Panel del encargado en página distinta -->
    <!-- <a href="clave_panel.php"><button class="panel-button">Ver Panel del Encargado</button></a> -->

  <?php endif; ?>

</body>
</html>
