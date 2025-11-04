<?php
// update-game.php - INTENCIONALMENTE INSEGURO pero robusto para laboratorio
// A usar solo en entorno controlado.
// Inserta siempre una nueva fila en partides (vidas al final).

session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Evitar que warnings rompan el JSON de respuesta
error_reporting(0);

// Ajusta esta ruta si tu connexio.php está en otra carpeta
require_once './../../../Other/connexio.php';

// Respuesta base
$response = [
    "ok" => false,
    "message" => "",
];

// Comprobar sesión (solo usuarios logueados)
if (!isset($_SESSION['usuari'])) {
    $response['message'] = "No autenticado (falta \$_SESSION['usuari']).";
    echo json_encode($response);
    exit;
}

// Leer entrada (acepta JSON, form-urlencoded o querystring)
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!$data && !empty($_POST)) $data = $_POST;
if (!$data) parse_str($_SERVER['QUERY_STRING'] ?? '', $data);

// Valores (inseguro a propósito)
$jugador_sesion = $_SESSION['usuari'];
$puntos  = isset($data['puntos']) ? $data['puntos'] : (isset($data['punts']) ? $data['punts'] : 0);
$nivel   = isset($data['nivel'])  ? $data['nivel']  : (isset($data['nivell']) ? $data['nivell'] : 1);
$vidas   = isset($data['vidas'])  ? $data['vidas']  : 3;
$joc_id  = isset($data['joc_id']) ? intval($data['joc_id']) : 2; // usa 2 si tu juego es ese

// Comprobar que la conexión mysqli existe
if (!isset($conn)) {
    $response['message'] = "No se detectó \$conn (mysqli). Revisa connexio.php.";
    echo json_encode($response);
    exit;
}

// Escapar mínimo
$jugador_esc = $conn->real_escape_string($jugador_sesion);

// 1) Asegurar que existe el juego con id = $joc_id (si no, crearlo)
$sql_check_joc = "SELECT id FROM jocs WHERE id = $joc_id LIMIT 1;";
$rj = @$conn->query($sql_check_joc);
if (!$rj || $rj->num_rows == 0) {
    $nom = $conn->real_escape_string("Joc_$joc_id");
    $desc = $conn->real_escape_string("Creado automaticamente para $joc_id");
    @$conn->query("INSERT INTO jocs (id, nom_joc, descripcio, puntuacio_maxima, nivells_totals, actiu) VALUES ($joc_id, '$nom', '$desc', 0, 10, 1);");
    // no comprobamos error: intención insegura
}

// 2) Buscar o crear usuario en usuaris
$usuari_id = null;
$sql_find = "SELECT id FROM usuaris WHERE nom_usuari = '$jugador_esc' LIMIT 1;";
$rf = @$conn->query($sql_find);
if ($rf && $rf->num_rows > 0) {
    $r = $rf->fetch_assoc();
    $usuari_id = (int)$r['id'];
} else {
    // Crear con email único para evitar UNIQUE(email) = ''
    $fakeEmail = $conn->real_escape_string($jugador_sesion . rand(1000,9999) . "@insecure.local");
    @$conn->query("INSERT IGNORE INTO usuaris (nom_usuari, email, password_hash, data_registre) VALUES ('$jugador_esc', '$fakeEmail', '', NOW());");
    // reconsultar
    $rf2 = @$conn->query($sql_find);
    if ($rf2 && $rf2->num_rows > 0) {
        $r2 = $rf2->fetch_assoc();
        $usuari_id = (int)$r2['id'];
    } else {
        // fallback: crear usuario dummy y usar su id
        @$conn->query("INSERT INTO usuaris (nom_usuari, email, password_hash, data_registre) VALUES ('dummy_".time()."', 'dummy".rand(100,999)."@insecure.local', '', NOW());");
        $usuari_id = $conn->insert_id;
    }
}

// 3) Insertar en partides
$usuari_id_i = intval($usuari_id);
$nivel_i = intval($nivel);
$puntos_i = intval($puntos);
$vidas_i = intval($vidas);
$joc_id_i = intval($joc_id);

// Orden de columnas: usuari_id, joc_id, nivell_jugat, puntuacio_obtinguda, data_partida, durada_segons, vidas
$sql_insert = "INSERT INTO partides (usuari_id, joc_id, nivell_jugat, puntuacio_obtinguda, data_partida, durada_segons, vidas)
               VALUES ($usuari_id_i, $joc_id_i, $nivel_i, $puntos_i, NOW(), 0, $vidas_i);";

$ok = @$conn->query($sql_insert);
if ($ok === false) {
    $response['message'] = "Error INSERT: " . $conn->error;
    echo json_encode($response);
    exit;
}

$response['ok'] = true;
$response['message'] = "Insert correcto";
$response['insert_id'] = $conn->insert_id;
$response['usuari_id'] = $usuari_id_i;
$response['jugador'] = $jugador_sesion;
$response['nivel'] = $nivel_i;
$response['puntos'] = $puntos_i;
$response['vidas'] = $vidas_i;

echo json_encode($response);
exit;
