<?php
session_start();

require_once 'conection_db/conexion.php';
include_once 'logica/funciones_admin.php';

if (!isset($_SESSION['nombre']) && !isset($_SESSION['id_usuario'])) {
    header('location:index.php');
} else {
    $conn = conectar();
    $alumnos = getAlumnos($conn);
    $preguntas = getPreguntas($conn);
    $mejores_notas = getMejoresNotas($conn);

    desconectar($conn);
}


?>
<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_admin.css">
    <title>Página administrador</title>
</head>

<body>
    <nav>
        <span><strong>Administrador: </strong> <?php echo $_SESSION['nombre']; ?></span>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
    <header>
        <h1>ESTADISTICAS CUESTIONARIO PHP</h1>
    </header>
    <main>
        <div class="content_main">
            <div class="content_tabla_usuarios">
                <div class="tabla1">
                    <table>
                        <caption>RESULTADOS EXAMENES ALUMNOS</caption>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Nota</th>
                                <th>Respuestas correctas</th>
                                <th>Porcentaje</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            if ($alumnos != null) {
                                foreach ($alumnos as $alumno) {
                            ?>
                                    <tr>
                                        <td><?php echo $alumno['nombre'];   ?></td>
                                        <td><?php echo $alumno['nota'];   ?></td>
                                        <td><?php echo $alumno['resp_correctas'];   ?></td>
                                        <td><?php echo $alumno['porcentaje'];   ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
                <div class="tabla2">
                    <table>
                        <caption>MÁXIMAS NOTAS ALUMNOS</caption>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Nota máxima</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            if ($mejores_notas != null) {
                                foreach ($mejores_notas as $nota) {
                            ?>
                                    <tr>
                                        <td><?php echo $nota['nombre'];   ?></td>
                                        <td><?php echo $nota['nota'];   ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="content_tabla_preguntas">
                <table>
                    <caption>ESTADISTICAS PREGUNTAS</caption>
                    <thead>
                        <tr>
                            <th>Pregunta</th>
                            <th>Veces acertada</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if ($preguntas != null) {
                            foreach ($preguntas as $pregunta) {
                        ?>
                                <tr>
                                    <td><?php echo $pregunta['pregunta'];   ?></td>
                                    <td><?php echo $pregunta['veces_acertada'];   ?></td>
                                    <td><?php echo $pregunta['porcentaje'];   ?></td>

                                </tr>
                        <?php
                            }
                        }
                        ?>

                    </tbody>
                </table>
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