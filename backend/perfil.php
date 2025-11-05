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

$id = $_SESSION['id'] ?? null;
$nom_usuari_sess = $_SESSION['usuari'] ?? null;

$uploadDir = "../uploads/";
$mensajeFoto = "";
if (isset($_POST['subir_foto']) && isset($_FILES['foto'])) {
    if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
    $nombreArchivo = basename($_FILES['foto']['name']);
    $rutaDestino = $uploadDir . $nombreArchivo;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
        $urlEnBD = "uploads/" . $nombreArchivo;
        $sqlUpdate = $id
            ? "UPDATE usuaris SET imatge_url = '$urlEnBD' WHERE id = $id"
            : "UPDATE usuaris SET imatge_url = '$urlEnBD' WHERE nom_usuari = '$nom_usuari_sess'";
        $conn->query($sqlUpdate);
        $mensajeFoto = "✅ Imagen subida correctamente.";
    } else {
        $mensajeFoto = "❌ Error al subir la imagen.";
    }
}

$mensajePass = "";
if (isset($_POST['cambiar_pass'])) {
    $passActual = $_POST['pass_actual'] ?? '';
    $passNueva = $_POST['pass_nueva'] ?? '';
    $passConfirm = $_POST['pass_confirm'] ?? '';

    if ($passActual === '' || $passNueva === '' || $passConfirm === '') {
        $mensajePass = "⚠️ Rellena todos los campos.";
    } elseif ($passNueva !== $passConfirm) {
        $mensajePass = "❌ Las contraseñas no coinciden.";
    } else {
        $sqlPass = $id
            ? "SELECT password_hash FROM usuaris WHERE id = $id"
            : "SELECT password_hash FROM usuaris WHERE nom_usuari = '$nom_usuari_sess'";
        $res = $conn->query($sqlPass);
        $fila = $res->fetch_assoc();

        if ($fila && $fila['password_hash'] === $passActual) {
            $sqlUpdate = $id
                ? "UPDATE usuaris SET password_hash = '$passNueva' WHERE id = $id"
                : "UPDATE usuaris SET password_hash = '$passNueva' WHERE nom_usuari = '$nom_usuari_sess'";
            $conn->query($sqlUpdate);
            $mensajePass = "✅ Contraseña actualizada correctamente.";
        } else {
            $mensajePass = "❌ Contraseña actual incorrecta.";
        }
    }
}

$sql = $id
    ? "SELECT nom_usuari, email, nom_complet, data_registre, imatge_url FROM usuaris WHERE id = $id"
    : "SELECT nom_usuari, email, nom_complet, data_registre, imatge_url FROM usuaris WHERE nom_usuari = '$nom_usuari_sess'";

$result = $conn->query($sql);
$usuari = $result->fetch_assoc() ?? [];

$fotoMostrar = !empty($usuari['imatge_url']) ? "../" . $usuari['imatge_url'] : "../img/foto-perfil.png";
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/perfil.css">
    <style>
        /* Estilo del desplegable de contraseña */
        .change-pass-container {
            margin-top: 1rem;
        }
        .toggle-btn {
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        .toggle-btn:hover {
            background-color: #1673d1;
        }
        .password-form {
            display: none;
            background-color: #f4f8ff;
            border: 1px solid #ccd9ff;
            border-radius: 10px;
            margin-top: 10px;
            padding: 15px;
            width: 90%;
            animation: fadeIn 0.4s ease-in-out;
        }
        .password-form input {
            width: 100%;
            margin: 5px 0;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #aaa;
        }
        .password-form button {
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 8px;
            transition: 0.3s;
        }
        .password-form button:hover {
            background-color: #0b6bd6;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .mensaje-pass {
            margin-top: 8px;
            font-weight: bold;
        }
    </style>
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
                <?php if ($mensajeFoto): ?>
                    <p><?= $mensajeFoto ?></p>
                <?php endif; ?>
            </div>

            <div id="info">
                <p><strong>Nombre de usuario:</strong> <?= $usuari['nom_usuari'] ?></p>
                <p><strong>Nombre completo:</strong> <?= $usuari['nom_complet'] ?></p>
                <p><strong>Email:</strong> <?= $usuari['email'] ?></p>
                <p><strong>Fecha de registro:</strong> <?= $usuari['data_registre'] ?></p>

                <!-- 🔹 Botón desplegable para cambiar contraseña -->
<!-- 🔹 Contenedor de acciones alineadas -->
                <div class="acciones-usuario">
                    <button type="button" class="toggle-btn" onclick="togglePasswordForm()">🔒 Cambiar contraseña</button>

                    <form method="post" style="display:inline;">
                        <button type="submit" name="logout" class="logout-btn">🚪 Cerrar sesión</button>
                    </form>
                </div>

                <!-- 🔹 Formulario desplegable -->
                <form method="post" class="password-form" id="passwordForm">
                    <input type="password" name="pass_actual" placeholder="Contraseña actual" required>
                    <input type="password" name="pass_nueva" placeholder="Nueva contraseña" required>
                    <input type="password" name="pass_confirm" placeholder="Confirmar nueva contraseña" required>
                    <button type="submit" name="cambiar_pass">Actualizar</button>
                </form>

                <?php if ($mensajePass): ?>
                    <p class="mensaje-pass"><?= $mensajePass ?></p>
                <?php endif; ?>

                <a href="plataforma.php" class="boton volver">⬅️ Volver a la plataforma</a>

            </div>
        </div>
    </div>

    <script>
        function togglePasswordForm() {
            const form = document.getElementById("passwordForm");
            form.style.display = form.style.display === "block" ? "none" : "block";
        }
    </script>
</body>
</html>
