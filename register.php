<?php
// incluir la conexión
require_once 'Other/connexio.php';

// si se ha enviado el formulario, recoger datos en variables y hacer INSERT
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // recoger datos del formulario y asignar valores vacíos por defecto si no existen
    $nom_usuari  = $_POST['nom_usuari'] ?? '';
    $nom_complet = $_POST['nom_complet'] ?? '';
    $email       = $_POST['email'] ?? '';
    $password    = $_POST['password'] ?? '';

    // comprobar que los campos obligatorios no están vacíos
    if ($nom_usuari !== '' && $email !== '' && $password !== '') {

        // INSERT directo (no seguro, solo para pruebas)
        $sql = "INSERT INTO usuaris (nom_usuari, email, password_hash, nom_complet)
                VALUES ('$nom_usuari', '$email', '$password', '$nom_complet')";

        $resultado = $conn->query($sql);

        if ($resultado) {
            // redirigir a la página de inicio de sesión
            header("index.php"); // <-- cambia aquí la ruta
            exit(); // siempre poner exit después de header
        } else {
            echo "Error al crear l'usuari: " . $conn->error;
        }
    } else {
        echo "Rellena todos los campos obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registro Usuarios</title>
    <meta name="description" content="Registro usuarios." />
    <meta name="author" content="Matthew Luna y Marc Pimentel" />
    <meta name="copyright" content="Matthew Luna y Marc Pimentel" />
    <link rel="stylesheet" href="./css/register.css">
</head>
<body>
    <div id="FondoFormulario">
       <h1>Registro Nuevo Usuario</h1>
        <form action="" method="POST">
            <label for="nom_usuari"></label>
            <input type="text" id="nom_usuari" name="nom_usuari" placeholder="Nom Usuari" required><br><br>

            <label for="nom_complet"></label>
            <input type="text" id="nom_complet" name="nom_complet" placeholder="Nom Complet"><br><br>

            <label for="email"></label>
            <input type="email" id="email" name="email" placeholder="Email" required><br><br>

            <label for="password"></label>
            <input type="password" id="password" name="password" placeholder="Contraseña" required><br><br>

            <button type="submit">Crear usuario</button>
        </form>
    </div>
</body>
</html>
