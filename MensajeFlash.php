<?php


class MensajeFlash {
    public static function anadir_mensaje(string $mensaje) {
        $_SESSION['mensajes_flash'][] = $mensaje;
    }

    public static function imprimir_mensajes() {
        if (isset($_SESSION['mensajes_flash'])) {
            foreach ($_SESSION['mensajes_flash'] as $mensaje) {
                print "<div class=\"mensaje_flash\">$mensaje</div>";
            }
            unset($_SESSION['mensajes_flash']);
        }
    }
}