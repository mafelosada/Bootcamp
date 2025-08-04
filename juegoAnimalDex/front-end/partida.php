<?php
// archivo: partida.php
include('php/conexion.php');
session_start();

if (isset($_GET['game_id'])) {
    $_SESSION['game_id'] = $_GET['game_id'];
    $_SESSION['turno_actual'] = 0; // Reiniciar turno al iniciar partida
}

$gameId = $_SESSION['game_id'] ?? null;
if (!$gameId) {
    echo "No se encontró partida activa.";
    exit;
}

// Obtener jugadores
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

// Primer jugador
$primerJugador = $jugadores[0];
$primerJugadorId = $primerJugador['gameplayer_id'];

// Obtener sus 8 cartas
$cartasJugador1 = [];
$res = $conn->query("SELECT c.*
    FROM gameplayercard gpc
    JOIN card c ON c.card_id = gpc.card_id
    WHERE gpc.gameplayer_id = $primerJugadorId
    ORDER BY gpc.round_number ASC
    LIMIT 8");

while ($fila = $res->fetch_assoc()) {
    $cartasJugador1[] = $fila;
}

// Escoger un atributo aleatorio
$atributos = ['weight', 'height', 'speed', 'intelligence', 'strength', 'agility'];
$nombresAtributos = [
    'weight' => 'Peso',
    'height' => 'Altura',
    'speed' => 'Velocidad',
    'intelligence' => 'Inteligencia',
    'strength' => 'Fuerza',
    'agility' => 'Agilidad'
];
$atributoEscogido = $atributos[array_rand($atributos)];

// Guardar atributo seleccionado en todas las cartas del jugador 1
$stmt = $conn->prepare("UPDATE gameplayercard SET selected_attribute = ? WHERE gameplayer_id = ?");
if ($stmt) {
    $stmt->bind_param("si", $atributoEscogido, $primerJugadorId);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Partida Iniciada</title>
    <link rel="stylesheet" href="css/styleGame.css">
</head>
<body>
    <h1 class="letter text-center">Partida Iniciada</h1>

    <div class="jugadores-container">
        <h2 class="letter">Jugadores:</h2>
        <?php foreach ($jugadores as $j): ?>
            <p class="letter"><?php echo htmlspecialchars($j['name']); ?></p>
        <?php endforeach; ?>
    </div>

    <div id="mensaje-turno" class="turno-mensaje letter">
        Turno de <?php echo htmlspecialchars($primerJugador['name']); ?><br>
        Atributo elegido por el sistema: <strong><?php echo $nombresAtributos[$atributoEscogido]; ?></strong>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = 'turnoLanzarCarta.php?game_id=<?php echo $gameId; ?>';
        }, 3000);
    </script>
</body>
</html>
