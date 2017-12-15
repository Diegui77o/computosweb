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
        $consulta = $this->conexion->prepare("SELECT pd.*, pro.nombre AS nombreProducto, pro.marca AS marcaProducto, ped.fecha_alta AS fecha, ped.idestado_pedido AS idEstado, e.nombre AS nombreEstado, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario, ped.descripcion AS descripcion, ped.observacion AS observacion, e.nombre AS estado, a.nombre AS nombreArea
            FROM pedido_detalle pd
            INNER JOIN producto pro ON pd.idproducto=pro.id
            INNER JOIN pedido ped ON pd.idpedido=ped.id
            INNER JOIN estado_pedido e ON ped.idestado_pedido=e.id
            INNER JOIN usuario u ON ped.idusuario=u.id
            INNER JOIN area a ON u.idarea = a.id
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
            ORDER BY ped.fecha_alta DESC, a.nombre, u.usuario, pro.nombre");
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
            ORDER BY ped.fecha_alta DESC, nombreArea");
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
            ORDER BY ped.fecha_alta DESC, nombreArea");
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
                SELECT p.nombre AS nombre, p.marca AS marca, pd.cantidad AS cantidad, ped.fecha_alta, e.nombre AS estado, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario
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
    // Pedidos del usuario en fecha determinada y con estado reservado
    // Pasar como parametros el id del usuari y la fecha
    public function reportePedidosDelUsuario($idusuario, $fecha)
    {
        $consulta = $this->conexion->prepare("
            SELECT ped.id AS numeroPedido, p.nombre, p.marca, pd.cantidad, ped.fecha_alta AS fecha, e.nombre AS estado, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario, u.usuario AS usuario, a.nombre AS nombreArea, ped.descripcion
            FROM pedido ped INNER JOIN usuario u ON ped.idusuario = u.id
                            INNER JOIN pedido_detalle pd ON pd.idpedido = ped.id
                            INNER JOIN producto p ON pd.idproducto = p.id
                            INNER JOIN estado_pedido e ON ped.idestado_pedido = e.id
                            INNER JOIN  area a ON u.idarea = a.id
                            WHERE u.id=? and ped.fecha_alta=? and e.id = 1
                            ORDER BY numeroPedido
                ");
        $consulta->bindParam(1, $idusuario);
        $consulta->bindParam(2, $fecha);
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }

    public function pedidosEntreFechasPorArea($fecha1, $fecha2)
    {
        $consulta = $this->conexion->prepare("
            SELECT a.nombre AS area, pro.nombre AS producto, pro.marca AS marca, COUNT(p.idusuario) AS total
            FROM pedido p, usuario u, area a, producto pro, pedido_detalle pd
            WHERE u.idarea = a.id AND p.idusuario = u.id AND pd.idpedido = p.id
            AND pd.idproducto = pro.id AND p.idestado_pedido = 2 AND p.fecha_alta BETWEEN p.fecha_alta=? AND p.fecha_alta=?
            GROUP BY u.idarea
            ORDER BY total DESC, u.idarea");
        $consulta->bindParam(1, $fecha1);
        $consulta->bindParam(2, $fecha2);
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }

    // Total de productos gastados hasta el momento
    public function totalProductosGastados()
    {
        $consulta = $this->conexion->prepare("
            SELECT p.nombre AS nombreProducto, p.marca AS marcaProducto, SUM(pd.cantidad) AS TOTAL
            FROM pedido_detalle pd, producto p, pedido ped
            WHERE (ped.idestado_pedido = 2) AND (pd.idproducto = p.id) AND (ped.id = pd.idpedido)
            GROUP BY pd.idproducto");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Cantidad de pedidos totales de cada area
    public function totalPedidosPorArea()
    {
        $consulta = $this->conexion->prepare("
            SELECT a.nombre AS area,pro.nombre, pro.marca, SUM(pd.cantidad) AS Cantidad
            FROM pedido_detalle pd, producto pro, pedido p, usuario u, area a
            WHERE pd.idproducto = pro.id AND p.id = pd.idpedido AND p.idusuario = u.id AND u.idarea=a.id AND p.idestado_pedido=2
            GROUP BY a.id, pd.idproducto
            ORDER BY pro.nombre, Cantidad DESC, area");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Cantidad de pedidos totales de cada producto de cada usuario del sistema
    public function totalPedidosPorUsuario()
    {
        $consulta = $this->conexion->prepare("
            SELECT u.nombre , u.apellido, a.nombre AS area,pro.nombre, pro.marca, SUM(pd.cantidad) AS Cantidad
            FROM pedido_detalle pd, producto pro, pedido p, usuario u, area a
            WHERE pd.idproducto = pro.id AND p.id = pd.idpedido AND p.idusuario = u.id AND u.idarea=a.id AND p.idestado_pedido=2
            GROUP BY p.idusuario, pd.idproducto
            ORDER BY Cantidad DESC, pro.nombre, area, u.nombre, u.apellido");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
}
