<?php
require_once "PDO.php";

session_start();

$title = false;
$input = '';
if (isset($_SESSION['conexion']['user'])) {
    $title = true;
    $host = $_SESSION['conexion']['host'];
    $usuario = $_SESSION['conexion']['user'];
    $password = $_SESSION['conexion']['pass'];
    $con = conectar($host, $usuario, $password);
    $DB = mostrarDB($con);
}
if (isset($_POST['enviar'])) {
    if (empty($_POST['localhost']) || empty($_POST['usuario']) || empty($_POST['password'])) {
        $error = "<li>Por favor introduzca datos validos</li><br>";
    } else {
        $host = filter_var(strtolower(filter_input(INPUT_POST, 'localhost')), FILTER_SANITIZE_STRING);
        $usuario = strtolower(filter_input(INPUT_POST, 'usuario'));
        //$password = hash('sha512', filter_input(INPUT_POST, 'password'));
        $password = strtolower(filter_input(INPUT_POST, 'password'));
        $title = true;
        $_SESSION['conexion']['host'] = $host;
        $_SESSION['conexion']['user'] = $usuario;
        $_SESSION['conexion']['pass'] = $password;
        $con = conectar($host, $usuario, $password);
        $DB = mostrarDB($con);

        $input = 'disabled';
    }
}
$con = null;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>GESTOR DATABASES</title>
        <link rel="stylesheet" href="estilos.css">
        <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,500" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="contenedor">
            <?php if ($title): ?>
                <h1 class="titulo">GESTOR DE BASES DE DATOS</h1>
            <?php else: ?>
                <h2 class="titulo">Inicia Sesión</h2>
            <?php endif; ?>
            <hr class="border">
            <form action="#" method="POST" class="formulario">
                <div class="form-group">
                    <input type="text" name="localhost" id="localhost" class="usuario" value="<?php echo $host ?>" placeholder="LocalHost">


                </div>

                <div class="form-group">
                    <i class="icono izquierda fa fa-user"></i><input type="text" name="usuario" value="<?php echo $usuario ?>" class="usuario" placeholder="Usuario">


                </div>
                <div class="form-group">
                    <i class="icono izquierda fa fa-lock"></i><input type="password" name="password" value="<?php echo $password ?>" class="usuario" placeholder="Contraseña">


                </div>
                <div class="form-group">
                    <input type="submit" value="Login" name="enviar" class="submit-btn" <?php echo $input ?> >


                </div>
                <?php if (!empty($error)): ?>
                    <div class="error">
                        <ul>
                            <?php echo $error ?>
                        </ul>

                    </div>
                <?php endif; ?>
            </form>
            <?php if (!empty($DB)): ?>
                <h1 class="titulo">SELECCIONA DB</h1>
                <hr class="border">
                <form action="tabla.php" method="post" class="formulario" name="form">
                    <ul>
                        <?php
                        foreach ($DB as $database) {
                            foreach ($database as $bd => $data) {
                                echo "<li><input type='radio' name='database' value=$data><span>" . strtoupper($data) . "</span></li>";
                            }
                        }
                        ?>
                    </ul>
                    <input type="submit" name="db_selected" value="Gestionar" class="submit-btn" >
                    <input type="submit" name="db_selected" value="Cancelar" class="cancel-btn">
                    <?php if (empty($error2)): ?>
                        <div class="error">
                            <ul>
                                <?php echo $error ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </body>
</html>
