<?php
// api_basica.php
require_once "./../Other/connexio_api.php";

// Definim el tipus de resposta per a tota l'API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // VULNERABILITAT: CORS permissiu a tothom

// Obtenim la URL sol·licitada
$uri = explode("/", trim($_SERVER['REQUEST_URI'], "/"));

// VIGILEU el número d'elements de la vostra URI pot canviar!!
// Ex: /backend/api.php/jocs/1/nivells/3
// uri[0] =backend,uri[1] api, uri[2] = jocs, uri[3] = 1, uri[4] = nivells, uri[5] = 3

$method = $_SERVER['REQUEST_METHOD'];

if ($method === "GET") {
  // Validar l'estructura: /api.php/jocs/{id}/nivells/{nivell}
  if (count($uri) === 6 && $uri[2] === "jocs" && $uri[4] === "nivells") {
    $jocId = (int) $uri[3];
    $nivell = (int) $uri[5];

    // Exemple vulnerable a SQL Injection
    $sql = "SELECT configuracio_json FROM nivells_joc WHERE joc_id = $jocId AND nivell = $nivell";

    $resultat = $pdo->query($sql);

    if ($resultat && $fila = $resultat->fetch(PDO::FETCH_ASSOC)) {
        echo $fila['configuracio_json']; // Retornem directament el JSON guardat
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Joc o nivell no trobat"]);
    }
  } else {
      http_response_code(400);
      echo json_encode(["error" => "Ruta no vàlida. Exemple: /api/jocs/1/nivells/3"]);
  }
} else {
    http_response_code(405); // Mètode no permès
    echo json_encode(["error" => "Mètode no suportat"]);
}

$pdo = null;
?>