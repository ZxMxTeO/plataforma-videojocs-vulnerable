<?php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
$input = file_get_contents("php://input");
$data = json_decode($input, true);
if (!$data) parse_str($_SERVER['QUERY_STRING'], $data);
$jugador = isset($_SESSION['usuari']) ? $_SESSION['usuari'] : (isset($data['jugador']) ? $data['jugador'] : 'guest');
$puntos = isset($data['puntos']) ? $data['puntos'] : (isset($_GET['puntos']) ? $_GET['puntos'] : 0);
$nivel = isset($data['nivel']) ? $data['nivel'] : (isset($_GET['nivel']) ? $_GET['nivel'] : 1);
$vidas = isset($data['vidas']) ? $data['vidas'] : (isset($_GET['vidas']) ? $_GET['vidas'] : 3);
$log = ["jugador"=>$jugador,"puntos"=>$puntos,"nivel"=>$nivel,"vidas"=>$vidas,"fecha"=>date("Y-m-d H:i:s")];
if (!file_exists("logs")) mkdir("logs", 0777, true);
file_put_contents("logs/estado_juego.log", json_encode($log).PHP_EOL, FILE_APPEND);
echo json_encode(["ok"=>true,"saved"=>$log]);
