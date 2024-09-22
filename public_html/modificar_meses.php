<?php
session_start();
if(!isset($_SESSION["id"])){
    header("Location: login.php");
    exit();
}
if($_SESSION["tipo"]!=3){
    header("Location: index.php");
    exit();
}


require("conexion_db.php");
require("funciones.php");

    if(($_POST["empleado"])==""){
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/style.css">
            <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
            <title>Mes</title>
        </head>
        <body class="bg-principal flex-column p-0">
            <h1 class="texto-rojo">No selecciono un empleado valido</h1>
            <a class="boton-azul"href="administrador.php">Volver</a>
        </body>
        </html>';
        exit();
    }

    $empleado= $_POST["empleado"];
    $mes= $_POST["mes"];
    $anio= 2024;

    $dias= dias_mes($mes, $anio);
    $fecha1= "$anio-$mes-01";
    $fecha2= "$anio-$mes-$dias";
    
    $empleado = consultar_empleado($conn, $empleado);
    $respuesta= consultar_horarios($empleado, $fecha1, $fecha2);

    $nombre_empleado= $empleado["Nombre y apellido"];

    if(mysqli_num_rows($respuesta)==0){
        echo '<!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/style.css">
            <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
            <title>Mes</title>
        </head>
        <body class="bg-principal flex-column p-0">
            <h1 class="texto-rojo">No se encontraron registros para este mes</h1>
            <a class="boton-azul"href="administrador.php">Volver</a>
        </body>
        </html>';
        exit();
    }

    $horas= array(
        "horas"=> 0,
        "minutos"=> 0,
    );

    echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/style.css">
            <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
            <title>Mes</title>
        </head>
        <body class="bg-principal flex-column p-0">
            <h1>'.$nombre_empleado.'</h1>
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Modificar</th>
                </tr>';
                while($fila= mysqli_fetch_assoc($respuesta)){
                    if($fila["Salida"]!= NULL){
                        $aux= contar_horas($fila["Entrada"],$fila["Salida"], $empleado["Entrada"], $empleado["Salida"], $fila["Horas_extra"]);
                        $horas["horas"]+= $aux["horas"];
                        $horas["minutos"]+= $aux["minutos"];
                        if($horas["minutos"]>=60){
                            $horas["horas"]++;
                            $horas["minutos"]-=60;
                        }
                    }
                    else{
                        $aux["horas"]= 0;
                        $aux["minutos"]= 0;
                    }
                    echo genera_fila_modificable($fila, $aux);
                }
        echo '</table>
        <br>';
        $horas= formatear_horas($horas);
        echo '<p class="texto-centrado">Horas trabajadas '.$horas["horas"].':'.$horas["minutos"].'</p>';
        echo '<br>
        <a class="boton-azul"href="administrador.php">Volver</a>
        <form action="modificar_password.php" method="post">
            <input class="oculto" type="text" name="empleado" id="empleado" value="'.$empleado["id_empleado"].'">
            <button class="boton-azul" type="submit">Resetear password</button>
        </form>
        </body>
        </html>
        ';
?>