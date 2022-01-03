<?php
session_start();


if (!isset($_SESSION['nombre']) && !isset($_SESSION['id_usuario'])) {
    header('location:index.php');
}


require_once 'conection_db/conexion.php';
include_once 'logica/funciones_examen.php';

$conn = conectar();

$preguntas = getPreguntas($conn);
$Tpreguntas = 0;

?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_examen.css">
    <title>Pagina examen</title>
</head>

<body>
    <nav>
        <span><strong>Usuario: </strong> <?php echo $_SESSION['nombre']; ?></span>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
    <header>
        <div class="cabecera_examen">
            <h1>Cuestionario Online PHP</h1>
            <p>El cuestionario de compone de 10 preguntas, algunas de las cuales con respuestas únicas y otras con respuestas múltiples.</p>
        </div>
    </header>
    <main>
        <div class="content_main">
            <form action="correccion.php" method="post" class="formulario">

                <?php
                foreach ($preguntas as $pregunta) {
                    $Tpreguntas++;
                    if ($pregunta['multiple'] == 0) {

                ?>
                        <div class="detalles_pregunta">
                            <div class="titulo_pregunta">
                                <label for="" name="pregunta"> <?php echo $pregunta['pregunta']  ?> </label>
                            </div>
                            <div class="respuestas">
                                <?php
                                $respuestas = getRespuestas($conn, $pregunta['id_pregunta']);
                                foreach ($respuestas as $respuesta) {
                                ?>
                                    <div class="respuesta">
                                        <input type="radio" name="respuesta[<?php echo $pregunta['id_pregunta']; ?>]" value="<?php echo $respuesta['id_respuesta']; ?>" id=""> <label for=""> <?php echo " " . $respuesta['respuesta']; ?> </label>
                                    </div>

                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    <?php

                    } else {

                    ?>
                        <div class="detalles_pregunta">
                            <div class="titulo_pregunta">
                                <label for="" name="pregunta"> <?php echo $pregunta['pregunta'];  ?> </label>
                            </div>
                            <div class="respuestas">
                                <?php
                                $respuestas = getRespuestas($conn, $pregunta['id_pregunta']);
                                foreach ($respuestas as $respuesta) {
                                ?>
                                    <div class="respuesta">
                                        <input type="checkbox" name="respuesta_multi[]" id="" value="<?php echo $respuesta['id_respuesta']; ?>"> <label for=""> <?php echo " " . $respuesta['respuesta']; ?> </label>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                <?php
                desconectar($conn);
                    }
                }

                ?>
                <input type="hidden" name="Tpreguntas" value="<?php echo $Tpreguntas; ?>">
                <div class="boton">
                    <button type="submit" name="corregir" class="btn">Corregir Examen</button>
                </div>
            </form>
        </div>
    </main>
    <footer>
        <div class="content_footer">
            <div class="img_marca">
                <img src="../img/logo_sin.ico" alt="Logo marca personal">
            </div>
            <div class="derechos">
                <span class="author">Juan Márquez Serrano</span>
                <span class="copy">Todos los derechos reservados &copy; 2021 / 2022</span>
            </div>
        </div>
    </footer>
</body>

</html>