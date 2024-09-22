<?php
    require("funciones.php");
    $hora_entrada = "08:30:00";
    $hora_salida = "08:30:00";
    $jornada_entrada = "09:15:00";
    $jornada_salida = "17:15:00";
    var_dump(viaje_tiempo($hora_entrada, $hora_salida))
?>