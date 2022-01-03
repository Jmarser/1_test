<?php

/**
 * Obtenemos todos los datos de las preguntas que forman el test.
 */
function getPreguntas($conection){

    $buscar_preguntas = "SELECT * FROM preguntas";

    $ejecutar = $conection->prepare($buscar_preguntas);
    $ejecutar->execute();
    
    return $ejecutar->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Obtenemos los datos de todas las respuestas que pertenecen a una misma pregunta.
 */
function getRespuestas($conection, $id_pregunta){
    $buscar_respuestas = "SELECT * FROM respuestas WHERE id_pregunta = $id_pregunta";

    $ejecutar = $conection->prepare($buscar_respuestas);
    $ejecutar->execute();
    
    return $ejecutar->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * obtenemos el valor del campo que determina si la respuesta es correcta o no.
 */
function validarRespuesta($conection, $id_respuesta){
    $respuesta = "SELECT isCorrecta FROM respuestas WHERE id_respuesta = $id_respuesta";

    $ejecutar = $conection->prepare($respuesta);
    $ejecutar->execute();

    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtenemos el número de respuestas correctas que hay en el examen.
 * Al haber preguntas con respuestas multiples, las respuestas correctas será superior al número de preguntas del examen
 */
function correctas($conection){
    $sql_correctas = "SELECT COUNT(*) AS total FROM respuestas WHERE isCorrecta = 1";

    $ejecutar = $conection->prepare($sql_correctas);
    $ejecutar->execute();

    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtenemos el número de preguntas que tiene el examen
 */
function num_preguntas($conection){
    $sql_num_preguntas = "SELECT COUNT(*) AS total FROM preguntas";

    $ejecutar = $conection->prepare($sql_num_preguntas);
    $ejecutar->execute();

    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}

/**
 * Función con la que guardamos los resultados del examen de un usuario en la tabla correspondiente
 */
function reg_examen($conection, $examen){
    $sql_insert_examen = "INSERT INTO examen (id_user, nota, resp_correctas, porcentaje) VALUES(:id_user, :nota, :resp_correctas, :porcentaje);";

    $ejecutar = $conection->prepare($sql_insert_examen);
    $ejecutar->bindParam(":id_user", $examen['id_user']);
    $ejecutar->bindParam(":nota", $examen['nota']);
    $ejecutar->bindParam(":resp_correctas", $examen['resp_correctas']);
    $ejecutar->bindParam(":porcentaje", $examen['porcentaje']);
    $ejecutar->execute();
}

/**
 * Obtenemos el número de veces que ha sido realizado el examen por un usuario determinado
 */
function vecesRealizado($conection, $id_user){
    $sql_num_veces ="SELECT COUNT(*) as total FROM examen WHERE id_user = $id_user";

    $ejecutar = $conection->prepare($sql_num_veces);
    $ejecutar->execute();

    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}

/**
 * Obtenemos el número de veces que se ha realizado el examen por todos los usuarios
 */
function totalExamenes($conection){
    $sql_num_veces ="SELECT COUNT(*) as total FROM examen";

    $ejecutar = $conection->prepare($sql_num_veces);
    $ejecutar->execute();

    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}

/**
 * Función con la que obtenemos las estadísticas de una pregunta para poderlas actualizar
 */
function getDatosPregunta($conection, $id_respuesta){
    $sql_datos = "SELECT p.id_pregunta, p.veces_acertada FROM preguntas p, respuestas r WHERE p.id_pregunta = r.id_pregunta AND r.id_respuesta = $id_respuesta";

    $ejecutar = $conection->prepare($sql_datos);
    $ejecutar->execute();

    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}

/**
 * Función con la que actualizamos las estadísticas de una pregunta cuando se ha terminado un examen.
 */
function updatePreguntas($conection, $datosPregunta){

    $sql_update = "UPDATE preguntas SET `veces_acertada` = :veces_acertada, `porcentaje` = :porcentaje WHERE `id_pregunta` = :id_pregunta";

    try{
    $ejecutar = $conection->prepare($sql_update);
    $ejecutar->bindParam(":veces_acertada", $datosPregunta['veces_acertada'], PDO::PARAM_INT);
    $ejecutar->bindParam(":porcentaje", $datosPregunta['porcentaje'], PDO::PARAM_INT);
    $ejecutar->bindParam(":id_pregunta", $datosPregunta['id_pregunta'], PDO::PARAM_INT);
    $ejecutar->execute();
    }catch(PDOException $e){
        return $e->getMessage();
    }

}

/**
 * Función con la que obtenemos la nota más alta que haya tenido un usuario en el test
 */
function getNota($conection, $id_usuario){
    $sql_nota_max = "SELECT MAX(nota) AS nota FROM examen where id_user = $id_usuario";

    $ejecutar = $conection->prepare($sql_nota_max);
    $ejecutar->execute();

    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}
?>