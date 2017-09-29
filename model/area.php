<?php
require_once "db.php";
// Clase area
class Area
{
    public $id;
    public $nombre;
    public $conexion;

    // Constructor
    public function __construct()
    {
        $this->conexion = $conexion = new conexion();
        $this->conexion = $conexion->conectar();
    }
    /*
     * Alta, baja y modificación del area *
     */
    public function altaArea($nombre)
    {
        $consulta = $this->conexion->prepare("INSERT INTO area(nombre) VALUES (:nombre)");
        $consulta->bindParam('nombre', $nombre);
        $consulta->execute();
    }
    // Baja
    public function eliminarArea($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM area WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Modificar
    public function modificarArea($nombre, $id)
    {
        $consulta = $this->conexion->prepare("UPDATE area SET nombre=? WHERE id=?");
        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $id);
        $consulta->execute();
    }
    // Listado de areas
    public function listarAreas()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM area ORDER BY nombre");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Verifica si el area existe
    public function existeArea($nombre)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM area WHERE nombre=?");
        $consulta->bindParam(1, $nombre);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }
    // Buscar el area y retorna sus datos
    public function buscarArea($id)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM area WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }
}
