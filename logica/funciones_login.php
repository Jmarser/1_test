<?php

/**
 * Función con la que obtenemos un registro de la tabla login
 */
function getLogin($conexion, $email){
    $buscar_login = "SELECT * FROM login WHERE email = :correo";

    $ejecutar = $conexion->prepare($buscar_login);
    $ejecutar->bindParam(":correo", $email);
    $ejecutar->execute();
    
    return $ejecutar->fetch(PDO::FETCH_ASSOC);
}


/**
 * Función con la que realizamos el registro de un nuevo usuario en la base de datos.
 * El registro de un nuevo usuario se realiza en dos tablas login y users
 */
function registroUsuario($conexion, $usuario)
{

    //comprobamos que el email no se encuentre ya registrado
    if (getLogin($conexion, $usuario['email']) == null) {
        
        $insert_login = "INSERT INTO login (email, pass) VALUES (:correo, :pass)";

        $resultado = [
            'error' => false,
            'mensaje' => 'El usuario ' . $usuario['nombre'] . ' agregado correctamente.'
        ];

        try {
            //Ejecutamos la inserción en la tabla login
            $ejecutar = $conexion->prepare($insert_login);
            $ejecutar->bindParam(":correo", $usuario['email']);
            $ejecutar->bindParam(":pass", $usuario['pass']);
            $ejecutar->execute();

            $login = getLogin($conexion, $usuario['email']);
            
            $insert_user = "INSERT INTO users (id_login, nombre, isActive, rol) VALUES (:id_login,:nombre,:isActive,:rol)";

            //Realizamos la inserción en la tabla users
            $ejecutar = $conexion->prepare($insert_user);
            $ejecutar->bindParam(":id_login", $login['id_login']);
            $ejecutar->bindParam(":nombre", $usuario['nombre']);
            $ejecutar->bindParam(":isActive", $usuario['activo']);
            $ejecutar->bindParam(":rol", $usuario['rol']);
            $ejecutar->execute();


        } catch (PDOException $e) {
            $resultado = [
                'error' => true,
                'mensaje' => 'Error al agregar el usuario.'
            ];
        }
    } else {
        $resultado = [
            'error' => true,
            'mensaje' => 'El Email ya se encuentra registrado.'
        ];
    }

    return $resultado;
}

/**
 * Función con la realizamos el login del usuario.
 */
function loginUsuario($conexion, $email, $pass)
{

    $login = getLogin($conexion, $email);

    if ($login != null && password_verify($pass, $login['pass'])) {

        $buscar_user = "SELECT * FROM users WHERE id_login = :id_login";

        $ejecutar = $conexion->prepare($buscar_user);
        $ejecutar->bindParam(":id_login", $login['id_login']);
        $ejecutar->execute();
        
        $usuario = $ejecutar->fetch(PDO::FETCH_ASSOC);

        $resultado = [
            'error' => false,
            'mensaje' => 'Usuario encontrado',
            'usuario' => [
                "id_user" => $usuario['id_user'],
                "nombre" => $usuario['nombre'],
                "isActive" => $usuario['isActive'],
                "rol" => $usuario['rol']
            ]
        ];
    } else {
        $resultado = [
            'error' => true,
            'mensaje' => 'Error Usuario no encontrado.'
        ];
    }

    return $resultado;
}

/**
 * Función con la que validamos que el usuario se encuentre activo en la base de datos
 */
function validarUsuario($usuario)
{

    if ($usuario['isActive'] != 0) {
        $resultado = [
            'error' => false
        ];
    } else {
        $resultado = [
            'error' => true,
            'mensaje' => 'El usuario está en estado de baja.'
        ];
    }

    return $resultado;
}

/**
 * Función con la que saneamos los string que se hayan introducido en el formulario.
 */
function resetearString($valor)
{
    $valor = trim($valor); //Eliminamos espacios vacíos.
    $valor = strip_tags($valor); // Eliminamos etiquetas html
    $valor = stripslashes($valor);
    $valor = htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    return $valor;
}

/**
 * Función con la que saneamos y validamos una dirección de correo electrónico.
 */
function validarEmail($valor)
{
    //Primero saneamos de caracteres no deseados
    $valor = filter_var($valor, FILTER_SANITIZE_EMAIL);

    //Validamos la dirección de correo
    return filter_var($valor, FILTER_VALIDATE_EMAIL);
}

/**
 * Función con la que alteramos el password introducido por el usuario, aumentando la seguridad
 * en el caso de que el algoritmo de cifrado sea expuesto.
 */
function ofuscarPassword($pass){
    $resultado = [];
    $array_1 = str_split($pass); //Creamos un array con los caracteres del password
    $array_2 = array_reverse($array_1, false); //array invertido del anterior

    //Recorremos el array y guardamos las posiciones de los array por orden en un nuevo array
    for($i = 0; $i < sizeof($array_1); $i++){
        array_push($resultado, $array_1[$i]);
        array_push($resultado, $array_2[$i]);
    }

    // Devolvemos el nuevo password en forma de cadena.
    return implode("",$resultado);
}
