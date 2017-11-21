
<?php
require_once 'PDO.php';
session_start();
$editar = false;
$con = conectar($_SESSION['conexion']['host'], $_SESSION['conexion']['user'], $_SESSION['conexion']['pass']);
$db = $_SESSION['conexion']['database'];
$sentencia2 = "USE $db";
$cambiaDb = accede($con, $sentencia2, NULL);
if (isset($_SESSION['conexion']['fila_editar'])) {

    $editar = true;
    $fila = $_SESSION['conexion']['fila_editar'];


    $campos = $_SESSION['conexion']['campos'];
} else if (isset($_SESSION['conexion']['fila_borrar'])) {


    $fila = $_SESSION['conexion']['fila_borrar'];
    var_dump($fila);
    $sentencia = make_delete($fila, $_SESSION['conexion']['tabla']);
    $fila = to_index($fila);

    update($sentencia, $con, $fila);
    header("Location:gestion.php");
} else {
    session_destroy();
    unset($_SESSION['conexion']);
    header("Location:index.php");
}
if (isset($_POST['edit'])) {
    $sentencia = "UPDATE $tabla SET ";
    $fila_edit = $_POST['cambia'];
    $sentencia = make_update($_SESSION['conexion']['tabla'], $fila_edit, $fila);
    $valores = combinaValores($fila_edit, $fila);
    var_dump($valores);
    update($sentencia, $con, $valores);
    header("Location:gestion.php");
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Actions</title>
        <link rel="stylesheet" href="estilos.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <h1 class="titulo"><?php echo strtoupper($_SESSION['conexion']['tabla']) ?> <form action="gestion.php" class="form"><input type="submit" class="cancel-btn" value="Volver"></form></h1>

            <hr class="border">
            <?php if ($editar): ?>
                <form action="#" method="POST">
                    <table>
                        <?php
                        echo "<tr>";
                        foreach ($campos as $campo) {
                            echo "<td>$campo</td>";
                        }
                        echo "</tr>";
                        ?>
                        <tr>

                            <?php foreach ($fila as $campos => $valor): ?>
                                <td><textarea name='cambia[<?php echo $campos ?>]' style="resize: none"><?php echo $valor ?></textarea></td>
                            <?php endforeach; ?>

                        </tr>

                    </table>
                    <input type="submit" name="edit" value="Editar" class="submi-btn">
                </form>
            <?php endif; ?>
        </div>
    </body>
</html>

