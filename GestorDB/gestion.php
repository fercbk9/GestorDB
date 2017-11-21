<?php
require_once 'PDO.php';
session_start();
if (isset($_POST['gestion'])) {
    switch ($_POST['gestion']) {
        case 'Editar':
            $fila_editar = $_POST['fila'];
            $_SESSION['conexion']['fila_editar'] = $fila_editar;
            header("Location:actions.php");
            break;
        case 'Borrar':

            //$borrar = "DELETE FROM $tabla WHERE $cod = :cod";
            if (isset($_SESSION['conexion']['fila_editar'])) {
                unset($_SESSION['conexion']['fila_editar']);
            }
            $fila_borrar = $_POST['fila'];
            $_SESSION['conexion']['fila_borrar'] = $fila_borrar;
            header("Location:actions.php");


            //insertar($con, $borrar, $valores);

            break;
        case 'Añadir':
            $add = true;

            break;
        case 'Cerrar':
            session_destroy();
            unset($_SESSION['conexion']);

            break;
    }
}
if (isset($_SESSION['conexion'])) {

    if (isset($_POST['enviar'])) {
        $tabla = filter_input(INPUT_POST, 'enviar');
        $_SESSION['conexion']['tabla'] = $tabla;
        if ($_POST['enviar'] === 'Volver') {

            header("Location:index.php");
        }
    }
    $db = $_SESSION['conexion']['database'];
    $sentencia2 = "USE $db";


    $tabla = $_SESSION['conexion']['tabla'];
    $sentencia = "SELECT * FROM $tabla";
    $con = conectar($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);

    $cambiaDb = accede($con, $sentencia2, NULL);
    $datos = select($sentencia, NULL, $con);
    $indices = mostrarNomColum($con, $tabla);
    $_SESSION['conexion']['campos'] = $indices;
} else {

    header("Location:index.php");
}

if (isset($_POST['añadir'])) {


    $añadir = $_POST['add'];
    insertarDatos($con, $tabla, $añadir);
    header("Location:gestion.php");
}
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
        <div class="container">

            <h1 class="titulo"><?php echo strtoupper($tabla) ?> <form action="tabla.php" class="form"><input type="submit" class="cancel-btn" value="Volver"></form></h1>

            <hr class="border">

            <table>

                <?php
                echo "<tr>";
                foreach ($indices as $indice) {
                    echo "<td>$indice</td>";
                }
                echo "</tr>";
                ?>
                <?php if ($add): ?>
                    <tr>
                    <form action="#" method="post">
                        <?php foreach ($indices as $indice): ?>
                            <td><textarea name="add[<?php echo $indice ?>]" style="resize: none"></textarea></td>
                        <?php endforeach; ?>
                        <td><input type="submit" value="Add" name="añadir" class="editar"></td>
                    </form>
                    </tr>
                <?php endif; ?>
                <?php
                foreach ($datos as $dato) {
                    echo "<tr><form action='#' method='POST' >";

                    foreach ($dato as $nombre => $valor) {

                        echo "<td> <textarea name='$nombre' disabled style='resize: none;'>$valor</textarea>\n";
                        echo "<input type='hidden' name='fila[" . $nombre . "]'  value=$valor></td>";
                    }

                    echo "<td><input type='submit' name='gestion' value='Editar' class='editar'><input type='submit' name='gestion' value='Borrar' class='borrar'></form></td></tr>\n";
                }
                ?>




            </table>
            <form action="#"method="POST" class="formulario">


                <input type="submit" value="Añadir" name="gestion" class="submit-btn">
                <input type="submit" value="Cerrar" name="gestion" class='cancel-btn'>



            </form>







        </div>
    </body>
</html>

