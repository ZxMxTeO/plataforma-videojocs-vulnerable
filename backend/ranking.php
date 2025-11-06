<?php
require_once './../Other/connexio.php'; // conexión a BD

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8');

// IDs de los juegos (ajusta según tus datos reales)
$jocs = [
  1 => "Marcianitos",
  2 => "Atrapa Objetos"
];

$rankings = [];

foreach ($jocs as $id => $nomJoc) {
    $sql = "SELECT u.nom_usuari AS jugador, u.imatge_url AS imagen, 
                   p.puntuacio_obtinguda AS puntos, p.nivell_jugat AS nivel, p.data_partida
            FROM partides p
            JOIN usuaris u ON p.usuari_id = u.id
            WHERE p.joc_id = $id
            ORDER BY p.puntuacio_obtinguda DESC
            LIMIT 5;";

    $res = $conn->query($sql);
    $rankings[$id] = [];
    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $rankings[$id][] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Ranking</title>
  <link rel="icon" type="image/png" href="../img/wii-logo.png">
  <link rel="stylesheet" href="../css/ranking.css">
</head>
<body>
  <h1>🏆 Ranking</h1>

  <div class="ranking-container">
    <?php foreach ($jocs as $id => $nomJoc): ?>
      <div class="ranking-box">
        <h2><?= htmlspecialchars($nomJoc) ?></h2>
        <ul>
          <?php if (empty($rankings[$id])): ?>
            <li>No hay puntuaciones todavía.</li>
          <?php else: ?>
            <?php foreach ($rankings[$id] as $i => $row): 
              $pos = $i + 1;
              $jugador = htmlspecialchars($row['jugador']);
              $avatar  = htmlspecialchars($row['imagen']);
              $puntos  = htmlspecialchars($row['puntos']);
              $nivel   = htmlspecialchars($row['nivel']);
              $fecha   = htmlspecialchars($row['data_partida']);
              if (!preg_match('/^(https?:|\/)/', $avatar)) {
                  $avatar = "./../" . ltrim($avatar, "./");
              }
            ?>
              <li>
                <span class="pos"><?= $pos ?>º</span>
                <img class="avatar" src="<?= $avatar ?>" alt="avatar <?= $jugador ?>">
                <span class="jugador"><?= $jugador ?> <small>(Niv. <?= $nivel ?>)</small></span>
                <span class="puntos"><?= $puntos ?> pts</span>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>

  <a href="plataforma.php" class="boton">⬅ Volver a la plataforma</a>
</body>
</html>
