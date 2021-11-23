<?php
require 'Session.php';
session_start();
Session::cerrar();
Session::borrar_cookie();
header('Location: index.php');