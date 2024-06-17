<?php
// Este archivo recibe mensajes
$http_origin = $_SERVER['HTTP_ORIGIN'];
header("Access-Control-Allow-Origin: $http_origin");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' and !in_array('administrador', $_SESSION['user']['roles'])) {
    require_once './bd.php';
    $bd = new basededatos;
    $data = json_decode(file_get_contents('php://input'));
    $bd->enviarMensajeAdministrador($_SESSION['user']['id'], $data->titulo, $data->mensaje);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' and in_array('administrador', $_SESSION['user']['roles'])){
    require_once './bd.php';
    $bd = new basededatos;
    $data = json_decode(file_get_contents('php://input'));
    $bd->leerMensaje($data->id);
}
elseif ($_SERVER['REQUEST_METHOD'] == 'GET' and in_array('administrador', $_SESSION['user']['roles'])){
    require_once './bd.php';
    $bd = new basededatos;
    $respuesta = array('mensajes' => $bd->getMensajes());
    echo json_encode($respuesta);
}