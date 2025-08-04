<?php
include('php/conexion.php');
session_start();

if (isset($_GET['game_id'])) {
    $_SESSION['game_id'] = $_GET['game_id'];
    $_SESSION['turno_actual'] = 0; 
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

$primerJugador = $jugadores[0];
$primerJugadorId = $primerJugador['gameplayer_id'];

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee&family=Delius&family=Google+Sans+Code:ital,wght@0,300..800;1,300..800&family=Palette+Mosaic&family=Piedra&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="contenedor">
        <h1 class="letter2 text-center">Partida Iniciada</h1>

        <div class="jugadores-container">
            <h2 class="letter2">Jugadores:</h2>
            <?php foreach ($jugadores as $j): ?>
                <p class="letter2"><?php echo htmlspecialchars($j['name']);?></p>
            <?php endforeach; ?>
        </div>

        <div id="mensaje-turno" class="turno-mensaje letter2">
            Turno de <?php echo htmlspecialchars($primerJugador['name']); ?><br>
            Atributo elegido: <strong><?php echo $nombresAtributos[$atributoEscogido]; ?></strong>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = 'turnoLanzarCarta.php?game_id=<?php echo $gameId; ?>';
        }, 3000);
    </script>
</body>
</html>
