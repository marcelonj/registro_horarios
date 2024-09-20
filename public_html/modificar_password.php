<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit();
}

require "conexion_db.php";

if(isset($_POST["pass"])){
    $hash= password_hash($_POST["pass"], PASSWORD_DEFAULT);
    $id= $_SESSION["id_user"];
}
else{
    $hash= password_hash("1234", PASSWORD_DEFAULT);
    $id= $_POST["empleado"];
}

$consulta_post= "UPDATE Usuarios SET Hash=\"{$hash}\" WHERE id_usuario={$id}";

$resultado_post= mysqli_query($conn, $consulta_post);

if($resultado_post){
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
        <title>Exito</title>
    </head>
    <body class="bg-principal">
        <div class="flex-columna">
            <label id="iniciar-sesion" class="texto-verde">Modificacion exitosa!</label>
            <a class="boton-azul" href="index.php">Regresar</a>
        </div>
    </body>
    </html>
    ';
}
else{
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
        <title>Error</title>
    </head>
    <body class="bg-principal">
        <div class="flex-columna">
            <label id="iniciar-sesion" class="texto-rojo">Ocurrio un error</label>
            <a class="boton-azul" href="index.php">Regresar</a>
        </div>
    </body>
    </html>
    ';
}    
?>