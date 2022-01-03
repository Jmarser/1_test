<?php
session_start();
require_once 'conection_db/conexion.php';
include_once 'logica/funciones_examen.php';


if (!isset($_SESSION['nombre']) && !isset($_SESSION['id_usuario'])) {
    header('location:index.php');
} else {

    if (isset($_SERVER['REQUEST_METHOD']) == 'POST') {
        if (isset($_POST['corregir'])) {

            $conn = conectar();
            $aciertos = 0;

            $totalExamenes = totalExamenes($conn)['total'];
            $totalExamenes++;

            $respuestas = $_POST['respuesta'];
            foreach ($respuestas as $respuesta) {
                if (validarRespuesta($conn, $respuesta)['isCorrecta'] == 1) {

                    $datos = getDatosPregunta($conn, $respuesta);

                    $acertada = $datos['veces_acertada'];
                    $acertada++;

                    $porcentaje = ($acertada / $totalExamenes) * 100;

                    $datosPregunta = array(
                        'id_pregunta' => $datos['id_pregunta'],
                        'veces_acertada' => $acertada,
                        'porcentaje' => $porcentaje
                    );

                    updatePreguntas($conn, $datosPregunta);

                    $aciertos++;
                }
            }
            
            $respuestas = $_POST['respuesta_multi'];
            foreach ($respuestas as $respuesta) {
                if (validarRespuesta($conn, $respuesta)['isCorrecta'] == 1) {
                    $aciertos++;
                }
            }

            $correctas = correctas($conn)['total'];
            $num_preguntas = num_preguntas($conn)['total'];

            $nota = ($aciertos * 10) / $correctas;

            $porcen_aciertos = ($aciertos / $correctas) * 100;

            $examen = array(
                'id_user' => $_SESSION['id_usuario'],
                'nota' => $nota,
                'resp_correctas' => $aciertos,
                'porcentaje'=> $porcen_aciertos
            );

            reg_examen($conn, $examen);

            desconectar($conn);
        }
    }
}
?>

?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Pagina usuario</title>
</head>

<body>
    <nav>
        <span><strong>Usuario: </strong> <?php echo $_SESSION['nombre']; ?></span>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
    <header>
        <h1>Cuestionario Online PHP</h1>
    </header>
    <main>
        <div class="content_main">
            <div class="titulo_test">
                <h3>Resultados de la prueba</h3>
            </div>
            <div class="detalles_test">
                <ul>
                    <li>Número de preguntas: <?php echo $num_preguntas; ?></li>
                    <li>Número de respuestas correctas: <?php echo $correctas; ?></li>
                    <li>Número de respuestas acertadas: <?php echo $aciertos; ?></li>
                </ul>
            </div>
            <div class="resultado_test">
                <h3>Nota total del test: <?php echo round($nota, 2); ?></h3>
            </div>
            <div class="boton">
                <a href="pag_user.php"><button class="btn">Repetir Test</button></a>
            </div>
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