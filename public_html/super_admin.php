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
$consulta= "SELECT * FROM Usuarios INNER JOIN Empleados ON Usuarios.id_empleado=Empleados.id_empleado";
$respuesta= mysqli_query($conn, $consulta);

function generar_option($fila){
    $aux= "<option value=\"".$fila["id_usuario"]."\">".$fila["Nombre y apellido"]."</option>";
    echo $aux;
}

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <title>Administrador</title>
</head>
<body class="bg-principal flex-columna p-0">
    <h1>Bienvenido/a '.$_SESSION["nombre"].'!</h1>
        <div class="empleado flex-columna p-0">
            <div class="foto">
                <img src="img/'.$_SESSION["id"].'.jpg" alt="foto">
            </div>
        </div>
    <h1>Mostrar horarios</h1>
    <form class="filtros flex-columna p-0" action="modificar_meses.php" method="post">
        <label for="empleado">Empleado</label>
        <select name="empleado" id="empleado">
            <option value="" selected>Empleado</option>';
            
            while($fila= mysqli_fetch_assoc($respuesta)){
                if($fila["Tipo"]==1){
                    echo generar_option($fila);
                }
            }

        echo '</select>
        <label for="mes">Mes</label>
        <select name="mes" id="mes">
            <option value="" selected>Mes</option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
        </select>
        <button class="boton-azul m-0" type="submit">Modificar horarios</button>
        <a class="boton-azul m-0"href="logout.php">Cerrar sesion</a>
</form>
</body>
</html>';

?>