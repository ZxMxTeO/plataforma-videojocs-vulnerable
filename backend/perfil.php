<?php
session_start();
require_once '../Other/connexio.php';

if (!isset($_SESSION['id']) && !isset($_SESSION['usuari'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : null;
$nom_usuari_sess = isset($_SESSION['usuari']) ? $_SESSION['usuari'] : null;

$uploadDir = "../uploads/";
$mensajeFoto = "";
if (isset($_POST['subir_foto']) && isset($_FILES['foto'])) {
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    $nombreArchivo = basename($_FILES['foto']['name']);
    $rutaDestino = $uploadDir . $nombreArchivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
        $urlEnBD = "uploads/" . $nombreArchivo;
        if ($id !== null) {
            $sqlUpdate = "UPDATE usuaris SET imatge_url = '$urlEnBD' WHERE id = $id";
        } else {
            $sqlUpdate = "UPDATE usuaris SET imatge_url = '$urlEnBD' WHERE nom_usuari = '$nom_usuari_sess'";
        }
        $conn->query($sqlUpdate);
        $mensajeFoto = "Imagen subida correctamente.";
    } else {
        $mensajeFoto = "Error al subir la imagen.";
    }
}

if ($id !== null) {
    $sql = "SELECT nom_usuari, email, nom_complet, data_registre, imatge_url FROM usuaris WHERE id = $id";
} else {
    $sql = "SELECT nom_usuari, email, nom_complet, data_registre, imatge_url FROM usuaris WHERE nom_usuari = '$nom_usuari_sess'";
}

$result = $conn->query($sql);
if ($result && $result->num_rows === 1) {
    $usuari = $result->fetch_assoc();
} else {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
$conn->close();

$fotoMostrar = "../img/foto-perfil.png";
if (!empty($usuari['imatge_url'])) {
    $fotoMostrar = "../" . $usuari['imatge_url'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/perfil.css">
</head>
<body>
    <div id="contenedor">
        <div id="usuario">
            <div id="imagen">
                <img src="<?= $fotoMostrar ?>" alt="Foto de perfil">
                <form method="post" enctype="multipart/form-data">
                    <input type="file" name="foto" accept="image/*" required>
                    <button type="submit" name="subir_foto">Subir foto</button>
                </form>
                <?php if ($mensajeFoto !== ""): ?>
                    <p><?= $mensajeFoto ?></p>
                <?php endif; ?>
            </div>

            <div id="info">
                <p><strong>Nombre de usuario:</strong> <?= $usuari['nom_usuari'] ?></p>
                <p><strong>Nombre completo:</strong> <?= $usuari['nom_complet'] ?></p>
                <p><strong>Email:</strong> <?= $usuari['email'] ?></p>
                <p><strong>Fecha de registro:</strong> <?= $usuari['data_registre'] ?></p>

                <form method="post">
                    <button type="submit" name="logout">Cerrar sesión</button>
                </form>
                <a href="plataforma.php" class="boton">Volver a la plataforma</a>
            </div>
        </div>
    </div>
</body>
</html>
