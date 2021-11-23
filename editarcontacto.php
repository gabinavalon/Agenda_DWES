<?php
require 'funciones.php';
session_start();
require 'Session.php';
if (!Session::esta_iniciada()) {
    header('Location: index.php');
    die();
}

// Iniciamos las variables
$nombre = '';
$apellidos = '';
$poblacion = '';
$provincia = '';
$email = '';
$fecha_nac = '';
$fotoerr = '';

$correcto = true;

//Recogemos el id del CONTACTO y le quitamos todos los caracteres que no sean números
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$conn = new mysqli('localhost:3310', 'root', '', 'prueba');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (!$result = $conn->query("SELECT * FROM contactos WHERE id = $id")) {
    die("Error en la SQL: " . $conn->error);
}

$filas = $result->fetch_all(MYSQLI_ASSOC);

// Almacenamos los numeros de los contactos del usuario en $numerostele

if (!$resultnum = $conn->query("SELECT * FROM telefonos WHERE id_contacto = $id")) {
    die("Error en la SQL: numeros" . $conn->error);
}

$numerostele = $resultnum->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = limpiar_datos($_POST['nombre']);
    $apellidos = limpiar_datos($_POST['apellidos']);
    $poblacion = limpiar_datos($_POST['poblacion']);
    $provincia = limpiar_datos($_POST['provincia']);
    $email = limpiar_datos($_POST['email']);
    $fecha_nac = limpiar_datos($_POST['fecha_nac']);

    if (empty($nombre)) {
        $correcto = false;
    }

    if (empty($email)) {
        $correcto = false;
    }

    if ($_FILES['foto']['size']>0) {
        if (
            $_FILES['foto']['type'] != 'image/png' &&
            $_FILES['foto']['type'] != 'image/jpeg' &&
            $_FILES['foto']['type'] != 'image/gif'
        ) {
            $fotoerr = "Debe seleccionar una imagen con extensión png, jpg o gif";
            $correcto = false;
        }
    }

    if ($correcto) {

        //Si el usuario a seleccionado que quiere cambiar la foto se ejecutará este código
        if ($_FILES['foto']['size']>0) {

            //Ponemos un nombre aleatorio manteniendo la extensión original
            $array = explode('.', $_FILES['foto']['name']);
            $extension = limpiar_datos($array[count($array) - 1]);
            do {
                $nombre_archivo = md5(time() + rand()) . '.' . $extension;
            } while (file_exists("imagenes/$nombre_archivo"));  //por si existiera ya un archivo con ese nombre
            move_uploaded_file($_FILES['foto']['tmp_name'], "imagenes/$nombre_archivo");


            //Redimensionamos la foto
            if ($extension == "jpg" || $extension == "jpeg") {
                $imagen = imagecreatefromjpeg('imagenes/' . $nombre_archivo);
                $imagen_redimensionada = imagescale($imagen, '200');
                imagejpeg($imagen_redimensionada, 'imagenes/' . $nombre_archivo);
            } elseif ($extension == "png") {
                $imagen = imagecreatefrompng('imagenes/' . $nombre_archivo);
                $imagen_redimensionada = imagescale($imagen, '200');
                imagepng($imagen_redimensionada, 'imagenes/' . $nombre_archivo);
            } elseif ($extension == "gif") {
                $imagen = imagecreatefromgif('imagenes/' . $nombre_archivo);
                $imagen_redimensionada = imagescale($imagen, '200');
                imagegif($imagen_redimensionada, 'imagenes/' . $nombre_archivo);
            }

            // Aquí hacemos el insert

            if (!$stmt = $conn->prepare("UPDATE contactos SET nombre = ?,apellidos = ?, poblacion = ?,provincia = ?, email = ?, fecha_nac = ?, foto = ? WHERE id = $id")) {
                die("Error al preparar la consulta" . $conn->error);
            }
            if (!$stmt->bind_param('sssssss', $nombre, $apellidos, $poblacion, $provincia, $email, $fecha_nac, $nombre_archivo)) {
                die("Error al hacer el bind_param: " . $stmt->error);
            }
            if (!$stmt->execute()) {
                die("Error al hacer el execute: " . $stmt->error);
            }

            //Redirigimos al login
            header("Location: editarcontacto.php?id=<?=$id");
        } else {

            // Si no ha subido una imagen se ejecutará este código

            if (!$stmt = $conn->prepare("UPDATE contactos SET nombre = ?,apellidos = ?, poblacion = ?,provincia = ?, email = ?, fecha_nac = ? WHERE id = $id")) {
                die("Error al preparar la consulta" . $conn->error);
            }
            if (!$stmt->bind_param('ssssss', $nombre, $apellidos, $poblacion, $provincia, $email, $fecha_nac)) {
                die("Error al hacer el bind_param: " . $stmt->error);
            }
            if (!$stmt->execute()) {
                die("Error al hacer el execute: " . $stmt->error);
            }

            //Redirigimos al login
            header("Location: editarcontacto.php?id=<?=$id");
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title></title>
    <style type="text/css">
        .general {
            display: flex;
            justify-content: center;
        }

        .usuario {
            border: 1px solid black;
            margin: auto;
            padding: 10px;
            margin: 10px;
            width: 400px;
            display: inline-block;
            align-items: center;
            background-color: whitesmoke;

        }

        header {
            background-color: buttonhighlight;
            width: 80%;
            border: 1px solid black;
            padding: 5px;
            margin: 2px auto;
            box-sizing: border-box;
            margin: 20px auto;
        }
    </style>
    <script src="js/funciones.js" type="text/javascript"></script>

</head>

<body>

    <header>
        <h3>Usuario conectado: <?= Session::obtener()['email'] ?></h3>
        <a href="logout.php">Cerrar sesión</a>
        <h4><a href="contactos.php">Volver a mis contactos</a></h4>
    </header>
    <div class="general">
        <div class="usuario">
            <form action="" method="post" enctype="multipart/form-data">

                <div class="cambiarfoto">
                    <input type="file" name="foto" accept="image/gif,image/jpeg,image/png" id="subidafoto"><?= $fotoerr ?><br>
                    <br>
                </div>

                <?php foreach ($filas as $fila) : ?>


                    <img src="imagenes/<?= $fila['foto'] ?>" alt=""> <br>

                    <label for="nombre"><b>* Nombre: </b></label><br>
                    <input name="nombre" value="<?= $fila['nombre'] ?>"></input><br><br>

                    <label for="apellidos"><b>Apellidos: </b></label><br>
                    <input name="apellidos" value="<?= $fila['apellidos'] ?>"></input><br><br>

                    <label for="poblacion"><b>Población: </b></label><br>
                    <input name="poblacion" value="<?= $fila['poblacion'] ?>"></input><br><br>

                    <label for="provincia"><b>Provincia: </b></label><br>
                    <input name="provincia" value="<?= $fila['provincia'] ?>"></input><br><br>

                    <label for="email"><b>* E-mail: </b></label><br>
                    <input name="email" value="<?= $fila['email'] ?>"></input><br><br>

                    <label for="fecha_nach"><b>Fecha Nacimiento: </b></label><br>
                    <input type="date" name="fecha_nac" value="<?= $fila['fecha_nac'] ?>"></input><br><br>

                    <input type="submit" value="Guardar">


                <?php endforeach; ?>

            </form>

        </div>

        <div class="usuario">
            <a href="añadirnumero.php?id=<?= $id ?>">Añadir un nuevo telefono</a>
            <?php foreach ($numerostele as $telefono) : ?>
                <?php if ($telefono['id_contacto'] == $fila['id']) : ?>
                    <p><b><?= $telefono['tipo'] ?></b> <?= $telefono['numero'] ?>
                        <a href="borrartelefono.php?id=<?= $telefono['id'] ?>&id_contacto=<?= $id ?>">Borrar</a> </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>