<?php
session_start();
//Si hi ha una sessió reenviar a plataforma.php
if (isset($_SESSION['usuari'])) {
    header("Location: ./backend/plataforma.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>
    <div id="container">
        <div id="formulario">
            <h2>Login de usuario</h2>
            <form method="post">
                <label for="email">Correo electronico</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Entrar</button>
                <p class="register-text">Todavia sin cuenta? <a href="register.php">Registrate</a></p>
            </form>
        <?php 
        require_once 'Other/connexio.php';

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($email !== '' && $password !== '') {

                // Consulta insegura a propósito (como pediste)
                $sql = "SELECT * FROM usuaris WHERE email = '$email' AND password_hash = '$password'";
                $result = $conn->query($sql);

                if ($result && $result->num_rows === 1) {
                    $usuari = $result->fetch_assoc();

                    // Guardar ID y nombre en la sesión (para que perfil.php pueda usarlo)
                    // Asegúrate de que tu tabla 'usuaris' tiene la columna 'id' (o cámbialo si tiene otro nombre)
                    $_SESSION['id'] = $usuari['id'];
                    $_SESSION['usuari'] = $usuari['nom_usuari'];
                    $_SESSION['email'] = $usuari['email']; // opcional

                    header("Location: ./backend/plataforma.php"); 
                    exit();
                } else {
                    echo "<p style='color:red;text-align:center;'>Email o contraseña incorrectos.</p>";
                }
            }
        }
        ?>
        </div>
        
    </div>
</body>
</html>
