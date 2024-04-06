<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit();
}
if($_SESSION["tipo"]==2){
    header("Location: administrador.php");
    exit();
}

require "conexion_db.php";

//Declaracion de variables
$fecha= $_POST["fecha"];
$hora= $_POST["hora"];
$long= $_POST["long"];
$lat= $_POST["lat"];
$empleado= $_SESSION["id"];

function determinar_ubicacion($long_aux, $lat_aux){ //Determina si el usuario esta en el lugar indicado
    $long_sup= -65.409172;
    $lat_sup= -24.792140;
    $long_inf= -65.411172;
    $lat_inf= -24.794140;

    return $long_aux< $long_sup && $long_aux> $long_inf && $lat_aux< $lat_sup && $lat_aux> $lat_inf;
}

if(!determinar_ubicacion($long,$lat)){
    echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/style.css">
            <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
            <title>Registro</title>
        </head>
        <body class="bg-principal">
            <div class="flex-columna">
                <label id="iniciar-sesion" class="texto-rojo">No estás en la oficina</label>
                <a class="boton-azul" href="index.php">Regresar</a>
            </div>
        </body>
        </html>
        ';
        exit();
}
else{
    $consulta= "SELECT * FROM Horarios WHERE Fecha=\"$fecha\" AND id_empleado=".$_SESSION["id"];
    $consulta_post= "";

    $resultado= mysqli_query($conn, $consulta);
    if(mysqli_num_rows($resultado)>0){
        global $consulta_post;
        $consulta_post= "UPDATE Horarios SET Salida=\"$hora\" WHERE Fecha=\"$fecha\" AND id_empleado= \"$empleado\"";
    }
    else{
        global $consulta_post;
        $consulta_post= "INSERT INTO Horarios (Fecha, Entrada, id_empleado) VALUES (\"$fecha\", \"$hora\", $empleado)";
    }

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
            <title>Registro</title>
        </head>
        <body class="bg-principal">
            <div class="flex-columna">
                <label id="iniciar-sesion" class="texto-verde">Registro exitoso!</label>
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
            <title>Registro</title>
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
}

?>