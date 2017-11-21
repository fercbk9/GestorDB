<?php
require_once 'PDO.php';
session_start();

if (isset($_SESSION['conexion'])) {
    if ($_POST['db_selected']) {


        switch ($_POST['db_selected']) {

            case 'Gestionar':
                $db = filter_var(strtolower(filter_input(INPUT_POST, 'database')), FILTER_SANITIZE_STRING);
                if (!empty($db)) {
                    $_SESSION['conexion']['database'] = $db;

                    $sentencia = "USE $db";
                    $con = conectar($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
                    $cambiaDb = accede($con, $sentencia, NULL);
                    $tablas = mostrarTablas($con);
                } else {

                    $error2 = "<li>Elija una base de datos</li>";
                }

                break;
            case 'Cancelar':
                session_destroy();
                unset($_SESSION['conexion']);
                header("Location:index.php");
                break;
        }
    } else {
        $db = $_SESSION['conexion']['database'];

        $sentencia = "USE $db";
        $con = conectar($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
        $cambiaDb = accede($con, $sentencia, NULL);
        $tablas = mostrarTablas($con);
    }
} else {

    header("Location:index.php");
}
if (isset($_POST['volver'])) {
    header("Location:index.php");
}


$con = null;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Tabla</title>
        <link rel="stylesheet" href="estilos.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="contenedor">

            <h1 class="titulo">ELIGE TABLA</h1>

            <hr class="border">
            <form action="gestion.php" class="formulario" method="post">
<?php
foreach ($tablas as $tabla) {
    foreach ($tabla as $key => $value) {
        echo "<input type='submit' name='enviar' class='submit-btn' value=$value> ";
    }
}
?>
                <input type="submit" name="enviar" value="Volver" class="cancel-btn">
            </form>


        </div>
    </body>
</html>

