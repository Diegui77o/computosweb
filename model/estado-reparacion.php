<?php

require_once "db.php";

class EstadoReparacion
{
    public $id;
    public $nombre;
    public $conexion;
    // Contructor
    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Listado de los estados de reparaciones
    public function listarEstadoReparaciones()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM estado_reparacion ORDER BY id");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
}
