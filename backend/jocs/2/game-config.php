<?php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
$jugador = isset($_SESSION['usuari']) ? $_SESSION['usuari'] : (isset($_GET['usuari']) ? $_GET['usuari'] : 'guest');
$nivel = isset($_GET['nivel']) ? (int)$_GET['nivel'] : 1;
$puntos = isset($_GET['puntos']) ? (int)$_GET['puntos'] : 0;
$vidas = isset($_GET['vidas']) ? (int)$_GET['vidas'] : 3;
$velocidadAparicion = isset($_GET['velocidadAparicion']) ? (int)$_GET['velocidadAparicion'] : 1000;
$tiempoVidaObjeto = isset($_GET['tiempoVidaObjeto']) ? (int)$_GET['tiempoVidaObjeto'] : 3000;
echo json_encode([
    "jugador" => $jugador,
    "nivel" => $nivel,
    "puntos" => $puntos,
    "vidas" => $vidas,
    "velocidadAparicion" => $velocidadAparicion,
    "tiempoVidaObjeto" => $tiempoVidaObjeto
]);
