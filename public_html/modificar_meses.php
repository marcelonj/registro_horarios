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

    function dias_mes($mes, $anio){
        switch($mes){
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12: 
                return 31;
                break;
            case 4:
            case 6:
            case 9:
            case 11: 
                return 30;
                break;
            case 2:
                if($anio%400==0 || ($anio%4==0 && $anio%100!=0)){
                    return 29;
                }
                else{
                    return 28;
                }
        }
    }

    function contar_horas($tiempo1, $tiempo2, $extras){
        $horas1= $tiempo1["horas"];
        $minutos1= $tiempo1["minutos"];
        $horas2= $tiempo2["horas"];
        $minutos2= $tiempo2["minutos"];
        $horas= 0;
        $minutos= 0;
        if($minutos1<=$minutos2){
            $minutos= $minutos2-$minutos1;
            $horas= $horas2-$horas1;
        }
        else{
            $horas1++;
            $minutos2+= 60;
            $minutos= $minutos2-$minutos1;
            $horas= $horas2-$horas1;
        }
        $tiempo["horas"]= $horas;
        $tiempo["minutos"]= $minutos;
        return $tiempo;
    }

    function formar_array($str){
        $array= explode(":", $str);
        $array_ind["horas"]= (int)$array[0];
        $array_ind["minutos"]= (int)$array[1];
        return $array_ind;
    }

    function formatear_horas($horas){
        if($horas["minutos"]< 10){
            $horas["minutos"]= "0".$horas["minutos"];
        }
        return $horas;
    }

    function genera_fila($fila){
        if(!$fila["Salida"]){
            $salida= "-";
        }
        else{
            $salida= $fila["Salida"];
        }
        $aux= "<tr>
                    <td>".$fila["Fecha"]."</td>
                    <td>".$fila["Entrada"]."</td>
                    <td>".$salida."</td>
                    <td><a href=\"modificar.php?id=".$fila["id_horario"]."\">Mod</a></td>
                </tr>
        ";
        return $aux;
    }

    $dias= dias_mes($mes, $anio);
    $fecha1= "$anio-$mes-01";
    $fecha2= "$anio-$mes-$dias";
    
    $id_empleado = mysqli_query($conn, "SELECT id_empleado FROM Usuarios WHERE id_usuario=".$empleado);
    $id_empleado = mysqli_fetch_assoc($id_empleado);
    $id_empleado = $id_empleado["id_empleado"];
    
    $consulta= "SELECT * FROM Horarios WHERE id_empleado=".$id_empleado." AND (Fecha BETWEEN \"".$fecha1."\" AND \"".$fecha2."\")";
    $consulta2= "SELECT * FROM Empleados WHERE id_empleado=".$id_empleado;
    $consulta_jornada= "SELECT * FROM Empleados e INNER JOIN Jornadas j on e.id_jornada = j.id_jornada WHERE id_empleado =";
    $respuesta= mysqli_query($conn, $consulta);
    $respuesta2= mysqli_query($conn, $consulta2);

    $fila2= mysqli_fetch_assoc($respuesta2);
    $nombre_empleado= $fila2["Nombre y apellido"];

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
                    <th>Modificar</td>
                </tr>';
                while($fila= mysqli_fetch_assoc($respuesta)){
                    if($fila["Salida"]!= NULL){
                        $tiempo1= formar_array($fila["Entrada"]);
                        $tiempo2= formar_array($fila["Salida"]);
                        var_dump($fila);
                        $query = $consulta_jornada.$fila["id_empleado"];
                        $jornada = mysqli_query($conn, $query);
                        var_dump(mysqli_fetch_assoc($jornada));
                        $aux= contar_horas($tiempo1,$tiempo2,$fila["Horas_extra"]);
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
                    echo genera_fila($fila, $aux);
                }
        echo '</table>
        <br>';
        $horas= formatear_horas($horas);
        echo '<p class="texto-centrado">Horas trabajadas '.$horas["horas"].':'.$horas["minutos"].'</p>';
        echo '<br>
        <a class="boton-azul"href="administrador.php">Volver</a>
        <form action="modificar_password.php" method="post">
            <input class="oculto" type="text" name="empleado" id="empleado" value="'.$empleado.'">
            <button class="boton-azul" type="submit">Resetear password</button>
        </form>
        </body>
        </html>
        ';
?>