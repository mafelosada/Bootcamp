<?php
$host = "localhost";            // o 127.0.0.1
$usuario = "root";              // tu usuario de MySQL
$contraseña = "";               // tu contraseña (si no tiene, déjala vacía)
$bd = "animaldex";          // nombre de tu base de datos

$conn = new mysqli($host, $usuario, $contraseña, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
