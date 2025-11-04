<?php
require_once './../Other/connexio.php'; // ajusta ruta si hace falta

header("Access-Control-Allow-Origin: *");
header('Content-Type: text/html; charset=utf-8');

$joc_id = isset($_GET['joc_id']) ? $_GET['joc_id'] : 2;

// Consulta totalmente insegura
$sql = "SELECT u.nom_usuari AS jugador, u.imatge_url AS imagen, 
               p.puntuacio_obtinguda AS puntos, p.nivell_jugat AS nivel, p.data_partida
        FROM partides p
        JOIN usuaris u ON p.usuari_id = u.id
        ORDER BY p.puntuacio_obtinguda DESC
        LIMIT 5;";

$top = [];
$db_error = null;
$debug_sql = $sql;

if (isset($conn) && method_exists($conn, 'query')) {
    $res = $conn->query($sql);
    if ($res === false) {
        $db_error = $conn->error;
    } else {
        while ($row = $res->fetch_assoc()) $top[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>Ranking</title>
  <link rel="stylesheet" href="../css/ranking.css">
</head>
<body>
  <div id="contenedor">
    <div id="leaderboard">
      <h1>Ranking</h1>
      <ul style="list-style:none; padding:0; margin:0;">
<?php
if (empty($top)) {
    echo "<li>No hay puntuaciones todavía.</li>";
} else {
    foreach ($top as $i => $row) {
        $pos = $i + 1;
        $jugador = $row['jugador'];
        $avatar  = $row['imagen'];
        $puntos  = $row['puntos'];
        $nivel   = $row['nivel'];
        $fecha   = $row['data_partida'];

        // Si la ruta no empieza por http ni por "/", añadimos ./../
        if (!preg_match('/^(https?:|\/)/', $avatar)) {
            $avatar = "./../" . ltrim($avatar, "./");
        }

        echo "<li class='li-row'>";
        echo "<span class='pos'>{$pos}º</span>";
        echo "<img class='avatar' src='{$avatar}' alt='avatar {$jugador}' />";
        echo "<span class='juego'>{$jugador}</span>";
        echo "<span class='meta'>(nivel {$nivel})</span>";
        echo "<span class='puntos'>{$puntos} pts</span>";
        echo "</li>";
    }
}
?>
      </ul>
      <a href='plataforma.php' class='boton'>Volver a la plataforma</a>
      </div>
    </div>
  </div>
</body>
</html>
