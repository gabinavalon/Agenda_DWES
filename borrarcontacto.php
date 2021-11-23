<?php

session_start();
require 'Session.php';
if(!Session::esta_iniciada()){
    header('Location: index.php');
    die();
}

//Recogemos el id y le quitamos todos los caracteres que no sean números
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$conn = new mysqli('localhost:3310', 'root', '', 'prueba');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Almacenamos el nombre de la foto del usuario para poder borrarla de la carpeta
if (!$resultid = $conn->query("SELECT foto FROM contactos WHERE id = $id")) {
    die("Error en la colnsulta");
};
$nombrefoto = $resultid->fetch_array(MYSQLI_ASSOC);
unlink("imagenes/".$nombrefoto['foto']);

//Borramos el contacto
if (!$stmt = $conn->prepare("DELETE FROM contactos WHERE id=?")) {
    die("Error al preparar la consulta" . $conn->error);
}
if (!$stmt->bind_param('i', $id)) {
    die("Error al hacer el bind_param: " . $stmt->error);
}
if (!$stmt->execute()) {
    die("Error al hacer el execute: " . $stmt->error);
}
header('Location: contactos.php');