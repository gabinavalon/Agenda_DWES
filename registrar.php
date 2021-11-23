<?php
require 'funciones.php';
require 'Session.php';
require 'MensajeFlash.php';

$email = '';
$password = '';
$password2 = '';
$fecha_registro = '';
$error = '';
$error_usuario = '';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = limpiar_datos($_POST['email']);
    $password = limpiar_datos($_POST['password']);
    $password2 = limpiar_datos($_POST['password2']);
    $correcto = true;

    if (empty($password) || empty($password2) || empty($email)) {
        $correcto = false;
        MensajeFlash::anadir_mensaje('Rellena todos los datos');
    }

    if (strlen($password)<3) {
        $correcto = false;
        MensajeFlash::anadir_mensaje('La contraseña debe tener al menos 3 caracteres');
    }

    if ($password != $password2) {
        $correcto = false;
        MensajeFlash::anadir_mensaje('Las contraseñas no coinciden. Deben ser iguales');
    }

    // Conectamos con la base de datos

    $conn = new mysqli('localhost', 'root', '', 'prueba');

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Comprobamos que el usuario exista existe

    $sql = "SELECT email FROM usuarios WHERE email='$email'";

    if (!$result = $conn->query($sql)) {
        die("Error en la SQL: " . $conn->error);
    }

    $usuario = $result->fetch_assoc();

    if (!$usuario['email']) {
        
    } else {
        $correcto = false;
        MensajeFlash::anadir_mensaje("Este usuario ya existe");
    }

    if ($correcto) {

        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $uid = sha1(time()+rand()); 

        if (!$stmt = $conn->prepare("INSERT INTO usuarios (email, password,uid) VALUES (?,?,'$uid')")) {
            die("Error al preparar la consulta" . $conn->error);
        }
        if (!$stmt->bind_param('ss', $email, $password_hash)) {
            die("Error al hacer el bind_param: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            die("Error al hacer el execute: " . $stmt->error);
        }
        //Redirigimos al login
        header('Location: index.php');
        die();
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="email" placeholder="email..." value="<?= $email ?>"><br><br>
            <input type="password" name="password" placeholder="password..." value="<?= $password ?>"><br><br>
            <input type="password" name="password2" placeholder="repite el password..." value="<?= $password2 ?>"><br>
            <h3><?= MensajeFlash::imprimir_mensajes() ?></h3>
            <input type="submit" value="Registrar"><br><br>
            <a href="index.php">Logearse</a>
        </form>
    </body>
</html>