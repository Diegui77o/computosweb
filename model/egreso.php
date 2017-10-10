<?php
require_once "db.php";

class Egreso
{
    public $id;
    public $idproducto;
    public $idarea;
    public $cantidad;
    public $fecha;
    public $descripcion;
    public $conexion;
    // Constructor
    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Alta egreso
    public function altaEgreso($idproducto, $idarea, $cantidad, $fecha, $descripcion)
    {
        $consulta = $this->conexion->prepare("INSERT INTO egreso (idproducto, idarea, cantidad, fecha, descripcion) VALUES (:idproducto, :idarea, :cantidad, :fecha, :descripcion)");
        $consulta->bindParam(':idproducto', $idproducto);
        $consulta->bindParam(':idarea', $idarea);
        $consulta->bindParam(':cantidad', $cantidad);
        $consulta->bindParam(':fecha', $fecha);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->execute();
    }
    // Baja egreso
    public function eliminarEgreso($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM egreso WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Buscar egreso
    public function buscarEgreso($id)
    {
        $consulta = $this->conexion->prepare("SELECT e.*, p.nombre AS nombreProducto, p.marca AS marcaProducto, a.nombre AS nombreArea
                                            FROM egreso e
                                            INNER JOIN producto p ON e.idproducto=p.id
                                            INNER JOIN area a ON e.idarea=a.id
                                            WHERE e.id=?
                                            ORDER BY e.fecha DESC");
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
    // Listado egresos
    public function listarEgresos()
    {
        $consulta = $this->conexion->prepare("SELECT e.*, p.nombre AS nombreProducto, p.marca AS marcaProducto, a.nombre AS nombreArea
                                            FROM egreso e
                                            INNER JOIN producto p ON e.idproducto=p.id
                                            INNER JOIN area a ON e.idarea=a.id
                                            ORDER BY e.fecha DESC
                                            ");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
}
