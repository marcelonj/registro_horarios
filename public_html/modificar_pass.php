<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Modificar contraseña</title>
</head>
<body class="bg-principal">
    <form class="flex-columna" action="modificar_password.php" method="post">
        <label for="pass">Nueva contraseña</label>
        <input type="text" name="pass" id="pass" placeholder="Ingrese nueva contraseña">
        <button class="boton-azul" type="submit">Modificar</button>
    </form>
</body>
</html>