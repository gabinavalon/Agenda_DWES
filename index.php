<?php
session_start();
require 'funciones.php';
require 'Session.php';
$error = '';
$email = '';
$password = '';

if (Session::existe_cookie()) {
    if ($usuario = Session::obtener_usuario_cookie()) {
        Session::iniciar($usuario);
        header('Location: contactos.php');
        die();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = limpiar_datos($_POST['email']);
    $password = limpiar_datos($_POST['password']);

    $conn = new mysqli('localhost', 'root', '', 'prueba');
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    //Obtener una fila de una tabla
    $sql = "SELECT * FROM usuarios WHERE email='$email'";
    if (!$result = $conn->query($sql)) {
        die("Error en la SQL: " . $conn->error);
    }
    $usuario = $result->fetch_assoc();
    if (password_verify($password, $usuario['password'])) {
        if (isset($_POST['recordar'])) {
            //Si ha marcado recordar creamos la cookie
            Session::crear_cookie($usuario['uid']);
        }
        Session::iniciar($usuario);
        header('Location: contactos.php');
        die();
    } else {
        $error = "Usuario o password incorrectos";
    }
}
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h3 style="color:red"><?= $error ?></h3>
        <form action="" method="post">
            <input type="text" name="email" placeholder="email..." value="<?= $email ?>"><br><br>
            <input type="password" name="password" placeholder="password..." value="<?= $password ?>"><br><br>
            <input type="checkbox" name="recordar">Recordar usuario en este equipo<br><br>
            <input type="submit" value="Login"><br><br>
            
        </form>
        <a href="registrar.php">Registrar usuario</a>
    </body>
</html>