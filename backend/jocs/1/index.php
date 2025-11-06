  <?php
  session_start();
  require_once  './../../../Other/connexio_api.php';

  // Si repem una petició POST la gestionem aquí per actualitzar el nivell
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      header('Content-Type: application/json');

      $data = json_decode(file_get_contents("php://input"), true);
      $usuari_id = isset($data['usuari_id']) ? (int)$data['usuari_id'] : 0;
      $nivell_actual = isset($data['nivell_actual']) ? (int)$data['nivell_actual'] : 0;

      if ($usuari_id <= 0 || $nivell_actual <= 0) {
          http_response_code(400);
          echo json_encode(["ok" => false, "error" => "Paràmetres invàlids"]);
          exit;
      }

      try {
          // UPDATE senzill i intencionadament vulnerable seguint l'estil del projecte
          $sql = "UPDATE progres_usuari SET nivell_actual = $nivell_actual, ultima_partida = NOW() WHERE usuari_id = $usuari_id";
          $pdo->exec($sql);
          echo json_encode(["ok" => true, "nivell" => $nivell_actual]);
      } catch (Exception $e) {
          http_response_code(500);
          echo json_encode(["ok" => false, "error" => $e->getMessage()]);
      }
      exit; // Important: aturar l'execució i no enviar el HTML després
  }

  // Comprobamos sesión
  if (!isset($_SESSION['id']) && !isset($_SESSION['usuari'])) {
      header("Location: ../index.php");
      exit();
  }

  /* ✅ Aquí viene la corrección importante */
  if (isset($_GET['nivell'])) {
      // Si hay ?nivell= en la URL, usamos ese valor y lo guardamos
      $nivell = (int)$_GET['nivell'];
      $_SESSION['nivell_joc1'] = $nivell;
  } elseif (isset($_SESSION['nivell_joc1'])) {
      // Si ya hay guardado en sesión
      $nivell = (int)$_SESSION['nivell_joc1'];
  } else {
      // Si no hay nada, empezamos desde el nivel 1
      $nivell = 1;
      $_SESSION['nivell_joc1'] = 1;
  }
  ?>

  <!DOCTYPE html>
  <html lang="ca">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Joc interactiu HTML, CSS i JavaScript</title>
      <meta name="description" content="Joc JS per treballar la manipulació del DOM, la gestió d'esdeveniments i la POO." />
      <meta name="author" content="Xavi Garcia @xavig-icv" />
      <meta name="copyright" content="Xavi Garcia @xavig-icv" />
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
            <!-- 🔁 Nuevo botón para reiniciar -->
            <a href="#" id="reiniciar">🔁 Reiniciar</a>
        </div>
      </div>

      <div id="pantalla"></div>
      <div id="infoPartida"></div>

      <script>
        const jocId = 1;
        let nivell = <?php echo $nivell; ?>;
        const usuariId = <?php echo isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0; ?>;

        // 🔁 Script del botón de reinicio
        document.addEventListener("DOMContentLoaded", () => {
          const btnReiniciar = document.getElementById("reiniciar");
          if (btnReiniciar) {
            btnReiniciar.addEventListener("click", (e) => {
              e.preventDefault();
              if (!confirm("Vols reiniciar el joc al nivell 1?")) return;

              fetch(window.location.pathname, {
                method: "POST",
                headers: {
                  "Content-Type": "application/json"
                },
                body: JSON.stringify({
                  usuari_id: usuariId,
                  nivell_actual: 1
                })
              })
              .then(res => res.json())
              .then(data => {
                if (data.ok) {
                  alert("Joc reiniciat!");
                  window.location.href = "./index.php?nivell=1";
                } else {
                  alert("Error al reiniciar nivell");
                }
              })
              .catch(err => {
                console.error(err);
                alert("Error al reiniciar nivell");
              });
            });
          }
        });

        // 🔹 Carga de la API del nivel actual
        fetch(`http://172.20.0.134/backend/api.php/jocs/${jocId}/nivells/${nivell}`)
          .then(res => res.json())
          .then(data => {
            console.log("Resposta API:", data);
            window.config = data;

            window.config.puntuacio_final = data.puntuacio_final || data.puntuacio_maxima;
            window.usuariId = <?php echo (int)$_SESSION['id']; ?>;
            window.nomUsuari = "<?php echo $_SESSION['usuari']; ?>";
            window.nivell = <?php echo $nivell; ?>;

  (function loadGameScripts() {
      const scriptCls = document.createElement('script');
      scriptCls.src = './classes.js';
      scriptCls.onload = () => {
        console.log('classes.js cargado — ahora cargo main.js');

        // ahora inyectamos main.js (se ejecutará ya con Jugador definido)
        const scriptMain = document.createElement('script');
        scriptMain.src = './main.js';
        scriptMain.onload = () => console.log('main.js cargado');
        document.body.appendChild(scriptMain);
      };
      scriptCls.onerror = () => console.error('Error cargando classes.js');
      document.body.appendChild(scriptCls);
    })();
          })
          .catch(err => console.error("Error de la API:", err));
      </script>
      
    </body>
  </html>
