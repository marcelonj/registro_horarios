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
elseif ($_SESSION["tipo"]==3) {
    header("Location: super_admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Laboral NOA</title>
</head>
<body>
    <main class="bg-principal">
    <?php
    echo '<h1>Bienvenido/a '.$_SESSION["nombre"].'!</h1>
        <div class="empleado flex-columna p-0">
            <div class="foto">
                <img src="img/'.$_SESSION["id"].'.jpg" alt="foto">
            </div>
        </div>';
            
    require("conexion_db.php");
    $date= getdate();
    $fecha= (string)$date["year"]."-". (string)$date["mon"]."-". (string)$date["mday"];
    
    $consulta= "SELECT * FROM Horarios WHERE Fecha=\"$fecha\" AND id_empleado=".$_SESSION["id"];
    $resultado= mysqli_query($conn, $consulta);
    if(mysqli_num_rows($resultado)>0){
        echo "<p class=\"texto-centrado\">Ya registró entrada, marque su salida</p>";
    }
    else{
        echo "<p class=\"texto-centrado\">Todavía no se registró entrada</p>";
    }
    echo '</div>
        <div class="opciones">
            <form class="flex-columna p-0" action="post.php" method="post">
                <input type="text" name="fecha" id="fecha" class="oculto">
                <input type="text" name="hora" id="hora" class="oculto">
                <input type="text" name="long" id="long" class="oculto">
                <input type="text" name="lat" id="lat" class="oculto">';
                //<button type="submit">Marcar E/S</button>
                if(mysqli_num_rows($resultado)>0){
                    echo '<button class="boton-verde" type="submit" id="submit">Marcar SALIDA</button>
                    <a class="boton-azul"href="consultar_mes.php">Mostrar mes</a>
                    <a class="boton-azul"href="logout.php">Cerrar sesion</a>';
                    
                }
                else{
                    echo '<button class="boton-verde" type="submit" id="submit">Marcar ENTRADA</button>
                    <a class="boton-azul"href="consultar_mes.php">Mostrar mes</a>
                    <a class="boton-azul"href="logout.php">Cerrar sesion</a>
                    <a href="modificar_pass.php">Modificar contraseña</a>';
                };
    echo '</form>
        </div>'
?>
    </main>
</body>
<script src="scripts/script.js"></script>
</html>