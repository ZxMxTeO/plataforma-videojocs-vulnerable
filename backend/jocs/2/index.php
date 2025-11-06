<?php
// index.php - versión con barra superior separada
session_start();
require_once './../../../Other/connexio.php';

$nombreUsuario = $_GET['usuari'] ?? ($_SESSION['usuari'] ?? 'guest');
$idUsuario = $_GET['id'] ?? ($_SESSION['id'] ?? 0);
$_SESSION['usuari'] = $nombreUsuario;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Atrapa Objetos</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>

<!-- 🔹 Barra superior -->
    <div class="topbar">
      <h2>🎮 Atrapa Objetos</h2>
      <div class="topbar-nav">
          <a href="./../../plataforma.php">🏠 Plataforma</a>
          <a href="./../../ranking.php">🏆 Ranking</a>
          <a href="./../../perfil.php">👤 Perfil</a>
      </div>
    </div>

<div id="game-container">
    <h1>Atrapa Objetos</h1>
    <p>Jugador: <strong id="jugador"><?= htmlspecialchars($nombreUsuario) ?></strong></p>

    <div id="info">
        <p>Puntos: <span id="score">0</span></p>
        <p>Nivel: <span id="level">1</span></p>
        <p>Vidas: <span id="vidas">3</span></p>
    </div>

    <div id="game-area"></div>

    <button id="start-btn">Empezar Juego</button>
    <button id="reset-btn">Reiniciar Juego</button>
</div>

<script>
    // Variable global del jugador
    var jugadorNombre = "<?php echo htmlspecialchars($nombreUsuario, ENT_QUOTES, 'UTF-8'); ?>";
</script>

<script src="classes.js"></script>
<script src="main.js"></script>

</body>
</html>
