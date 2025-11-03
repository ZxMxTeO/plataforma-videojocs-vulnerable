<?php
session_start();
require_once  './../../../Other/connexio.php';
$nombreUsuario = isset($_SESSION['usuari']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atrapa Objetos</title>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" href="data:,">
</head>
<body>
    <div id="game-container">
        <h1>Atrapa Objetos</h1>
        <p>Jugador: <strong><?= $_SESSION['usuari'] ?></strong></p>
        <div id="info">
            <p>Puntos: <span id="score">0</span></p>
            <p>Nivel: <span id="level">1</span></p>
        </div>
        <div id="game-area"></div>
        <button id="start-btn">Empezar Juego</button>
        <button id="reset-btn">Reiniciar Juego</button>
    </div>
    <script src="classes.js"></script>
    <script src="main.js"></script>
</body>
</html>
