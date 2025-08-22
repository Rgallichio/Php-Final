<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "login_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>