<?php
require 'funciones.php';
require 'MensajeFlash.php';
session_start();
require 'Session.php';
if (!Session::esta_iniciada()) {
    header('Location: index.php');
    die();
}

// Iniciamos la variables que debemos asignar a los valores de un nuevo contacto
$nombre = '';
$apellidos = '';
$poblacion = '';
$provincia = '';
$email = '';
$fecha_nac = '';

$correcto = true;
$emptynombreocorreo = '';

$nuevonumero = '';
$tiponumero = '';

// Almacenamos la id del usuario que utilizaremos más tarde
$id_usuario = Session::obtener()['id'];

// Conexion con la base de datos
$conn = new mysqli('localhost', 'root', '', 'prueba');
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Almacenamos los contactos en filas
if (!$result = $conn->query("SELECT * FROM contactos WHERE id_usuario = $id_usuario")) {
    die("Error en la SQL: " . $conn->error);
}

$filas = $result->fetch_all(MYSQLI_ASSOC);

// Almacenamos los numeros de los contactos del usuario en $numerostele
if (!$resultnum = $conn->query("SELECT telefonos.id, telefonos.numero, telefonos.tipo, telefonos.id_contacto FROM telefonos, contactos
WHERE telefonos.id_contacto = contactos.id
and telefonos.id_contacto IN ( SELECT contactos.id 
                                from contactos
                                where contactos.id_usuario = $id_usuario)
GROUP BY telefonos.id")) {
    die("Error en la SQL: numeros" . $conn->error);
}

$numerostele = $resultnum->fetch_all(MYSQLI_ASSOC);

// ---------------------------- Añadimos un nuevo contacto a la lista

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = limpiar_datos($_POST['nombre']);
    $apellidos = limpiar_datos($_POST['apellidos']);
    $poblacion = limpiar_datos($_POST['poblacion']);
    $provincia = limpiar_datos($_POST['provincia']);
    $email = limpiar_datos($_POST['email']);
    $fecha_nac = limpiar_datos($_POST['fecha_nac']);


    if (empty($nombre)) {
        $correcto = false;
        MensajeFlash::anadir_mensaje('Debes añadir al menos el Nombre');
    }

    if (empty($email)) {
        $correcto = false;
        MensajeFlash::anadir_mensaje('Debes añadir al menos el Email');
    }

    if ($_FILES['foto']['size']>0) {
        if (
            $_FILES['foto']['type'] != 'image/png' &&
            $_FILES['foto']['type'] != 'image/jpeg' &&
            $_FILES['foto']['type'] != 'image/gif'
        ) {
            MensajeFlash::anadir_mensaje("Debe seleccionar una imagen con extensión png, jpg o gif");
            $correcto = false;
        }
    }

    if ($correcto) {

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

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        //Insertar el contacto en la tabla

        if (!$stmt = $conn->prepare("INSERT INTO  contactos ( nombre, apellidos, poblacion, provincia, email, fecha_nac, foto, id_usuario ) VALUES (?,?,?,?,?,?,?, $id_usuario)")) {
            die("Error al preparar la consulta" . $conn->error);
        }
        if (!$stmt->bind_param('sssssss', $nombre, $apellidos, $poblacion, $provincia, $email, $fecha_nac, $nombre_archivo)) {
            die("Error al hacer el bind_param: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            die("Error al hacer el execute: " . $stmt->error);
        }

        //Insertar sus numeros en la tabla

        foreach (array_keys($_POST['numero']) as $key) {

            $numero = $_POST['numero'][$key];
            $tipotelefono = $_POST['tipotelefono'][$key];

            // Almacenamos el id del ultimo usuario que se ha creado, que será al que le pertenecen estos numerod e telefono
            if (!$resultid = $conn->query("SELECT id FROM contactos ORDER BY id DESC LIMIT 1")) {
                die("Error en la colnsulta");
            };
            $id_ultimocontacto = $resultid->fetch_array(MYSQLI_ASSOC);


            if (!$stmt = $conn->prepare("INSERT INTO  telefonos (numero, tipo, id_contacto) VALUES (?,?,?)")) {
                die("Error al preparar la consulta" . $conn->error);
            }
            if (!$stmt->bind_param('ssi', $numero, $tipotelefono, $id_ultimocontacto['id'])) {
                die("Error al hacer el bind_param: " . $stmt->error);
            }
            if (!$stmt->execute()) {
                die("Error al hacer el execute: " . $stmt->error);
            }
        }

        //Redirigimos al login
        header("Location: contactos.php");
        die();
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
            margin: auto;
            flex-wrap: wrap;
            
        }

        .usuario {
            border: 1px solid black;
            margin: 0 auto;
            padding: 15px;
            margin: 10px ;
            width: 15%; 
            border-radius: 10px;
            justify-items: center;
            text-align: center;
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

        .añadirusuario {
            border: 1px solid black;
            padding: 5px;
            padding-left: 40px;
            margin: 20px auto;
            width: 80%;
        }

        .añadirusuario h3 {
            text-align: center;
            width: 80%;
        }

        .añadirusuario h4 {
            text-align: center;
            width: 80%;
            color: red;
        }

        .añadirusuario form {
            text-align: center;
            width: 80%;

        }
    </style>
    <script src="js/funciones.js" type="text/javascript"></script>

</head>

<body>

    <header>
        <h3>Usuario conectado: <?= Session::obtener()['email'] ?></h3>
        <a href="logout.php">Cerrar sesión</a>
    </header>

    <!-- Formulario para añadir un usuario -->

    <div class="añadirusuario">

        <h3 id="tituloform">Añade un contacto</h3>

        <h4><?= MensajeFlash::imprimir_mensajes() ?></h4>

        <form action="" method="post" enctype="multipart/form-data">

            <label for="nombre">Nombre: </label><input type="text" name="nombre" placeholder="nombre..." value="<?= $nombre ?>">

            <label for="apellidos">Apellidos: </label><input type="text" name="apellidos" placeholder="apellidos..." value="<?= $apellidos ?>">

            <label for="poblacion">Población: </label><input type="text" name="poblacion" placeholder="población..." value="<?= $poblacion ?>"><br><br>

            <label for="provincia">Provincia: </label><input type="text" name="provincia" placeholder="provincia..." value="<?= $provincia ?>">

            <label for="email">E-mail: </label><input type="text" name="email" placeholder="E-mail..." value="<?= $email ?>">

            <label for="fecha_nac">Fecha de Nacimiento: </label><input type="date" name="fecha_nac" placeholder="Fecha nacimiento..." value="<?= $fecha_nac ?>"><br><br>

            <input type="file" name="foto" accept="image/gif,image/jpeg,image/png"><br><br>

            Añadir un nuevo telefono:
            <input type='button' value=' + ' onclick='anadirNumero()'>
            <input type='button' value=' - ' onclick='quitarNumero()'>
            <br><br>

            <div id="telefonosformulario">
                <div id="telefonoformulario">
                    <label for="Numero">Numero: </label><input id="numeronuevo" type="text" name="numero[]" placeholder="Numero..." value="">
                    <label for="tipotelefono">Tipo de telefono</label>
                    <select id="tipotelefono" name="tipotelefono[]">
                        <option value="Movil">Movil</option>
                        <option value="Trabajo">Trabajo</option>
                        <option value="Casa">Casa</option>
                    </select> <br><br>
                </div>
            </div>



            <input type="submit" value="Añadir"><br><br>

        </form>
    </div>

    <!-- Mostramos todos los contactos -->
        <div class="general">

        
    <?php foreach ($filas as $fila) : ?>
        <div class="usuario">
            <h3><?= $fila['nombre'] ?> <?= $fila['apellidos'] ?></h3>
            <p><a href="editarcontacto.php?id=<?= $fila['id'] ?>">Editar</a> <a href="borrarcontacto.php?id=<?= $fila['id'] ?>">Borrar</a></p>
            <img src="imagenes/<?= $fila['foto'] ?>" height="100" id="foto_usuario">
            <p class="datos_usuario"><b>Población: </b><?= $fila['poblacion'] ?> </p>
            <p class="datos_usuario"><b>Provincia: </b><?= $fila['provincia'] ?></p>
            <p class="datos_usuario"><b>@: </b><?= $fila['email'] ?></p>
            <p class="datos_usuario"><b>Fecha de nacimiento: </b><?= $fila['fecha_nac'] ?></p>
            <?php foreach ($numerostele as $telefono) : ?>
                <?php if ($telefono['id_contacto'] == $fila['id']) : ?>
                    <p><b><?= $telefono['tipo'] ?></b> <?= $telefono['numero'] ?> </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
    </div>
</body>

</html>