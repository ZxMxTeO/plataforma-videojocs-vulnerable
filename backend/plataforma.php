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

#$id = $_SESSION['id'];
#$consulta1 = "SELECT nivell_actual FROM progres_usuari WHERE usuari_id = $id AND joc_id=1";

#$result = $conn->query($consulta1);
#  if ($result->num_rows > 0) {
#    $progres = $result->fetch_assoc();
#    echo "<p>Nivell Actual Level 1: " . $progres['nivell_actual'] . "</p>";
#    $nivell_joc1 = $progres['nivell_actual'];
#  } else {
#    echo "<p>No s'ha trobat cap progrés per l'usuari actual i el nivell 1.</p>";
#    $nivell_joc1 = 1;
#  }
#  $_SESSION['nivell_joc1'] = $nivell_joc1;
#  $conn->close();

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
                    <img src="../img/atrapa-objetos.png" alt="">
                </a>
            </li>
            <li></li>
            <li></li>
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
