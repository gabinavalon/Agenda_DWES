<?php

class Session {
    
    public static function iniciar($usuario) {
        $_SESSION['uid_app_usuario'] = $usuario;
    }
    
    public static function obtener(){
        if(self::esta_iniciada())
        {
            return $_SESSION['uid_app_usuario'];
        }
        else
        {
            return false;
        }
    }

    public static function cerrar() {
        unset($_SESSION['uid_app_usuario']);
    }

    public static function esta_iniciada(): bool {
        return isset($_SESSION['uid_app_usuario']);
    }

    public static function existe_cookie(): bool {
        return isset($_COOKIE['uid']);
    }

    public static function crear_cookie($uid) {
        setcookie('uid', $uid, time() + 30 * 24 * 60 * 60, '/');
    }

    public static function borrar_cookie() {
        setcookie('uid', '', time() - 5, '/');
    }

    public static function obtener_cookie() {
        if (self::existe_cookie()) {
            return filter_var($_COOKIE['uid'], FILTER_SANITIZE_SPECIAL_CHARS);
        } else {
            false;
        }
    }

    public static function obtener_usuario_cookie() {
        $cookie = self::obtener_cookie();
        $conn = new mysqli('localhost', 'root', '', 'prueba');
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }
        //Obtener una fila de una tabla
        $sql = "SELECT * FROM usuarios WHERE uid=?";
        if (!$stmt = $conn->prepare($sql)) {
            die("Error al preparar la consulta" . $conn->error);
        }
        if (!$stmt->bind_param('s', $cookie)) {
            die("Error al hacer el bind_param: " . $stmt->error);
        }
        if (!$stmt->execute()) {
            die("Error al hacer el execute: " . $stmt->error);
        }
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();
            //Iniciamos sesión
            return $usuario;
        } else {
            return false;
        }
    }

}
