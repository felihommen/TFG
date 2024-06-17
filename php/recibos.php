<?php
// Este archivo recibe datos de formulario para hacer algo con ellos
$http_origin = $_SERVER['HTTP_ORIGIN'];
header("Access-Control-Allow-Origin: $http_origin");

session_start();
if (in_array('administrador', $_SESSION['user']['roles']) and $_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once './bd.php';
    $bd = new basededatos;

    $bd->generaRecibosEsteMes();
}