<?php
$http_origin = $_SERVER['HTTP_ORIGIN'];
header("Access-Control-Allow-Origin: $http_origin");
session_start();

if (in_array('propietario', $_SESSION['user']['roles'])) {
    $uid = $_SESSION['user']['id'];
    require_once './bd.php';
    $bd = new basededatos;

    $respuesta = array();
    $respuesta['viviendas'] = $bd->getMisViviendas($uid);
    $respuesta['bloques'] = $bd->getBloques();
    $respuesta['info'] = $bd->getInfo();
    $respuesta['usuarios'] = $bd->getUsuariosPropietario($uid);
    $respuesta['administrador'] = $bd->getAdministrador();
    $respuesta['recibosImpagados'] = $bd->getMisRecibosImpagados($uid);

    echo json_encode($respuesta);
}
