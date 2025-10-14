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
        <form action="FormularioR">
            <label for="Nombre"></label>
            <input type="text" id="Nombre" name="Nombre" placeholder="Nombre" required><br><br>
            <label for="Apellidos"></label>
            <input type="text" id="Apellidos" name="Apellidos" required><br><br>
            <label for="Email"></label>
            <input type="email" id="Email" name="Email" required><br><br>
            <label for="Contraseña"></label>
            <input type="password" id="Contraseña" name="Contraseña" required><br><br>
            <label for="Edad"></label>
            <input type="number" id="Edad" name="Edad" required><br><br>
            <label for="genero"></label>
            <select id="genero" name="genero">
            <option value="">Genero...</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
            </select><br><br>
            <a href="./index.php">
                <button type="submit">Enviar</button>
            </a>
        </form>
    </div>
</body>
</html>