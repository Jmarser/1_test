<?php

function conectar(){

    $config = include 'config.php';

    try{

        $dsn = $config['db']['sgbd'].'='.$config['db']['host'].';dbname='.$config['db']['db_name'].';charset='.$config['db']['charset'];

        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'],$config['db']['options']);

        return $conexion;

    }catch(PDOException $e){

        return null;
        exit;
    }
}

function desconectar($conexion){
    if($conexion != null){
        $conexion = null;
    }
    return $conexion;
}

?>