<?php
session_start();

require_once 'conection_db/conexion.php';
include_once 'logica/funciones_examen.php';

if (!isset($_SESSION['nombre']) && !isset($_SESSION['id_usuario'])) {
    header('location:index.php');
} else {
    $conn = conectar();
    $num_preguntas = num_preguntas($conn)['total'];
    $num_veces_examen = vecesRealizado($conn, $_SESSION['id_usuario'])['total'];
    $nota = getNota($conn, $_SESSION['id_usuario'])['nota'];
    desconectar($conn);
}


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
                <h3>Comprueba cuanto sabes de PHP</h3>
            </div>
            <div class="detalles_test">
                <ul>
                    <li>Número de preguntas: <?php echo $num_preguntas; ?></li>
                    <li>Tipo de respuestas: únicas y múltiples</li>
                    <li>Puntuación máxima: 10</li>
                    <li>Número de intentos máximos: 3</li>
                    <li>Has realizado el examen: <?php echo $num_veces_examen; ?> veces.</li>
                    <?php
                        if($num_veces_examen > 0){
                            ?>
                                <li>Tu nota más alta en este test es de: <?php echo $nota ?></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>

            <?php
            if ($num_veces_examen < 3) {
            ?>
                <div class="boton">
                    <a href="examen.php"><button class="btn">Empezar Test</button></a>
                </div>
            <?php
            } else {
            ?>
                <div class="boton">
                    <p class="max-intentos">Ya no puedes realizar más el examen</p>
                </div>
            <?php
            }
            ?>

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