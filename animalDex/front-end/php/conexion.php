<?php
$host = "localhost";           
$usuario = "root";            
$contraseña = "";           
$bd = "animaldex";         

$conn = new mysqli($host, $usuario, $contraseña, $bd);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

?>
