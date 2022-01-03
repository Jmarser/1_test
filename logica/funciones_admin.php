<?php

/**
 * Función con la que obtenemos todos los alumnos que hayan realizado el test, con sus notas y estadisticas
 */
function getAlumnos($conection){
    $sql_alumnos = "SELECT u.nombre, e.nota, e.resp_correctas, e.porcentaje FROM users u, examen e WHERE u.id_user = e.id_user";

    $ejecutar = $conection->prepare($sql_alumnos);
    $ejecutar->execute();

    return $ejecutar->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Función con la que obtenemos las estadísticas de las preguntas que forman el test
 */
function getPreguntas($conection){
    $sql_preguntas = "SELECT pregunta, veces_acertada, porcentaje FROM preguntas";

    $ejecutar = $conection->prepare($sql_preguntas);
    $ejecutar->execute();

    return $ejecutar->fetchAll(PDO::FETCH_ASSOC);
}

function getMejoresNotas($conection){
    $sql_mejores_notas = "SELECT nombre, MAX(nota) AS nota FROM users u, examen e WHERE u.id_user = e.id_user GROUP BY e.id_user";

    $ejecutar = $conection->prepare($sql_mejores_notas);
    $ejecutar->execute();

    return $ejecutar->fetchAll(PDO::FETCH_ASSOC);
}

?>