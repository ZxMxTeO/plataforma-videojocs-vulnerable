<?php
session_start();
require_once './../../../Other/connexio_api.php';

// ----------------------------------------------------
// 🔹 1️⃣ Gestión de peticiones POST (guardar progreso o partida)
// ----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents("php://input"), true);

    // ✅ Guardar una partida finalizada
    if (isset($data['guardar_partida']) && $data['guardar_partida'] === true) {
        $usuari_id = (int)$data['usuari_id'];
        $joc_id = (int)$data['joc_id'];
        $nivell_jugat = (int)$data['nivell_jugat'];
        $puntuacio = (int)$data['puntuacio_obtinguda'];
        $vides = (int)$data['vidas'];

        try {
            $sql = "INSERT INTO partides (usuari_id, joc_id, nivell_jugat, puntuacio_obtinguda, vidas)
                    VALUES ($usuari_id, $joc_id, $nivell_jugat, $puntuacio, $vides)";
            $pdo->exec($sql);
            echo json_encode(['ok' => true, 'msg' => 'Partida guardada correctament']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    // ✅ Actualitzar el nivell actual del jugador
    $usuari_id = isset($data['usuari_id']) ? (int)$data['usuari_id'] : 0;
    $nivell_actual = isset($data['nivell_actual']) ? (int)$data['nivell_actual'] : 0;

    if ($usuari_id <= 0 || $nivell_actual <= 0) {
        http_response_code(400);
        echo json_encode(["ok" => false, "error" => "Paràmetres invàlids"]);
        exit;
    }

    try {
        $sql = "UPDATE progres_usuari 
                SET nivell_actual = $nivell_actual, ultima_partida = NOW() 
                WHERE usuari_id = $usuari_id";
        $pdo->exec($sql);
        echo json_encode(["ok" => true, "nivell" => $nivell_actual]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["ok" => false, "error" => $e->getMessage()]);
    }
    exit;
}

// ----------------------------------------------------
// 🔹 2️⃣ Comprobació de sessió i nivell actual
// ----------------------------------------------------
if (!isset($_SESSION['id']) && !isset($_SESSION['usuari'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['nivell'])) {
    $nivell = (int)$_GET['nivell'];
    $_SESSION['nivell_joc1'] = $nivell;
} elseif (isset($_SESSION['nivell_joc1'])) {
    $nivell = (int)$_SESSION['nivell_joc1'];
} else {
    $nivell = 1;
    $_SESSION['nivell_joc1'] = 1;
}
?>

<!DOCTYPE html>
<html lang="ca">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>🎮 Marcianitos</title>
    <link rel="stylesheet" href="./index.css" />
  </head>

  <body>
    <!-- 🔹 Barra superior -->
    <div class="topbar">
      <h2>🎮 Marcianitos</h2>
      <div class="topbar-nav">
        <a href="./../../plataforma.php">🏠 Plataforma</a>
        <a href="./../../ranking.php">🏆 Ranking</a>
        <a href="./../../perfil.php">👤 Perfil</a>
        <a href="#" id="reiniciar">🔁 Reiniciar</a>
      </div>
    </div>

    <div id="pantalla"></div>
    <div id="infoPartida"></div>

    <script>
      const jocId = 1;
      let nivell = <?php echo $nivell; ?>;
      const usuariId = <?php echo isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0; ?>;

      // 🔁 Reiniciar el joc
      document.addEventListener("DOMContentLoaded", () => {
        const btnReiniciar = document.getElementById("reiniciar");
        if (btnReiniciar) {
          btnReiniciar.addEventListener("click", (e) => {
            e.preventDefault();
            if (!confirm("Quieres reiniciar el juego al nivel 1?")) return;

            fetch(window.location.pathname, {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({
                usuari_id: usuariId,
                nivell_actual: 1,
              }),
            })
              .then((res) => res.json())
              .then((data) => {
                if (data.ok) {
                  alert("🔁 Juego reiniciado!");
                  window.location.href = "./index.php?nivell=1";
                } else {
                  alert("❌ Error al reiniciar nivel");
                }
              })
              .catch((err) => {
                console.error(err);
                alert("⚠️ Error al reiniciar nivel");
              });
          });
        }
      });

      // 🔹 Carrega de la configuració del nivell actual
      fetch(`http://192.168.1.144/backend/api.php/jocs/${jocId}/nivells/${nivell}`)
        .then((res) => res.json())
        .then((data) => {
          console.log("Resposta API:", data);
          window.config = data;

          window.config.puntuacio_final = data.puntuacio_final || data.puntuacio_maxima;
          window.usuariId = <?php echo (int)$_SESSION['id']; ?>;
          window.nomUsuari = "<?php echo $_SESSION['usuari']; ?>";
          window.nivell = <?php echo $nivell; ?>;

          // 🔹 Carreguem els scripts del joc
          (function loadGameScripts() {
            const scriptCls = document.createElement("script");
            scriptCls.src = "./classes.js";
            scriptCls.onload = () => {
              console.log("classes.js cargado — ahora cargo main.js");
              const scriptMain = document.createElement("script");
              scriptMain.src = "./main.js";
              scriptMain.onload = () => console.log("main.js cargado");
              document.body.appendChild(scriptMain);
            };
            scriptCls.onerror = () => console.error("Error cargando classes.js");
            document.body.appendChild(scriptCls);
          })();
        })
        .catch((err) => console.error("Error de la API:", err));
    </script>
  </body>
</html>
