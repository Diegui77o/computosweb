<?php
require_once "db.php";

class PedidoDetalle
{
    public $id;
    public $idpedido;
    public $idproducto;
    public $cantidad;
    public $conexion;
    // Constructor
    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Alta pedido-detalle
    public function altaPedidoDetalle($idpedido, $idproducto, $cantidad)
    {
        $consulta = $this->conexion->prepare("INSERT INTO pedido_detalle (idpedido, idproducto, cantidad) VALUES (:idpedido, :idproducto, :cantidad)");
        $consulta->bindParam(':idpedido', $idpedido);
        $consulta->bindParam(':idproducto', $idproducto);
        $consulta->bindParam(':cantidad', $cantidad);
        $consulta->execute();
    }
    // Baja del pedido detalle - El usuario no podra modificar el detalle del pedido, si se equivoca tendra que eliminarlo y volver a cargar el pedido
    public function eliminarPedidoDetalle($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM pedido_detalle WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Listado pedido detalle
    public function listarPedidoDetalle()
    {
        $consulta = $this->conexion->prepare("SELECT pd.*, pro.nombre AS nombreProducto, pro.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.idestado_pedido AS idEstado, e.nombre AS nombreEstado, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario
            FROM pedido_detalle pd
            INNER JOIN producto pro ON pd.idproducto=pro.id
            INNER JOIN pedido ped ON pd.idpedido=ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido=e.id
            INNER JOIN usuario u ON ped.idusuario=u.id
            ORDER BY ped.fecha_alta DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de pedidos de los usuarios (mis pedidos)
    public function misPedidos($idusuario)
    {
        $consulta = $this->conexion->prepare("SELECT pd.*, p.nombre AS nombreProducto, p.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.descripcion AS descripcion, ped.observacion AS observacion, e.nombre AS estado, ped.idusuario AS idusuario
            FROM pedido_detalle pd
            INNER JOIN producto p ON pd.idproducto=p.id
            INNER JOIN pedido ped ON pd.idpedido = ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido = e.id
            WHERE ped.idusuario = ?
            ORDER BY ped.fecha_alta DESC");
        $consulta->bindParam(1, $idusuario);
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Visualizar el pedido
    public function visualizarPedido($idusuario, $id)
    {
        $consulta = $this->conexion->prepare("SELECT pd.*, p.nombre AS nombreProducto, p.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.descripcion AS descripcion, ped.observacion AS observacion, e.nombre AS estado
            FROM pedido_detalle pd
            INNER JOIN producto p ON pd.idproducto=p.id
            INNER JOIN pedido ped ON pd.idpedido = ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido = e.id
            WHERE (ped.idusuario = ? AND pd.id=?)
            ORDER BY ped.fecha_alta DESC");
        $consulta->bindParam(1, $idusuario);
        $consulta->bindParam(2, $id);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }
    // Listado de pedidos reservados
    public function listarPedidosReservados()
    {
        $consulta = $this->conexion->prepare("SELECT pd.*, pro.nombre AS nombreProducto, pro.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.idestado_pedido AS idEstado, e.nombre AS nombreEstado, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario, ped.descripcion AS descripcion, ped.observacion AS observacion, e.nombre AS estado, a.nombre AS nombreArea
            FROM pedido_detalle pd
            INNER JOIN producto pro ON pd.idproducto=pro.id
            INNER JOIN pedido ped ON pd.idpedido=ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido=e.id
            INNER JOIN usuario u ON ped.idusuario=u.id
            INNER JOIN area a ON u.idarea = a.id
            WHERE e.id = 1
            ORDER BY ped.fecha_alta DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de pedidos entregados
    public function listarPedidosEntregados()
    {
        $consulta = $this->conexion->prepare("SELECT pd.*, pro.nombre AS nombreProducto, pro.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.idestado_pedido AS idEstado, e.nombre AS nombreEstado, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario, ped.descripcion AS descripcion, ped.observacion AS observacion, e.nombre AS estado, a.nombre AS nombreArea
            FROM pedido_detalle pd
            INNER JOIN producto pro ON pd.idproducto=pro.id
            INNER JOIN pedido ped ON pd.idpedido=ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido=e.id
            INNER JOIN usuario u ON ped.idusuario=u.id
            INNER JOIN area a ON u.idarea = a.id
            WHERE e.id = 2
            ORDER BY ped.fecha_alta DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de pedidos cancelados
    public function listarPedidosCancelados()
    {
        $consulta = $this->conexion->prepare("SELECT pd.*, pro.nombre AS nombreProducto, pro.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.idestado_pedido AS idEstado, e.nombre AS nombreEstado, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario, ped.descripcion AS descripcion, ped.observacion AS observacion, e.nombre AS estado, a.nombre AS nombreArea
            FROM pedido_detalle pd
            INNER JOIN producto pro ON pd.idproducto=pro.id
            INNER JOIN pedido ped ON pd.idpedido=ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido=e.id
            INNER JOIN usuario u ON ped.idusuario=u.id
            INNER JOIN area a ON u.idarea = a.id
            WHERE e.id = 3
            ORDER BY ped.fecha_alta DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Devolver la cantidad de un pedido
    public function devolverCantidad($id)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM pedido_detalle WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos[3];
    }
    // Devuelve el id del producto
    public function devolverIdProducto($id)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM pedido_detalle WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos[2];
    }
    // Devolver id pedido
    public function devolverIdPedido($id)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM pedido_detalle WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos[1];
    }
    // Visualiza el detalle del pedido
    public function visualizarDetallePedido($idpedido)
    {
        $consulta = $this->conexion->prepare("SELECT pd.*, p.nombre AS nombreProducto, p.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.descripcion AS descripcion, ped.observacion AS observacion, e.nombre AS estado, ped.idusuario AS idusuario, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario, a.nombre AS nombreArea
            FROM pedido_detalle pd
            INNER JOIN producto p ON pd.idproducto = p.id
            INNER JOIN pedido ped ON pd.idpedido = ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido = e.id
            INNER JOIN usuario u ON ped.idusuario = u.id
            INNER JOIN area a ON u.idarea = a.id
            WHERE ped.id = ?
            ORDER BY ped.fecha_alta DESC");
        $consulta->bindParam(1, $idpedido);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }
    // Pedidos del dia del usuario para consultar si puede realizar el pedido dependiendo del stock minimo del producto
    public function pedidoDelDia($idusuario, $fecha, $idproducto)
    {
        $consulta = $this->conexion->prepare("
                SELECT p.nombre, p.marca, pd.cantidad, ped.fecha_alta, e.nombre AS estado, u.nombre, u.apellido
                FROM pedido ped INNER JOIN usuario u ON ped.idusuario = u.id
                INNER JOIN pedido_detalle pd ON pd.idpedido = ped.id
                INNER JOIN producto p ON pd.idproducto = p.id
                INNER JOIN estado_pedido e ON ped.idestado_pedido = e.id
                WHERE u.id=? and ped.fecha_alta=? and p.id=?
                ");
        $consulta->bindParam(1, $idusuario);
        $consulta->bindParam(2, $fecha);
        $consulta->bindParam(3, $idproducto);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }
    // Para imprimir un listado de los pedidos del día del usuario
    public function reportePedidosDelUsuario($idusuario, $fecha)
    {
        $consulta = $this->conexion->prepare("
                SELECT p.nombre, p.marca, pd.cantidad, ped.fecha_alta, e.nombre AS estado, u.nombre, u.apellido
                FROM pedido ped INNER JOIN usuario u ON ped.idusuario = u.id
                INNER JOIN pedido_detalle pd ON pd.idpedido = ped.id
                INNER JOIN producto p ON pd.idproducto = p.id
                INNER JOIN estado_pedido e ON ped.idestado_pedido = e.id
                WHERE u.id=? and ped.fecha_alta=?
                ");
        $consulta->bindParam(1, $idusuario);
        $consulta->bindParam(2, $fecha);
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
