<?php
include('php/conexion.php');
session_start();

if (isset($_GET['game_id'])) {
    $_SESSION['game_id'] = $_GET['game_id'];
}

$gameId = $_SESSION['game_id'] ?? null;
if (!$gameId) {
    echo "No se encontró partida activa.";
    exit;
}

$sql = "SELECT gp.gameplayer_id, p.name
        FROM gameplayer gp
        JOIN player p ON p.player_id = gp.player_id
        WHERE gp.game_id = $gameId
        ORDER BY gp.gameplayer_id ASC";
$result = $conn->query($sql);
$jugadores = [];
while ($row = $result->fetch_assoc()) {
    $jugadores[] = [
        'gameplayer_id' => $row['gameplayer_id'],
        'name' => $row['name']
    ];
}

if (!isset($_SESSION['turno_actual'])) {
    $_SESSION['turno_actual'] = 0;
}
$currentTurn = $_SESSION['turno_actual'];

if ($currentTurn >= count($jugadores)) {
    header("Location: resultado_ronda.php?game_id=$gameId");
    exit;
}

$jugadorActual = $jugadores[$currentTurn];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carta_id'])) {
    $cartaId = (int)$_POST['carta_id'];
    $gpId = $jugadorActual['gameplayer_id'];

    $stmt = $conn->prepare("UPDATE gameplayercard SET is_winner_card = 1 WHERE gameplayer_id = ? AND card_id = ?");
    $stmt->bind_param("ii", $gpId, $cartaId);
    $stmt->execute();
    $stmt->close();

    $_SESSION['turno_actual']++;

    if ($_SESSION['turno_actual'] < count($jugadores)) {
        header("Location: turnoLanzarCarta.php");
    } else {
        header("Location: resultado_ronda.php?game_id=$gameId");
    }
    exit;
}

$cartas = [];
$res = $conn->query("SELECT c.*, gpc.gameplayercard_id
    FROM gameplayercard gpc
    JOIN card c ON c.card_id = gpc.card_id
    WHERE gpc.gameplayer_id = {$jugadorActual['gameplayer_id']} AND gpc.is_winner_card = 0
    ORDER BY gpc.round_number ASC");


while ($fila = $res->fetch_assoc()) {
    $cartas[] = $fila;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Turno de <?php echo htmlspecialchars($jugadorActual['name']); ?></title>
    <link rel="stylesheet" href="css/styleGame.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Delius&family=Google+Sans+Code:ital,wght@0,300..800;1,300..800&family=Palette+Mosaic&family=Piedra&display=swap" rel="stylesheet">
</head>
<body>
    <div class="contenedor">
        <h1 class="letter2 text-center">Turno de <?php echo htmlspecialchars($jugadorActual['name']); ?></h1>
    </div>
    
    <form method="POST" class="text-center">
        <div class="cartas-container">
            <?php foreach ($cartas as $carta): ?>
                <div class="carta letter">
                    <label>
                        <input type="radio" name="carta_id" value="<?php echo $carta['card_id']; ?>" required>
                        <img src="<?php echo htmlspecialchars($carta['image']); ?>" alt="Carta">
                        <h3><?php echo $carta['brand']; ?>-<?php echo $carta['name']; ?></h3>
                        <h4 class="letter">Peso: <?php echo $carta['weight']; ?> g <br>
                        Altura: <?php echo $carta['height']; ?> cm <br>
                        Velocidad: <?php echo $carta['speed']; ?> <br>
                        Inteligencia: <?php echo $carta['intelligence']; ?> <br>
                        Fuerza: <?php echo $carta['strength']; ?> <br>
                        Agilidad: <?php echo $carta['agility']; ?></h4>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="letter2 button">Lanzar Carta</button>
    </form>
</body>
</html>
