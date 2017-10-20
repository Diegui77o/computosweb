<?php
require_once "db.php";

class Pedido
{
    public $id;
    public $idusuario;
    public $fecha_alta;
    public $descripcion;
    public $observacion;
    public $idestado_pedido;
    public $conexion;
    // Constructor
    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Buscar pedido
    public function buscarPedido($id)
    {
        $consulta = $this->conexion->prepare("SELECT p.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoPedido
                                            FROM pedido p
                                            INNER JOIN usuario u ON p.idusuario=u.id
                                            INNER JOIN estado_pedido e ON p.idestado_pedido=e.id
                                            WHERE p.id=?");
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
    // Alta pedido - Realiza el pedido y retorna su id
    public function altaPedido($idusuario, $fecha_alta, $descripcion, $observacion, $idestado_pedido)
    {
        $consulta = $this->conexion->prepare("INSERT INTO pedido (idusuario, fecha_alta, descripcion, observacion, idestado_pedido) VALUES (:idusuario, :fecha_alta, :descripcion, :observacion, :idestado_pedido)");
        $consulta->bindParam(':idusuario', $idusuario);
        $consulta->bindParam(':fecha_alta', $fecha_alta);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->bindParam(':observacion', $observacion);
        $consulta->bindParam(':idestado_pedido', $idestado_pedido);
        $consulta->execute();
        $idpedido = $this->conexion->lastInsertId();
        return $idpedido;
    }
    // Baja
    public function eliminarPedido($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM pedido WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Modificación
    // Solo se podra modificar la descripcion y fecha si el usuario tuvo un error igual quedara registrado, solo se cancelara el pedido
    public function modificarPedido($fecha_alta, $descripcion, $id)
    {
        $consulta = $this->conexion->prepare("UPDATE pedido SET fecha_alta=?, descripcion=? WHERE id=?");
        $consulta->bindParam(1, $fecha_alta);
        $consulta->bindParam(2, $descripcion);
        $consulta->bindParam(3, $id);
        $consulta->execute();
    }
    // Listado de pedidos
    public function listarPedidos()
    {
        $consulta = $this->conexion->prepare("SELECT p.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoPedido
                                            FROM pedido p
                                            INNER JOIN usuario u ON p.idusuario=u.id
                                            INNER JOIN estado_pedido e ON p.idestado_pedido=e.id
                                            ORDER BY p.fecha_alta DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Actualiza el estado del pedido y la observación
    public function actualizarEstado($id, $idestado_pedido, $observacion)
    {
        $consulta = $this->conexion->prepare("UPDATE pedido SET idestado_pedido=?, observacion=? WHERE id=?");
        $consulta->bindParam(1, $idestado_pedido);
        $consulta->bindParam(2, $observacion);
        $consulta->bindParam(3, $id);
        $consulta->execute();
    }
    // Cantidad de pedidos reservados
    public function cantidadPedidosReservados()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM pedido WHERE idestado_pedido=1");
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 0) {
            $resultado = 0;
        } else {
            $resultado = $count;
        }
        return $resultado;
    }
    // Modificar descripción y observación
    public function modificarDesObs($id, $descripcion, $observacion)
    {
        $consulta = $this->conexion->prepare("UPDATE pedido SET descripcion=?, observacion=? WHERE id=?");
        $consulta->bindParam(1, $descripcion);
        $consulta->bindParam(2, $observacion);
        $consulta->bindParam(3, $id);
        $consulta->execute();
    }
}
