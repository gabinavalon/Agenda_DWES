<?php

require 'funciones.php';
require 'MensajeFlash.php';

session_start();
require 'Session.php';
if (!Session::esta_iniciada()) {
    header('Location: index.php');
    die();
}

//Recogemos el id y le quitamos todos los caracteres que no sean números
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$numero = '';
$tipo = '';

$correcto = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $numero = limpiar_datos($_POST['numero']);
    $tipo = limpiar_datos($_POST['tipotelefono']);

    if (empty($numero)) {
        $correcto = false;
        MensajeFlash::anadir_mensaje("Debes añadir un numero");
    }

    if ($correcto) {

        $conn = new mysqli('localhost:3310', 'root', '', 'prueba');
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        if (!$stmt = $conn->prepare("INSERT INTO  telefonos (numero, tipo, id_contacto) VALUES (?,?,?)")) {
            die("Error al preparar la consulta" . $conn->error);
        }
        if (!$stmt->bind_param('ssi', $numero, $tipo, $id)) {
            die("Error al hacer el bind_param: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            die("Error al hacer el execute: " . $stmt->error);
        }
        header("Location: editarcontacto.php?id=$id");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
    <style type="text/css">

    </style>

</head>

<body>

    <form action="" method="POST">
        <div id="telefonosformulario">
            <div id="telefonoformulario">
                <label for="Numero">Numero: </label><input id="numeronuevo" type="text" name="numero" placeholder="Numero..." value="">
                <label for="tipotelefono">Tipo de telefono</label>
                <select id="tipotelefono" name="tipotelefono">
                    <option value="Movil">Movil</option>
                    <option value="Trabajo">Trabajo</option>
                    <option value="Casa">Casa</option>
                </select> <br><br>
            </div>
        </div>
        <input type="submit" value="Guardar">
        <h3><?=MensajeFlash::imprimir_mensajes()?></h3>
    </form>

</body>

</html>