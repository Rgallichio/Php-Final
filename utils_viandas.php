<?php
function ensure_vianda_utils($conn) {
    // Tabla para almacenar el último momento en que el usuario HIZO un pedido (aunque luego cancele)
    $conn->query("
        CREATE TABLE IF NOT EXISTS ultimo_pedido (
            user_id INT PRIMARY KEY,
            ultimo TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_ultimo_pedido_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
}

function get_user_id_by_dni($conn, $dni) {
    $dni = $conn->real_escape_string($dni);
    $res = $conn->query("SELECT id FROM users WHERE dni = '$dni' LIMIT 1");
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        return intval($row['id']);
    }
    return null;
}

function tiene_pedido_ult_12h($conn, $user_id) {
    // Revisa pedidos registrados en 'viandas' en las últimas 12 horas
    $sql = "SELECT 1 FROM viandas WHERE user_id = $user_id AND fecha >= (NOW() - INTERVAL 12 HOUR) LIMIT 1";
    $res = $conn->query($sql);
    return ($res && $res->num_rows > 0);
}

function en_cooldown_12h($conn, $user_id) {
    // Revisa la tabla 'ultimo_pedido' para aplicar el cooldown aunque el usuario cancele
    $sql = "SELECT ultimo, (NOW() < DATE_ADD(ultimo, INTERVAL 12 HOUR)) AS bloqueado
            FROM ultimo_pedido WHERE user_id = $user_id LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows === 1) {
        $row = $res->fetch_assoc();
        return intval($row['bloqueado']) === 1;
    }
    return false;
}

function registrar_ultimo_pedido($conn, $user_id) {
    // Upsert del timestamp del último pedido
    $conn->query("INSERT INTO ultimo_pedido (user_id, ultimo) VALUES ($user_id, NOW())
                  ON DUPLICATE KEY UPDATE ultimo = NOW()");
}
?>
