<?php
    session_start();

    setcookie(session_name(), '', 100); //aliminamos la cookie creada por defecto
    session_unset(); //eliminamos las variables de sesion
    session_destroy(); //destruimos la sesion

    header('location:index.php');

?>