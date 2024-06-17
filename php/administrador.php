<?php
$http_origin = $_SERVER['HTTP_ORIGIN'];
header("Access-Control-Allow-Origin: $http_origin");
session_start();

if (in_array('administrador', $_SESSION['user']['roles'])) {
    require_once './bd.php';
    $bd = new basededatos;

    $respuesta = array();
    $respuesta['viviendas'] = $bd->getViviendas();
    $respuesta['bloques'] = $bd->getBloques();
    $respuesta['usuarios'] = $bd->getUsuarios();
    $respuesta['info'] = $bd->getInfo();
    $respuesta['recibosImpagados'] = $bd->getRecibosImpagados();

    echo json_encode($respuesta);
} else {
    $respuesta = array(
        'viviendas' => array(),
        'bloques' => array(),
        'usuarios' => array(),
        'info' => array(),
        'recibosImpagados' => array());
        echo json_encode($respuesta);
    }
