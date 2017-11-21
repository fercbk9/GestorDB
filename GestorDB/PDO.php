<?php

/**
 *
 * @param type $db
 * @param type $user
 * @param type $pass
 * @return \PDO
 * @description Conectamos con la BD
 */
function conectar($db, $user, $pass) {
    try {
        $con = new PDO("mysql:host=$db", $user, $pass);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo 'Conectado Correctamente';
        return $con;
    } catch (Exception $e) {
        session_destroy();

        die('<h1>Error de conexion</h1><br />' . $e->getMessage());
    }
}

/**
 *
 * @param type $sentencia
 * @param type $parametros
 * @param type $con
 * @return type
 * @description Ejecutamos una consulta de select
 */
function select($sentencia, $parametros, $con) {
    try {
        $stmt = $con->prepare($sentencia);
        $stmt->execute($parametros);
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultado[] = $fila;
        }
        //$stmt->close();
        return $resultado;
    } catch (PDOException $e) {
        die('Error de ' . $e->getMessage());
    }
}

function make_insert($tabla, $valores) {
    $sentencia = "insert into  $tabla values(";
    foreach ($valores as $v) {
        $sentencia .= "?,";
    }
//Quitamos el último carácter que es la última coma
    $sentencia = substr($sentencia, $start, strlen($sentencia) - 1);
    $sentencia .= ")";

    return $sentencia;
}

function make_update($tabla, $valores_editar, $valores_original) {
    $sentencia = "UPDATE $tabla SET ";
    foreach ($valores_editar as $valor => $d) {
        $sentencia .= "$valor = ?,";
    }
    $sentencia = substr($sentencia, $start, strlen($sentencia) - 1);
    $sentencia .= " WHERE";
    foreach ($valores_original as $v => $data) {
        $sentencia .= " ($v = ?) AND";
    }
    $sentencia = substr($sentencia, $start, strlen($sentencia) - 3);
    var_dump($sentencia);
    return $sentencia;
}

function make_delete($valores, $tabla) {
    $sentencia = "DELETE FROM $tabla WHERE";
    foreach ($valores as $v => $d) {
        $sentencia .= " $v = ? AND";
    }
    $sentencia = substr($sentencia, $start, strlen($sentencia) - 3);
    var_dump($sentencia);
    return $sentencia;
}

function update($sentencia, $con, $valores) {
    try {

        $stmt = $con->prepare($sentencia);

        $stmt->execute($valores);

        if ($stmt !== -1) {
            echo 'Se han actualizado ' . $stmt->rowCount() . ' filas.';
        } else {
            echo 'No se ha actualizado nada';
        }
    } catch (PDOException $e) {

        die('Error de ' . $e->getMessage());
    }
}

function combinaValores($array1, $array2) {
    $array1 = to_index($array1);
    $array2 = to_index($array2);

    foreach ($array2 as $v) {
        array_push($array1, $v);
    }
    return $array1;
}

/**
 *
 * @param array $valores array asociativo
 * @return array es la versión indexada del array asociativo
 */
function to_index($valores) {
    foreach ($valores as $v => $d) {
        $index[] = $d;
    }
    var_dump($index);
    return $index;
}

/**
 *
 * @param type $con
 * @param type $sentencia
 * @param type $valores
 * @description Funcion que usamos para acceder a las DB , tablas etc.
 */
function accede($con, $sentencia, $valores) {
    try {
        //$sentencia = make_insert($tabla, $valores);
        $stmt = $con->prepare($sentencia);
        //$valores = to_index($valores);

        $stmt->execute($valores);

        if ($stmt !== -1) {
            echo 'Se han actualizado ' . $stmt->rowCount() . ' filas.';
        } else {
            echo 'No se ha actualizado nada';
        }
    } catch (PDOException $e) {

        die('Error de ' . $e->getMessage());
    }
}

/**
 * @description Insertamos los valores para cada tabla segun asignemos
 * @param type $con
 * @param type $tabla
 * @param type $valores
 */
function insertarDatos($con, $tabla, $valores) {
    try {
        $sentencia = make_insert($tabla, $valores);
        $stmt = $con->prepare($sentencia);
        $valores = to_index($valores);

        $stmt->execute($valores);

        if ($stmt !== -1) {
            echo 'Se han actualizado ' . $stmt->rowCount() . ' filas.';
        } else {
            echo 'No se ha actualizado nada';
        }
    } catch (PDOException $e) {

        die('Error de ' . $e->getMessage());
    }
}

function mostrarDB($con) {

    $sentencia = "SHOW DATABASES;";
    $valores = null;
    return select($sentencia, $valores, $con);
}

function mostrarTablas($con) {
    $sentencia = "SHOW TABLES";
    $valores = null;
    return select($sentencia, $valores, $con);
}

function mostrarNomColum($con, $table) {
    $sentencia = "SELECT * FROM $table";
    try {
        $stmt = $con->prepare($sentencia);
        $stmt->execute();
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $resultado = array_keys($fila);
        }
//$stmt->close();
        return $resultado;
    } catch (PDOException $e) {
        die('Error de ' . $e->getMessage());
    }
}
