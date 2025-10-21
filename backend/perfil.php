<?php
session_start();
require_once '../Other/connexio.php';

// Verificar que la sesión está activa
if (!isset($_SESSION['id'])) {
    header("Location: ../index.php"); // redirige al login si no hay sesión
    exit();
}

// Obtener el ID del usuario desde la sesión
$id = $_SESSION['id'];

// Consulta insegura (como pediste)
$sql = "SELECT nom_usuari, email, nom_complet, data_registre FROM users WHERE id = $id";
$result = $conn->query($sql);
$usuari = $result->fetch_assoc();

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario</title>
    <link rel="stylesheet" href="../css/perfil.css">
</head>
<body>
    <div id="contenedor">
        <div id="usuario">
            <div id="imagen">
                <img src="../img/default.png" alt="Foto de perfil">
            </div>
            <div id="info">
                <ul>
                    <li>Nombre de usuario: <?= $usuari['nom_usuari'] ?></li>
                    <li>Nombre completo: <?= $usuari['nom_complet'] ?></li>
                    <li>Email: <?= $usuari['email'] ?></li>
                    <li>Fecha de registro: <?= $usuari['data_registre'] ?></li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
