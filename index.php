<?php

require_once 'conection_db/conexion.php';
include_once 'logica/funciones_login.php';

//Comprobamos que recivimos una peticion por el método post
if (isset($_SERVER['REQUEST_METHOD']) == 'POST') {

    //comprobamos que la petición post se ha hecho desde el boton login
    if (isset($_POST['login'])) {

        /*Obtenemos los datos que hemos recivido por el método post.
        comprobamos lo que nos ha llegado y en el caso de recivir algo, lo validamos y saneamos */
        $email = isset($_POST['email']) ? validarEmail($_POST['email']) : "";
        $pass = isset($_POST['pass']) ? resetearString($_POST['pass']) : "";

        $errores = array(); //Aquí guardaremos los errores que se puedan dar.

        //Comprobamos los posibles errores y los guardamos en caso existir.
        if (empty($email)) {
            array_push($errores, "Debe indicar un nombre de usuario.");
        }

        if (empty($pass)) {
            array_push($errores, "Password no correcto.");
        }

        //Si no hay errores, realizamos la conexión a la base de datos
        if ($errores == null) {

            $conn = conectar();//obtenemos conexión con la base de datos.

            //realizamos la consulta llamando a la función que hemos creado para ello.
            $resultado = loginUsuario($conn, $email, $pass);

            desconectar($conn);//cerramos la conexión con la base de datos.

            /* Comprobamos si ha habido algún error al realizar el login del usuario.*/
            if ($resultado['error']) {
                //Ha habido un error que mostraremos por pantalla.
                array_push($errores, $resultado['mensaje']);

            } else {

                $usuario = $resultado['usuario'];

                $activo = validarUsuario($usuario);
                if(!$activo['error']){//comprobamos que el usuario se encuentra activo en la base de datos.

                    //iniciamos la sesión y guardamos los datos que necesitamos.
                    session_start();
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['id_usuario'] = $usuario['id_user'];

                    //Comprobamos el rol del usuario logado y redireccionamos a su página correspondiente.
                    if($usuario['rol'] == "user"){

                        header("Location: pag_user.php");

                    }else if($usuario['rol'] == "admin"){

                        header("Location: pag_admin.php");
                    }

                }else{//usuario no activo, mostramos el mensaje.
                    array_push($errores, $activo['mensaje']);
                }

            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Jmarser">
    <link rel="stylesheet" href="css/login_style.css">
    <link rel="shortcut icon" href="../img/logo_sin.ico" type="image/x-icon">
    <title>Ejercicio Test PHP</title>
</head>

<body>
    <div class="content_form">

        <form action="index.php" class="form" method="post">
            <div class="cabecera">
                <h3>Login</h3>
            </div>
            <div class="campo errores">

                <?php
                //En el caso de existir errores los mostraremos aquí recorriendo el array.
                if (isset($errores) && !empty($errores)) {
                ?>
                    <ul>
                        <?php
                        foreach ($errores as $error) {
                        ?>
                            <li class=""><?php echo $error;  ?></li>
                        <?php
                        }
                        ?>
                    </ul>
                <?php
                }
                ?>

            </div>
            <div class="campo">
                <label class="label" for=""><img src="../img/email.svg" alt=""> Correo</label>
                <input class="input" type="email" name="email" id="">
            </div>
            <div class="campo">
                <label class="label" for=""><img src="../img/password.svg" alt=""> Password</label>
                <input class="input" type="password" name="pass" id="">
            </div>
            <div class="campo">
                <button type="submit" class="btn button" name="login">Login</button>
            </div>
            <div class="registro campo">
                <span>No tienes cuenta. <a href="registro.php">Registro</a></span>
            </div>
        </form>

    </div>
</body>

</html>