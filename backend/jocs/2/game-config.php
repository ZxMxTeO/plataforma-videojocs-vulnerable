<?php
// game-config.php - INSEGURO: devuelve configuración básica / último progreso
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
session_start();

require_once './../../../Other/connexio.php';

$jugador = $_GET['usuari'] ?? ($_SESSION['usuari'] ?? 'guest');

$sql = "SELECT p.nivell_jugat, p.puntuacio_obtinguda, p.vidas
        FROM partides p
        JOIN usuaris u ON u.id = p.usuari_id
        WHERE u.nom_usuari = '$jugador' AND p.joc_id = 2
        ORDER BY p.id DESC LIMIT 1;";

$nivel = 1; $puntos = 0; $vidas = 3;

if (isset($conn) && method_exists($conn,'query')) {
    $r = $conn->query($sql);
    if ($r && $row = $r->fetch_assoc()) {
        $nivel = intval($row['nivell_jugat']);
        $puntos = intval($row['puntuacio_obtinguda']);
        $vidas = intval($row['vidas'] ?? $vidas);
    }
}

echo json_encode([
    "jugador" => $jugador,
    "nivel" => $nivel,
    "puntos" => $puntos,
    "vidas" => $vidas,
    "mensaje" => "config insegura"
]);
