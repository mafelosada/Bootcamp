<?php
include('conexion.php');
session_start();

// Si game_id se pasó por GET, úsalo
if (isset($_GET['game_id'])) {
    $_SESSION['game_id'] = $_GET['game_id'];
}

$gameId = $_SESSION['game_id'] ?? null;
if (!$gameId) {
    echo "No se encontró partida activa.";
    exit;
}

// Obtener jugadores de la partida
$sql = "SELECT gp.gameplayer_id, p.name
        FROM gameplayer gp
        JOIN player p ON p.player_id = gp.player_id
        WHERE gp.game_id = $gameId";
$result = $conn->query($sql);
$jugadores = [];

while ($row = $result->fetch_assoc()) {
    $jugadores[] = [
        'gameplayer_id' => $row['gameplayer_id'],
        'name' => $row['name']
    ];
}

// Obtener 8 cartas por jugador con todos los datos
$cartasPorJugador = [];
foreach ($jugadores as $jugador) {
    $gpId = $jugador['gameplayer_id'];
    $res = $conn->query("SELECT c.*
        FROM gameplayercard gpc
        JOIN card c ON c.card_id = gpc.card_id
        WHERE gpc.gameplayer_id = $gpId
        ORDER BY gpc.round_number ASC
        LIMIT 8");

    $cartas = [];
    while ($fila = $res->fetch_assoc()) {
        $cartas[] = $fila;
    }

    $cartasPorJugador[$gpId] = $cartas;
}
?>