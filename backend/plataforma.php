<?php
//connexio a la bd
//$nivell = consulta --> select nivell from progres_usuari on el jocID = 1
session_start();
require_once  './../Other/connexio.php';

// Comprobamos sesión
if (!isset($_SESSION['id']) && !isset($_SESSION['usuari'])) {
    header("Location: ../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menú Wii</title>
    <link rel="stylesheet" href="../css/plataforma.css">
</head>
<body>
    <main>
        <ul class="navigation">
            <li>
                <a href="./jocs/1/index.php">
                    <img src="../img/Portada-nave.png" alt="">
                </a>
            </li>
            <li>
                <a href="./jocs/2/index.php">
                </a>
            </li>
            <li>
                <a href="./juego3.html">
                </a>
            </li>
            <li>
                <a href="./juego4.html">
                </a>
            </li>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </main>

    <div class="bottom-text">Selecciona un Juego</div>

    <footer>
        <div class="button-cont">
            <a class="button" href="./perfil.php">
                <span class="corner"></span>
                <span class="text">Perfil</span>
            </a>
        </div>
        <div class="c"></div>
        <div class="c"></div>
        <div class="m"></div>
        <div class="button-cont right-side">
            <a class="button" href="./ranking.php">
                <span class="corner"></span>
                <span class="text">Ranking</span>
            </a>
        </div>
    </footer>
</body>
</html>
