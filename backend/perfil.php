<?php
session_start();
require_once '../Other/connexio.php';

// Comprobamos sesión
if (!isset($_SESSION['id']) && !isset($_SESSION['usuari'])) {
    header("Location: ../index.php");
    exit();
}

// Manejar logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

// ID del usuario (priorizamos id en sesión)
$id = isset($_SESSION['id']) ? (int)$_SESSION['id'] : null;
$nom_usuari_sess = isset($_SESSION['usuari']) ? $_SESSION['usuari'] : null;

// Ruta de uploads en servidor (relativa desde el script)
$uploadDir = "../uploads/";

// Manejar subida de foto (form con name="subir_foto")
$mensajeFoto = "";
if (isset($_POST['subir_foto']) && isset($_FILES['foto'])) {
    // Crear carpeta si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Nombre original del archivo (usamos basename para quitar rutas)
    $nombreArchivo = basename($_FILES['foto']['name']);
    $rutaDestino = $uploadDir . $nombreArchivo;

    // Mover archivo (SIN VALIDACIONES - intencionalmente inseguro)
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
        // Guardar la URL relativa en la BD (consulta insegura a propósito)
        // Se guarda por ejemplo: uploads/miFoto.jpg
        $urlEnBD = "uploads/" . $nombreArchivo;

        if ($id !== null) {
            $sqlUpdate = "UPDATE usuaris SET imatge_url = '$urlEnBD' WHERE id = $id";
        } else {
            // si no hay id, intentar actualizar por nom_usuari si existe en sesión
            $sqlUpdate = "UPDATE usuaris SET imatge_url = '$urlEnBD' WHERE nom_usuari = '$nom_usuari_sess'";
        }

        $conn->query($sqlUpdate); // inseguro, pero es lo que pediste
        $mensajeFoto = "Imagen subida correctamente.";
    } else {
        $mensajeFoto = "Error al subir la imagen.";
    }
}

// Obtener datos del usuario incluyendo foto_perfil
if ($id !== null) {
    $sql = "SELECT nom_usuari, email, nom_complet, data_registre, imatge_url FROM usuaris WHERE id = $id";
} else {
    // si no hay id, consultamos por nom_usuari (inseguro)
    $sql = "SELECT nom_usuari, email, nom_complet, data_registre, imatge_url FROM usuaris WHERE nom_usuari = '$nom_usuari_sess'";
}

$result = $conn->query($sql);
if ($result && $result->num_rows === 1) {
    $usuari = $result->fetch_assoc();
} else {
    // Si no encontramos, destruimos sesión y redirigimos al login
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$conn->close();

// Determinar qué imagen mostrar: foto_perfil desde BD o default
$fotoMostrar = "../img/foto-perfil.png";
if (!empty($usuari['imatge_url'])) {
    // La columna guarda la URL relativa tal como "uploads/nombre.jpg"
    $fotoMostrar = "../" . $usuari['imatge_url']; // quedaría ../uploads/nombre.jpg (ajusta si tu estructura es distinta)
}
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
            <div id="imagen" style="text-align:center;">
                <!-- Mostrar foto (si existe en BD, la cargamos; si no, default) -->
                <img src="<?= $fotoMostrar ?>" alt="Foto de perfil" style="width:150px; height:150px; border-radius:50%; object-fit:cover;">

                <!-- Formulario para subir foto (inseguro, sin validaciones) -->
                <form method="post" enctype="multipart/form-data" style="margin-top:10px;">
                    <input type="file" name="foto" accept="image/*" required>
                    <button type="submit" name="subir_foto">Subir foto</button>
                </form>

                <?php if ($mensajeFoto !== ""): ?>
                    <p><?= $mensajeFoto ?></p>
                <?php endif; ?>
            </div>

            <div id="info">
                <ul>
                    <li>Nombre de usuario: <?= $usuari['nom_usuari'] ?></li>
                    <li>Nombre completo: <?= $usuari['nom_complet'] ?></li>
                    <li>Email: <?= $usuari['email'] ?></li>
                    <li>Fecha de registro: <?= $usuari['data_registre'] ?></li>
                </ul>

                <!-- Botón para cerrar sesión -->
                <form method="post" style="margin-top:20px;">
                    <button type="submit" name="logout">Cerrar sesión</button>
                </form>
                <a href="plataforma.php" class="boton">Volver a la plataforma</a>
            </div>
        </div>
    </div>
</body>
</html>