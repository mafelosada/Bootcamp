<?php
include('php/conexion.php');
session_start();

$gameId = $_SESSION['game_id'] ?? null;
if (!$gameId) {
    echo "No hay partida activa.";
    exit;
}

// Obtener atributo escogido por el sistema (puede estar en la tabla game)
// Obtener atributo seleccionado por el jugador 1 desde gameplayercard
$sql = "SELECT selected_attribute 
        FROM gameplayercard 
        WHERE gameplayer_id = (
            SELECT gp.gameplayer_id 
            FROM gameplayer gp 
            WHERE gp.game_id = $gameId 
            ORDER BY gp.gameplayer_id ASC 
            LIMIT 1
        ) 
        AND selected_attribute IS NOT NULL 
        LIMIT 1";

$res = $conn->query($sql);

if (!$res) {
    die("Error en la consulta del atributo: " . $conn->error);
}

$atributo = $res->fetch_assoc()['selected_attribute'] ?? '';

// Obtener los jugadores y la carta que lanzaron (is_winner_card = 1)
$sql = "SELECT p.name, c.name AS card_name, c.$atributo AS valor
        FROM gameplayer gp
        JOIN player p ON p.player_id = gp.player_id
        JOIN gameplayercard gpc ON gpc.gameplayer_id = gp.gameplayer_id
        JOIN card c ON c.card_id = gpc.card_id
        WHERE gp.game_id = $gameId AND gpc.is_winner_card = 1";
$result = $conn->query($sql);

// Encontrar el valor más alto
$jugadores = [];
$mayorValor = -1;
$ganador = null;

while ($row = $result->fetch_assoc()) {
    $jugadores[] = $row;

    if ($row['valor'] > $mayorValor) {
        $mayorValor = $row['valor'];
        $ganador = $row['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado de Ronda</title>
    <link rel="stylesheet" href="css/styleGame.css">
</head>
<body>
    <h1 class="letter text-center">Resultado de la Ronda</h1>
    <p class="letter text-center">Atributo evaluado: <strong><?php echo htmlspecialchars($atributo); ?></strong></p>

    <div class="jugadores-container">
        <?php foreach ($jugadores as $jugador): ?>
            <p class="letter">
                <?php echo htmlspecialchars($jugador['name']); ?> lanzó <?php echo htmlspecialchars($jugador['card_name']); ?> con valor <strong><?php echo $jugador['valor']; ?></strong>
            </p>
        <?php endforeach; ?>
    </div>

    <h2 class="letter text-center ganador">🏆 Ganador de la ronda: <?php echo htmlspecialchars($ganador); ?> 🏆</h2>

    <div class="text-center mt-4">
        <form action="partida.php" method="GET">
            <input type="hidden" name="game_id" value="<?php echo $gameId; ?>">
            <button class="button">Siguiente Ronda</button>
        </form>
    </div>
</body>
</html>
