<?php

/**
 * Operaciones con la base de datos
 */
class basededatos
{
    private $conn;
    /**
     * Constructor de la clase
     * Crea la conexión con la bse de datos
     */
    public function __construct()
    {

        $config = parse_ini_file('./config.ini');
        try {
            //$opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
            //$dsn = 'mysql:host=' . $config['server'] . ';dbname=' . $config['base'];
            //$this->conn = new PDO($dsn, $config['user'], $config['pass'], $opc);
            //$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn = new mysqli($config['server'], $config['user'], $config['pass'], $config['base']);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Método privado que ejecuta una consulta preparada que devuelva una única fila
     *
     * @param [type] $query
     * @return void
     */
    private function selectQuery($query)
    {
        try {
            $query->execute();
            $result = $query->get_result();
            return $result->fetch_assoc();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Método privado que ejecuta una consulta preparada que devuelva múltiples filas
     *
     * @param [type] $query
     * @return void
     */
    private function selectsQuery($query)
    {
        try {
            $query->execute();
            $result = $query->get_result();
            $respuesta = array();
            while ($row = $result->fetch_assoc()) {
                array_push($respuesta, $row);
            }
            return $respuesta;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * Método privado que ejecuta una consulta preparada que hace insert o update
     */
    private function insertQuery($query)
    {
        try {
            $query->execute();
            $result = $query->get_result();
            return $result;
        } catch (Exception $ex) {
            throw $ex;
        }

    }

    /**
     * Devuelve si existe el usuario con las credenciales dadas
     *
     * @param [type] $username
     * @param [type] $password
     * @return void
     */
    public function login($username, $password)
    {
        $query = $this->conn->prepare("SELECT id, email, nombre, apellidos, nif, direccion, password_hash FROM usuario WHERE email=?");
        $query->bind_param('s', $username);
        $user = $this->selectQuery($query);
        if (isset($user['id']) and password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            $user['roles'] = array();
            // Rol administrador
            $query = $this->conn->prepare("SELECT valor FROM info WHERE clave = 'admin user'");
            $result = $this->selectQuery($query);
            if ($user['id'] == $result['valor']) {
                array_push($user['roles'], 'administrador');
            }
            // Rol propietario
            $query = $this->conn->prepare("SELECT COUNT(*) AS n FROM propiedad WHERE usuario_id=?");
            $query->bind_param("i", $user['id']);
            $result = $this->selectQuery($query);
            if ($result['n'] > 0) {
                array_push($user['roles'], 'propietario');
            }
            return $user;
        }
    }

    public function leerMensaje($id){
        $query = $this->conn->prepare("UPDATE mensaje set leido = curdate() WHERE id = ?");
        $query->bind_param('i', $id);
        $this->insertQuery($query);
    }

    /**
     * Función que envía un mensaje al administrador
     */
    public function enviarMensajeAdministrador($id, $titulo, $mensaje){
        $query = $this->conn->prepare("INSERT INTO mensaje(usuario_id, fecha, titulo, mensaje) VALUES(?, curdate(), ?, ?)");
        $query->bind_param('iss', $id, $titulo, $mensaje);
        $this->insertQuery($query);
    }

    /**
     * Función que devuelve todas las viviendas
     *
     * @return void
     */
    public function getViviendas()
    {
        $query = $this->conn->prepare("SELECT * FROM vivienda");
        return $this->selectsQuery($query);
    }

    public function getMisViviendas($uid)
    {
        $query = $this->conn->prepare("SELECT vivienda.* FROM vivienda JOIN propiedad ON vivienda.id = propiedad.vivienda_id WHERE propiedad.fecha_venta IS NULL AND usuario_id=?");
        $query->bind_param('i', $uid);
        return $this->selectsQuery($query);
    }

    /**
     * Función que devuelve todos los bloques
     *
     * @return void
     */
    public function getBloques()
    {
        $query = $this->conn->prepare("SELECT * FROM bloque");
        return $this->selectsQuery($query);
    }

    /**
     * Función que devuelve todos los usuarios
     *
     * @return void
     */
    public function getUsuarios()
    {
        $query = $this->conn->prepare("SELECT id, nif, telefono, email, nombre, apellidos, direccion FROM usuario");
        return $this->selectsQuery($query);
    }

    /**
     * Función que devuelve los usuarios que tiene derecho a ver un propietario
     *
     * @param [type] $uid
     * @return void
     */
    public function getUsuariosPropietario($uid)
    {
        $query = $this->conn->prepare("SELECT id, telefono, email, nombre, apellidos FROM usuario WHERE id=? OR id IN (SELECT presidente_id FROM bloque) OR id IN (SELECT valor FROM info WHERE clave = 'admin user')");
        $query->bind_param('i', $uid);
        return $this->selectsQuery($query);
    }

    /**
     * Función que devuelve la tabla de información de la coomunidad
     *
     * @return void
     */
    public function getInfo()
    {
        $query = $this->conn->prepare("SELECT * FROM info");
        return $this->selectsQuery($query);
    }

    /**
     * Función que devuelve todas las propiedades
     *
     * @param [type] $uid
     * @return void
     */
    public function getMisPropiedades($uid)
    {
        $query = $this->conn->prepare("SELECT * FROM propiedad WHERE usuario_id=? AND fecha_venta IS NULL");
        $query->bind_param("i", $uid);
        return $this->selectsQuery($query);
    }

    public function getAdministrador()
    {
        $query = $this->conn->prepare("SELECT * FROM usuario WHERE id=(SELECT valor FROM info WHERE clave = 'admin user')");
        return $this->selectQuery($query);
    }

    public function getRecibosImpagados()
    {
        $query = $this->conn->prepare("SELECT * FROM recibo_comunidad WHERE fecha_pagado IS NULL");
        return $this->selectsQuery($query);
    }

    public function getMisRecibosImpagados($id)
    {
        $query = $this->conn->prepare("SELECT * FROM recibo_comunidad WHERE usuario_id=? AND fecha_pagado IS NULL");
        $query->bind_param("i", $id);
        return $this->selectsQuery($query);
    }

    public function getMensajes(){
        $query = $this->conn->prepare("SELECT * FROM mensaje");
        return $this->selectsQuery($query);
    }

    public function generaRecibosEsteMes()
    {
        $hoy = new DateTime('now');
        $mes = $hoy->format('m');
        $query = $this->conn->prepare("SELECT COUNT(*) AS n FROM recibo_comunidad WHERE MONTH(fecha_emision)=?");
        $query->bind_param('i', $mes);
        if ($this->selectQuery($query)['n'] == 0) {
            $fecha_inicio = (clone $hoy)->modify('first day of this month');
            $fecha_fin = (clone $hoy)->modify('last day of this month');
            $fecha_limite = (clone $fecha_fin)->modify('+3 month');
            $query = $this->conn->prepare("SELECT vivienda.id, usuario_id, vivienda.importe_comunidad FROM propiedad JOIN vivienda ON vivienda.id = propiedad.vivienda_id JOIN usuario ON usuario.id = propiedad.usuario_id WHERE fecha_venta IS NULL");
            $viviendas = $this->selectsQuery($query);
            foreach ($viviendas as $vivienda) {
                $query = $this->conn->prepare("INSERT INTO recibo_comunidad(fecha_emision, fecha_limite, fecha_inicio_periodo, fecha_fin_periodo, usuario_id, vivienda_id, importe, descripcion) VALUES(?, ?, ?, ?, ?, ?, ?, 'Recibo de comunidad')");
                $query->bind_param('ssssddd', $hoy->format('y-m-d'), $fecha_limite->format('y-m-d'), $fecha_inicio->format('y-m-d'), $fecha_fin->format('y-m-d'), $vivienda['usuario_id'], $vivienda['id'], $vivienda['importe_comunidad']);
                $this->insertQuery($query);
            }
        }
    }
}
