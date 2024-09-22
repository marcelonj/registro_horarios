<?php
    include("conexion_db.php");
    include("funciones.php");
    horas_extra($conn, $_GET["id"]);
?>