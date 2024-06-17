<?php
$http_origin = $_SERVER['HTTP_ORIGIN'];
header("Access-Control-Allow-Origin: $http_origin");

// Este archivo recibe datos de formulario para hacer algo con ellos
session_start();

require_once './bd.php';
$bd = new basededatos;

if (isset($_POST['username']) and isset($_POST['password'])) {
    $user = $bd->login($_POST['username'], $_POST['password']);
    $user['propiedades'] = $bd->getMisPropiedades($user['id']);
    $_SESSION['user'] = $user;
    echo json_encode($user);
}
