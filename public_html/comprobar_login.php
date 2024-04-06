<?php
require("conexion_db.php");

//Declaracion de variables
$usuario= $_POST["usuario"];
$pass= $_POST["password"];
$consulta= "SELECT * FROM Usuarios WHERE Usuario=\"$usuario\""; //Se recupera la información del usuario


if($respuesta= mysqli_query($conn, $consulta)){
    mysqli_data_seek($respuesta, 0);

    $fila= mysqli_fetch_assoc($respuesta);
    
    $hash= $fila["Hash"]; //Se establece el hash almacenado para el usuario con el que se ingreso
    
    if(!password_verify($pass, $hash)){  //Comprobacion de hashes
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="css/style.css">
            <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
            <title>Login</title>
        </head>
        <body class="bg-principal">
            <div class="flex-columna">
                <label id="iniciar-sesion">Usuario o contraseña incorrectos</label>
                <a class="boton-azul" href="login.php">Regresar</a>
            </div>
        </body>
        </html>
        ';
        exit();
    }
    else{
        session_start();
        $_SESSION["id"]= $fila["id_empleado"];
        $_SESSION["tipo"]= $fila["Tipo"];
        $consulta= "SELECT * FROM Empleados WHERE id_empleado=\"".$_SESSION["id"]."\"";
        $respuesta= mysqli_query($conn, $consulta);
        $fila= mysqli_fetch_assoc($respuesta);
        $_SESSION["nombre"]= $fila["Nombre y apellido"];
        header("Location: index.php");
    }

}
else{
    echo "Ocurrio un problema";
}

?>