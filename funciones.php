<?php
/**
 * Función para limpiar los datos del usuario como $_GET y $_POST
 * @param type $texto
 * @return type Texto limpio
 */
function limpiar_datos($texto) {
    $texto = trim($texto);
    $texto = htmlspecialchars($texto);
    $texto = stripslashes($texto);
    return $texto;
}
