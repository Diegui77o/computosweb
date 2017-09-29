<?php
require_once "db.php";

class Rol
{
    public $id;
    public $nombre;
    public $conexion;

    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Listado de roles
    public function listarRoles()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM rol ORDER BY id");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
}
