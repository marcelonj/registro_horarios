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

$consulta= "SELECT * FROM Horarios WHERE id_horario=".$_GET["id"];
$resultado= mysqli_query($conn, $consulta);
$resultado= mysqli_fetch_assoc($resultado);
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Modificar horario</title>
</head>
<body class="bg-principal">
    <form class="flex-columna" action="modificar_post.php" method="post">
        <label for="fecha">Fecha</label>
        <input type="date" name="fecha" id="fecha"
        <?php
            echo 'value="'.$resultado["Fecha"].'"';
        ?>
        >
        <label for="entrada">Entrada</label>
        <input type="time" name="entrada" id="entrada"
        <?php
            echo 'value="'.$resultado["Entrada"].'"';
        ?>>
        <label for="salida">Salida</label>
        <input type="time" name="salida" id="salida"
        <?php
            echo 'value="'.$resultado["Salida"].'"';
        ?>
        >
        <input class="oculto" type="text" name="id" id="id"
        <?php
            echo 'value="'.$resultado["id_horario"].'"';
        ?>
        >
        <button type="submit" class="boton-azul">Modificar</button>
    </form>
</body>
</html>