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

// Obtener todos los jugadores de la partida
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

// Obtener el turno actual (si no existe, iniciamos en 0)
if (!isset($_SESSION['turno_actual'])) {
    $_SESSION['turno_actual'] = 0;
}
$currentTurn = $_SESSION['turno_actual'];

// Si ya terminaron todos, redirigir al resultado
if ($currentTurn >= count($jugadores)) {
    header("Location: resultado_ronda.php?game_id=$gameId");
    exit;
}

$jugadorActual = $jugadores[$currentTurn];

// Si se ha enviado una carta (formulario POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carta_id'])) {
    $cartaId = (int)$_POST['carta_id'];
    $gpId = $jugadorActual['gameplayer_id'];

    // Marcar la carta como lanzada
    $stmt = $conn->prepare("UPDATE gameplayercard SET is_winner_card = 1 WHERE gameplayer_id = ? AND card_id = ?");
    $stmt->bind_param("ii", $gpId, $cartaId);
    $stmt->execute();
    $stmt->close();

    // Avanzar turno
    $_SESSION['turno_actual']++;

    // Recargar para el siguiente jugador o redirigir al resultado
    if ($_SESSION['turno_actual'] < count($jugadores)) {
        header("Location: turnoLanzarCarta.php");
    } else {
        header("Location: resultado_ronda.php?game_id=$gameId");
    }
    exit;
}

// Mostrar las cartas del jugador actual
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
</head>
<body>
    <h1 class="letter text-center">Turno de <?php echo htmlspecialchars($jugadorActual['name']); ?></h1>

    <form method="POST" class="text-center">
        <div class="cartas-container">
            <?php foreach ($cartas as $carta): ?>
                <div class="carta letter">
                    <label>
                        <input type="radio" name="carta_id" value="<?php echo $carta['card_id']; ?>" required>
                        <img src="<?php echo htmlspecialchars($carta['image']); ?>" alt="Carta">
                        <p><strong><?php echo $carta['name']; ?></strong></p>
                        <p>Marca: <?php echo $carta['brand']; ?></p>
                        <p>Peso: <?php echo $carta['weight']; ?> g</p>
                        <p>Altura: <?php echo $carta['height']; ?> cm</p>
                        <p>Velocidad: <?php echo $carta['speed']; ?></p>
                        <p>Inteligencia: <?php echo $carta['intelligence']; ?></p>
                        <p>Fuerza: <?php echo $carta['strength']; ?></p>
                        <p>Agilidad: <?php echo $carta['agility']; ?></p>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="submit" class="button mt-4">Lanzar Carta</button>
    </form>
</body>
</html>
