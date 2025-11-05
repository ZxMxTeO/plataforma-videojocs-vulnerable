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

if (!isset($_SESSION['nivell_joc1'])) {
    $_SESSION['nivell_joc1'] = 1;
    $nivell = 1;
} else {
  $nivell = $_SESSION['nivell_joc1'];
}

echo "<h1>Nivell: $nivell</h1>";
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
    <div id="pantalla"></div>
    <div id="infoPartida"></div>
      <script>
        //Simulem que la ruta del joc és http://IP_DE_LA_VM/jocs/1/index.php (vol dir que joc_id=1)
        const jocId = 1; // Ex: segons la ruta del joc
        let nivell = <?php echo $nivell; ?>;
        // exposem usuari id per al JS (assegura't que $_SESSION['id'] existeix)
        const usuariId = <?php echo isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0; ?>;

        //Poseu correctament la ruta de la API al fet el fetch.
        fetch(`http://192.168.1.144/backend/api.php/jocs/${jocId}/nivells/${nivell}`)
          .then(res => res.json())
          .then(data => {
            console.log("Resposta API:", data);
            
            const enemics = data.enemics;
            const velocitat = data.velocitat;
            const vides = data.vides
            const puntuacio_maxima = data.puntuacio_maxima
            console.log(data.enemics);
            window.config = data;
            
            const script = document.createElement('script');
            script.src = './classes.js';
            script.onload = () => {
              console.log('classes.js cargado después del fetch');
            };
            document.body.appendChild(script);

            const script2 = document.createElement('script');
            script2.src = './main.js';
            script2.onload = () => {
              console.log('main.js cargado después del fetch');
            };
            document.body.appendChild(script2);

          })
          .catch(err => console.error("Error de la API:", err));
    </script>
    
  </body>
</html>
