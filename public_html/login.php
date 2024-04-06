<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Login</title>
</head>
<body class="bg-principal">
    <form action="comprobar_login.php" method="post" class="flex-columna">
        <label id="iniciar-sesion">Iniciar sesión</label>
        <label for="usuario">Usuario</label>
        <input type="text" name="usuario">
        <label for="password">Contraseña</label>
        <input type="password" name="password">
        <button type="submit" class="boton-azul">Ingresar</button>
    </form>
</body>
<script src="scripts/script.js"></script>
</html>