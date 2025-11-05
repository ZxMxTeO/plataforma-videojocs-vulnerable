<?php
// difficulty.php - INSEGURO: devuelve parametros según nivel
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$nivel = isset($_GET['nivel']) ? intval($_GET['nivel']) : 1;

// regla simple: cuanto mayor nivel, menos tiempo entre apariciones y menos vida del objeto
$aparecion_base = 1000; // ms
$vida_base = 3000;      // ms

$aparecion = max(200, $aparecion_base - (($nivel - 1) * 80));
$vidaObjeto = max(500, $vida_base - (($nivel - 1) * 200));

// puedes añadir otros parámetros (probabilidad, tamaño, etc.)
echo json_encode([
    "nivel" => $nivel,
    "aparecion" => $aparecion,
    "vidaObjeto" => $vidaObjeto,
    "mensaje" => "dificultad (insegura)"
]);
