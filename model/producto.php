<?php
require_once "db.php";

class Producto
{
    public $id;
    public $nombre;
    public $marca;
    public $stock;
    public $stock_minimo;
    public $descripcion;
    public $fecha_alta;
    public $fecha_actualizado;

    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Verifica si el producto existe en la base
    public function existeProducto($nombre, $marca)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM producto WHERE nombre=? AND marca=?");
        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $marca);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }
    // Busca el producto por su id y retorna sus datos
    public function buscarProducto($id)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM producto WHERE id=?");
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
    // Alta
    public function altaProducto($nombre, $marca, $stock, $stock_minimo, $descripcion, $fecha_alta, $fecha_actualizado)
    {
        $consulta = $this->conexion->prepare("INSERT INTO producto (nombre, marca, stock, stock_minimo, descripcion, fecha_alta, fecha_actualizado) VALUES (:nombre, :marca, :stock, :stock_minimo, :descripcion, :fecha_alta, :fecha_actualizado)");
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':marca', $marca);
        $consulta->bindParam(':stock', $stock);
        $consulta->bindParam(':stock_minimo', $stock_minimo);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->bindParam(':fecha_alta', $fecha_alta);
        $consulta->bindParam(':fecha_actualizado', $fecha_actualizado);
        $consulta->execute();
    }
    // Baja
    public function eliminarProducto($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM producto WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Modificar
    public function modificarProducto($nombre, $marca, $descripcion, $id)
    {
        $consulta = $this->conexion->prepare("UPDATE producto SET nombre=?, marca=?, descripcion=? WHERE id=?");
        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $marca);
        $consulta->bindParam(3, $descripcion);
        $consulta->bindParam(4, $id);
        $consulta->execute();
    }
    // Listado
    public function listarProductos()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM producto ORDER BY nombre, marca");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de productos bajos en stock
    public function listarProductosBajosDeStock()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM producto WHERE stock<=30 ORDER BY nombre, marca");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de productos sin stock
    public function listarProductosSinStock()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM producto WHERE stock=0 ORDER BY nombre, marca");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Actualizar stock de un producto
    public function actualizarStock($stock, $id)
    {
        $consulta = $this->conexion->prepare("UPDATE producto SET stock=? WHERE id=?");
        $consulta->bindParam(1, $stock);
        $consulta->bindParam(2, $id);
        $consulta->execute();
    }
    // Consulta para reportes
    // Listado de productos para el reporte
    public function reporteListadoDeProductos()
    {
        $consulta = $this->conexion->prepare("SELECT nombre, marca, stock, fecha_actualizado FROM producto ORDER BY nombre, marca");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
}
