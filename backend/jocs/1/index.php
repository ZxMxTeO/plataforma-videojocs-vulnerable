<?php
session_start();
require_once  './../../../Other/connexio.php';

// Comprobamos sesión
if (!isset($_SESSION['id']) && !isset($_SESSION['usuari'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['nivell'])) {
    $_SESSION['nivell'] = 1; 
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
      <script>
        //Simulem que la ruta del joc és http://IP_DE_LA_VM/jocs/1/index.php (vol dir que joc_id=1)
        const jocId = 1; // Ex: segons la ruta del joc
        let nivell = <?php echo $_SESSION['nivell']; ?>;

        //Poseu correctament la ruta de la API al fet el fetch.
        fetch(`http://172.18.33.243/backend/api.php/jocs/${jocId}/nivells/${nivell}`)
          .then(res => res.json())
          .then(data => {
            console.log("Resposta API:", data);
            
            const enemics = data.enemics;
            const velocitrat = data.velocitat;


            /* LEs variables reals del joc son:
                 const maxPunts = 100;
                const vectorAsteroides = [];
                const vectorEnemics = [];
                const maxAsteroides = 100;
                const maxEnemics = 12;
                 // Constructor: nom, vides, velocitat, posicio, ample, alt
                const jugador = new Jugador("Pepito", 3, 15, {x: 100, y: 300}, 150, 100);
                */

            //Assignar variables del joc
            /*const vides = data.vides;
            const maxPunts = data.puntsNivell;
            const maxEnemics = data.maxEnemics;
            const maxProjectils = data.maxProjectils;

            console.log(`Nivell: ${nivell}`);
            console.log(`Vides: ${vides}`);
            console.log(`MaxPunts: ${maxPunts}`);
            console.log(`MaxEnemics: ${maxEnemics}`);
            console.log(`MaxProjectils: ${maxProjectils}`);
            */
            // Aquí ja pots fer servir aquestes dades dins el joc
            // Inicialitzar joc amb la configuració (crear objecte usuari amb les vides, etc.).
          })
          .catch(err => console.error("Error de la API:", err));
    </script>
    <div id="pantalla"></div>
    <div id="infoPartida"></div>
    <script src="./classes.js"></script>
    <script src="./main.js"></script>
  </body>
</html>
