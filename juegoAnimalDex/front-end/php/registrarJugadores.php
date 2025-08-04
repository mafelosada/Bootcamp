<?php
include('conexion.php');

// Obtener jugadores del formulario
$jugadores = array_filter($_POST, function($key) {
    return strpos($key, 'jugador') === 0;
}, ARRAY_FILTER_USE_KEY);

$insertados = 0;
$playerIds = [];

// Registrar jugadores
foreach ($jugadores as $nombre) {
    $nombre = trim($conn->real_escape_string($nombre));
    if ($nombre !== '') {
        $sql = "INSERT INTO player (name) VALUES ('$nombre')";
        if ($conn->query($sql)) {
            $insertados++;
            $playerIds[] = $conn->insert_id;
        }
    }
}

// Crear la partida
$conn->query("INSERT INTO game (startDate, endDate) VALUES (CURDATE(), CURDATE())");
$gameId = $conn->insert_id;

// Registrar en gameplayer
$gamePlayerIds = [];
foreach ($playerIds as $playerId) {
    $conn->query("INSERT INTO gameplayer (player_id, game_id, cards_winner) VALUES ($playerId, $gameId, 0)");
    $gamePlayerIds[] = $conn->insert_id;
}

// Obtener todas las cartas disponibles
$cartas = [];
$result = $conn->query("SELECT card_id FROM card");
while ($row = $result->fetch_assoc()) {
    $cartas[] = $row['card_id'];
}

// Verificar que haya suficientes cartas
$totalNecesarias = count($gamePlayerIds) * 8;
if (count($cartas) < $totalNecesarias) {
    echo "<script>alert('No hay suficientes cartas para la cantidad de jugadores'); window.location.href = '../index.html';</script>";
    exit;
}

// Mezclar y repartir cartas
shuffle($cartas);
$index = 0;
foreach ($gamePlayerIds as $gpId) {
    for ($i = 1; $i <= 8; $i++) {
        $cardId = $cartas[$index++];
        $conn->query("INSERT INTO gameplayercard 
            (card_id, gameplayer_id, round_number, selected_attribute, is_winner_card)
            VALUES ($cardId, $gpId, $i, '', 0)");
    }
}

$conn->close();

// Redirigir a la partida
header("Location: ../partida.php?game_id=$gameId");
exit;
?>
