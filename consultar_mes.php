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
    else{
        require("conexion_db.php");
        require("funciones.php");

        $date= getdate();
        $anio= $date["year"];
        $mes= $date["mon"];
        $dias= dias_mes($mes, $anio);
        $fecha1= "$anio-$mes-01";
        $fecha2= "$anio-$mes-$dias";
        
        $empleado = consultar_empleado($conn, $_SESSION["id"]);
        $respuesta= consultar_horarios($empleado, $fecha1, $fecha2);

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
                <a class="boton-azul"href="index.php">Volver</a>
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
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Total</td>
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
                    echo genera_fila($fila, $aux, $empleado);
                }
        echo '</table>
        <br>';
        $horas= formatear_horas($horas);
        echo '<p class="texto-centrado">Horas trabajadas '.$horas["horas"].':'.$horas["minutos"].'</p>';
        echo '<br>
        <a class="boton-azul"href="index.php">Volver</a>
        </body>
        </html>
        ';
    }
?>