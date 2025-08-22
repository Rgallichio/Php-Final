<?php
session_start();

if (!isset($_SESSION['acceso_panel']) || $_SESSION['acceso_panel'] !== true) {
    header("Location: clave_panel.php");
    exit();
}

require 'db.php';

$busqueda_nombre = $_GET['nombre'] ?? '';
$busqueda_fecha = $_GET['fecha'] ?? '';

$sql = "SELECT u.nombre, u.dni, v.fecha, v.tipo, COUNT(*) as cantidad
        FROM viandas v
        JOIN users u ON v.user_id = u.id
        WHERE 1";

if (!empty($busqueda_nombre)) {
    $nombre = $conn->real_escape_string($busqueda_nombre);
    $sql .= " AND u.nombre LIKE '%$nombre%'";
}

if (!empty($busqueda_fecha)) {
    $fecha = $conn->real_escape_string($busqueda_fecha);
    $sql .= " AND DATE(v.fecha) = '$fecha'";
}

$sql .= " GROUP BY u.id, DATE(v.fecha), v.tipo
          ORDER BY v.fecha DESC";

$result = $conn->query($sql);
$resumen_diario = [];

while ($row = $result->fetch_assoc()) {
    $nombre = $row['nombre'];
    $dni = $row['dni'];
    $fecha = date('Y-m-d', strtotime($row['fecha']));
    $tipo = ucfirst($row['tipo']);
    $cantidad = $row['cantidad'];
    $costo_total = $cantidad * 1500;

    $resumen_diario[$fecha][] = [
        'nombre' => $nombre,
        'dni' => $dni,
        'tipo' => $tipo,
        'cantidad' => $cantidad,
        'costo' => $costo_total
    ];
}

$sql_resumen_mensual = "
    SELECT u.nombre, u.dni, MONTH(v.fecha) AS mes, YEAR(v.fecha) AS anio, COUNT(*) AS total_viandas
    FROM viandas v
    JOIN users u ON v.user_id = u.id
    GROUP BY u.id, YEAR(v.fecha), MONTH(v.fecha)
    ORDER BY anio DESC, mes DESC
";

$mensual_result = $conn->query($sql_resumen_mensual);
$resumen_mensual = [];

while ($row = $mensual_result->fetch_assoc()) {
    $resumen_mensual[] = [
        'nombre' => $row['nombre'],
        'dni' => $row['dni'],
        'mes' => $row['mes'],
        'anio' => $row['anio'],
        'cantidad' => $row['total_viandas'],
        'total' => $row['total_viandas'] * 1500
    ];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Encargado</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Panel del Encargado - Registro de Viandas</h1>

<!-- Filtros -->
<div class="filtros">
    <form method="GET">
        <label>Buscar por nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($busqueda_nombre) ?>">

        <label>Filtrar por fecha:</label>
        <input type="date" name="fecha" value="<?= htmlspecialchars($busqueda_fecha) ?>">

        <button type="submit">Buscar</button>
        <a href="panel.php"><button type="button">Reiniciar</button></a>
    </form>
</div>

<!-- Tabla diaria -->
<?php if (empty($resumen_diario)): ?>
    <p>No se encontraron viandas para los filtros aplicados.</p>
<?php else: ?>
    <?php foreach ($resumen_diario as $fecha => $viandas): ?>
        <h2>Fecha: <?= $fecha ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Tipo de Vianda</th>
                    <th>Cantidad</th>
                    <th>Total a Descontar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_del_dia = 0;
                foreach ($viandas as $v):
                    $total_del_dia += $v['costo'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($v['nombre']) ?></td>
                        <td><?= htmlspecialchars($v['dni']) ?></td>
                        <td><?= $v['tipo'] ?></td>
                        <td><?= $v['cantidad'] ?></td>
                        <td class="costo">$<?= number_format($v['costo'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Total del Día:</strong></td>
                    <td class="costo"><strong>$<?= number_format($total_del_dia, 2, ',', '.') ?></strong></td>
                </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Resumen mensual -->
<h2>Total Descontado por Usuario por Mes</h2>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Mes</th>
            <th>Año</th>
            <th>Cantidad de Viandas</th>
            <th>Total Descontado</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resumen_mensual as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nombre']) ?></td>
                <td><?= htmlspecialchars($item['dni']) ?></td>
                <td><?= $item['mes'] ?></td>
                <td><?= $item['anio'] ?></td>
                <td><?= $item['cantidad'] ?></td>
                <td class="costo">$<?= number_format($item['total'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>

