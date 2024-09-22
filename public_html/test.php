<?php
    require("funciones.php");
    require("conexion_db.php");
    $hora_entrada = "08:30:00";
    $hora_salida = "08:30:00";
    $jornada_entrada = "09:15:00";
    $jornada_salida = "17:15:00";
    $pass = "OR 1=1";
    $id_horario = 57;
    //var_dump(viaje_tiempo($hora_entrada, $hora_salida));
    horas_extra($conn, $id_horario);
?>