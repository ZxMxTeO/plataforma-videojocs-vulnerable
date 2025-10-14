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
            <form action="./backend/perfil.php" method="post">
                <label for="email">Correo electronico</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Entrar</button>
                <p class="register-text">Todavia sin cuenta? <a href="register.php">Registrate</a></p>
            </form>
        </div>
    </div>
</body>
</html>