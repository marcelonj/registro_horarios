<?php
    //Bibloteca de funciones

    function formar_array($str){
        /* 
        Funcion que recibe un string que representa un time y lo convierte en un array con las horas y los minutos.
        */
        $array= explode(":", $str);
        $array_ind["horas"]= (int)$array[0];
        $array_ind["minutos"]= (int)$array[1];
        return $array_ind;
    }

    function contar_horas($entrada, $salida, $turno_entrada, $turno_salida, $extras){
        /* 
        Funcion que recibe dos arrays (uno con las horas de entrada y salida y otro con el turno que cumple el empleado) y un booleano (representa si se autorizaron horas extras) y devuelve un array con las horas y minutos que acumulo el empleado en el turno.
        */
        $entrada = formar_array($entrada);
        $salida = formar_array($salida);
        $turno_entrada = formar_array($turno_entrada);
        $turno_salida = formar_array($turno_salida);
        $horas= 0;
        $minutos= 0;

        if (!$extras){ //Si no se autorizaron horas extras se compueba que la entrada y salida no excedan la jornada
            if ($entrada["horas"] < $turno_entrada["horas"]){
                $entrada["horas"] = $turno_entrada["horas"];
                $entrada["minutos"] = $turno_entrada["minutos"];
            }
            if ($entrada["horas"] == $turno_entrada["horas"] && $entrada["minutos"] < $turno_entrada["minutos"]){
                $entrada["minutos"] = $turno_entrada["minutos"];
            }
            if ($salida["horas"] > $turno_salida["horas"]){
                $salida["horas"] = $turno_salida["horas"];
                $salida["minutos"] = $turno_salida["minutos"];
            }
            if ($salida["horas"] == $turno_salida["horas"] && $salida["minutos"] > $turno_salida["minutos"]){
                $salida["minutos"] = $turno_salida["minutos"];
            }
        }

        //Calculo de horas y minutos
        if($salida["minutos"] < $entrada["minutos"]){
            $entrada["horas"]++;
            $salida["minutos"]+= 60;
        }
        $minutos= $salida["minutos"]-$entrada["minutos"];
        $horas= $salida["horas"]-$entrada["horas"];
        $tiempo["horas"]= $horas;
        $tiempo["minutos"]= $minutos;

        return $tiempo;
    }

    function viaje_tiempo($entrada, $salida){
        /* 
        Funcion que recibe dos fechas en formato string y comprueba que la salida no se produzca antes que la entrada.
        */
        $entrada = formar_array($entrada);
        $salida = formar_array($salida);
        $aux = False;
        if ($salida["horas"] < $entrada["horas"]){
            $aux = True;
        }
        elseif ($salida["horas"] == $entrada["horas"]){
            if($salida["minutos"] < $entrada["minutos"]){
                $aux = True;
            }
        }
        return $aux;
    }

    function dias_mes($mes, $anio){
        /* 
        Funcion que recibe un mes y un anio y devuelve la cantidad de dias que contiene el mes.
        */
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
        /* Funcion que recibe una hora de tipo int y en caso de tener un solo digito le agrega un cero adelante y la devuelve formateada. */
        if($horas["minutos"]< 10){
            $horas["minutos"]= "0".$horas["minutos"];
        }
        return $horas;
    }

    function horas_extra($conn, $id){
        /* 
        Funcion que recibe una conexion y un id de registro horario para habilitar o deshabilitar las horas extra del mismo.
        */
        $consulta = "SELECT Horas_extra FROM Horarios where id_horario=".$id;
        $resultado = mysqli_query($conn, $consulta);
        if($resultado){
            $horas_extra = mysqli_fetch_assoc($resultado)["Horas_extra"];
            if($horas_extra == 0){
                $horas_extra = "true";
            }
            else{
                $horas_extra = "false";
            }
        }

        $modificar = 'UPDATE Horarios SET Horas_extra='.$horas_extra.' WHERE id_horario='.$id;
        if(mysqli_query($conn, $modificar)){
            header("Location: index.php");
        }
    }

    function genera_fila($fila, $horas){
        /* Funcion que genera una fila para la tabla con la entrada, la salida y el total de horas transcurridos */
        if(!$fila["Salida"]){
            $salida= "-";
        }
        else{
            $salida= $fila["Salida"];
        }
        $horas= formatear_horas($horas);
        $estilo = "";
        if($fila["Horas_extra"]){ //Si es un horario con hs extra autorizada se cambia el estilo para mostrar su total
            $estilo = 'style="color:white"';
        }
        $aux= "<tr>
                    <td>".$fila["Fecha"]."</td>
                    <td>".$fila["Entrada"]."</td>
                    <td>".$salida."</td>
                    <td ".$estilo.">".$horas["horas"].":".$horas["minutos"]."</td>
                </tr>";
        return $aux;
    }

    function genera_fila_modificable($fila){
        if(!$fila["Salida"]){
            $salida= "-";
        }
        else{
            $salida= $fila["Salida"];
        }
        if($fila["Horas_extra"]){
            $estado = "Deshabilitar";
        }
        else{
            $estado = "Habilitar";
        }
        $aux= "<tr>
                    <td>".$fila["Fecha"]."</td>
                    <td>".$fila["Entrada"]."</td>
                    <td>".$salida."</td>
                    <td><a href=\"modificar.php?id=".$fila["id_horario"]."\">🖋️</a></td>
                    <td><a href=\"horas_extra.php?id=".$fila["id_horario"]."\">".$estado."</a></td>
                </tr>
        ";
        return $aux;
    }

    function inyeccion_sql($str){
        /* Funcion que comorueba los caracteres un string para evitar inyecciones sql. Devuelve True en caso de caracter sospechoso. */
        $str = str_split($str);
        foreach($str as $caracter){
            if ($caracter == "'" || $caracter == "=" || $caracter == '"' || $caracter == "`" || $caracter == "<" || $caracter == ">"){
                return True;
            }
        }
        return False;
    }
    
    function consultar_empleado($conn, $id){
        /* 
        Funcion que recibe una conexion mysqli y un id para devolver los datos de dicho id.
        */
        $consulta = "SELECT * FROM Empleados e INNER JOIN Jornadas j ON e.id_jornada=j.id_jornada WHERE id_empleado = ".$id;
        $datos = mysqli_query($conn, $consulta);
        $datos = mysqli_fetch_assoc($datos);
        $datos["conn"] = $conn;
        return $datos;
    }

    function consultar_usuario($empleado){
        /* 
        Funcion que recibe una conexion mysqli y un array de empleado para devolver los datos de dicho empleado.
        */
        $conn = $empleado["conn"];
        $consulta = "SELECT * FROM Usuarios WHERE id_usuario = ".$empleado["id_empleado"];
        $datos = mysqli_query($conn, $consulta);
        $datos = mysqli_fetch_assoc($datos);
        return $datos;
    }

    function consultar_horarios($empleado, $fecha1, $fecha2){
        /* 
        Funcion que recibe una conexion mysqli, un array de empleado y dos fechas para devolver los horarios de dicho empleado en el rango solicitado.
        */
        $conn = $empleado["conn"];
        $consulta = "SELECT * FROM Horarios WHERE id_empleado = ".$empleado["id_empleado"]." AND (Fecha BETWEEN \"".$fecha1."\" AND \"".$fecha2."\") ORDER BY Fecha";
        $datos = mysqli_query($conn, $consulta);
        return $datos;
    }
?>