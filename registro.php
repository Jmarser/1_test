<?php

require_once 'conection_db/conexion.php';
include_once 'logica/funciones_login.php';

//comprobamos que nos llega una petición por post
if (isset($_SERVER['REQUEST_METHOD']) == 'POST') {
    //comprobamos que la petición sea para registro
    if (isset($_POST['registro'])) {

        //obtenemos los diferentes campos para registro y los saneamos en el caso de que la variable tenga contenido
        $nombre = isset($_POST['nombre']) ? resetearString($_POST['nombre']) : "";
        $email = isset($_POST['email']) ? validarEmail($_POST['email']) : "";
        $email_rep = isset($_POST['email_rep']) ? validarEmail($_POST['email_rep']) : "";
        $pass = isset($_POST['pass']) ? resetearString($_POST['pass']) : "";
        $pass_rep = isset($_POST['pass_rep']) ? resetearString($_POST['pass_rep']) : "";

        $errores = array();//array donde guardaremos los errores.
        $mensaje;

        //Comprobamos que el campo tenga contenido, en caso contrario guardamos el mensaje de error correspondiente.
        if (empty($nombre)) {
            array_push($errores, "Debe insertar un nombre de usuario.");
        }

        if (empty($email)) {
            array_push($errores, "Debe insertar un correo válido.");
        }

        if ($email !== $email_rep) {
            array_push($errores, "Los correos no coinciden.");
        }

        if (empty($pass)) {
            array_push($errores, "Debe indicar un password para el usuario.");
        }

        if ($pass !== $pass_rep) {
            array_push($errores, "Los password indicados no coinciden");
        }

        //Si no hay errores realizamos el registro.
        if ($errores == null) {

            $conn = conectar();//obtenemos la conexión a la base de datos.

            echo "La clave ofuscada es:".ofuscarPassword($pass);

            //Array con los datos del usuario
            $usuario = array(
                "nombre" => $nombre,
                "email" => $email,
                "pass" => password_hash(ofuscarPassword($pass), PASSWORD_DEFAULT, ['cost' => 10]),
                "activo" => 1, //por defecto activo
                "rol" => "user" //por defecto usuario
            );

            //Realizamos el registro llamando a la función que hemos creado para ello.
            $resultado = registroUsuario($conn, $usuario);
            desconectar($conn);//cerramos la conexión con la base de datos.

            //si obtenemos un error, lo guardamos para mostrarlo.
            if ($resultado['error']) {
                array_push($errores, $resultado['mensaje']);
            } else {
                //El usuario se ha registrado correctamente
                $mensaje = $resultado['mensaje'];
                header("refresh:5; url=index.php");//redireccionamos tras 5 segundos a la pantalla login
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

        <form action="registro.php" class="form" method="post">
            <div class="cabecera">
                <h3>Registro usuario</h3>
            </div>
            
            <div class="campo errores">
                <?php
                //En el caso de tener errores en el formulario o en el registro, los mostraremos aquí.
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

            <div class="campo registro_ok">
                <h4>
                    <?php
                    //El usuario se ha registrado correctamente y lo mostramos aquí
                    if (isset($mensaje) && !empty($mensaje)) {
                        echo $mensaje;
                    }
                    ?>
                </h4>
            </div>

            <div class="campo">
                <label class="label" for=""><img src="../img/user.svg" alt=""> Usuario</label>
                <input class="input" type="text" name="nombre" id="">
            </div>
            <div class="campo">
                <label class="label" for="email"><img src="../img/email.svg" alt=""> Correo</label>
                <input class="input" type="email" name="email" id="">
            </div>
            <div class="campo">
                <label class="label" for="email_rep"><img src="../img/email.svg" alt=""> Repita Correo</label>
                <input class="input" type="email" name="email_rep" id="" autocomplete="false">
            </div>
            <div class="campo">
                <label class="label" for=""><img src="../img/password.svg" alt=""> Password</label>
                <input class="input" type="password" name="pass" id="">
            </div>
            <div class="campo">
                <label class="label" for=""><img src="../img/password.svg" alt=""> Repita Password</label>
                <input class="input" type="password" name="pass_rep" id="">
            </div>
            <div class="campo">
                <button type="submit" class="btn button" name="registro">Registro</button>
            </div>
            <div class="registro campo">
                <span>Tienes cuenta. <a href="index.php">Login</a></span>
            </div>
        </form>

    </div>
</body>

</html>