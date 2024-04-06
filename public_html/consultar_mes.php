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

        function formatear_horas($horas){
            if($horas["minutos"]< 10){
                $horas["minutos"]= "0".$horas["minutos"];
            }
            return $horas;
        }

        function genera_fila($fila, $horas){
            if(!$fila["Salida"]){
                $salida= "-";
            }
            else{
                $salida= $fila["Salida"];
            }
            $horas= formatear_horas($horas);
            $aux= "<tr>
                        <td>".$fila["Fecha"]."</td>
                        <td>".$fila["Entrada"]."</td>
                        <td>".$salida."</td>
                        <td>".$horas["horas"].":".$horas["minutos"]."</td>
                    </tr>
            ";
            return $aux;
        }

        function contar_horas($tiempo1, $tiempo2){
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

        $date= getdate();
        $anio= $date["year"];
        $mes= $date["mon"];
        $dias= dias_mes($mes, $anio);
        $fecha1= "$anio-$mes-01";
        $fecha2= "$anio-$mes-$dias";
        
        $consulta= "SELECT * FROM Horarios WHERE id_empleado=".$_SESSION["id"]." AND (Fecha BETWEEN \"".$fecha1."\" AND \"".$fecha2."\") ORDER BY Fecha";
        $respuesta= mysqli_query($conn, $consulta);

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
                        $tiempo1= formar_array($fila["Entrada"]);
                        $tiempo2= formar_array($fila["Salida"]);
                        $aux= contar_horas($tiempo1,$tiempo2);
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
        <a class="boton-azul"href="index.php">Volver</a>
        </body>
        </html>
        ';
    }
?>