<?php

require_once "db.php";

class Ingreso
{
    public $id;
    public $idproducto;
    public $cantidad;
    public $fecha;
    public $descripcion;
    public $conexion;

    // Contructor
    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Alta ingreso
    public function altaIngreso($idproducto, $cantidad, $fecha, $descripcion)
    {
        $consulta = $this->conexion->prepare("INSERT INTO ingreso (idproducto, cantidad, fecha, descripcion) VALUES (:idproducto, :cantidad, :fecha, :descripcion)");
        $consulta->bindParam(':idproducto', $idproducto);
        $consulta->bindParam(':cantidad', $cantidad);
        $consulta->bindParam(':fecha', $fecha);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->execute();
    }
    // Baja ingreso
    public function eliminarIngreso($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM ingreso WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Buscar ingreso
    public function buscarIngreso($id)
    {
        $consulta = $this->conexion->prepare("SELECT i.*, p.nombre AS nombreProducto, p.marca AS marcaProducto
                                            FROM ingreso i
                                            INNER JOIN producto p ON i.idproducto=p.id
                                            WHERE i.id=?
                                            ORDER BY i.fecha DESC");
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
    // Listado de ingresos
    public function listarIngresos()
    {
        $consulta = $this->conexion->prepare("SELECT i.*, p.nombre AS nombreProducto, p.marca AS marcaProducto
                                            FROM ingreso i
                                            INNER JOIN producto p ON i.idproducto=p.id
                                            ORDER BY i.fecha DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
}
