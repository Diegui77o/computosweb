<?php

require_once "db.php";

class Reparacion
{
    public $id;
    public $idusuario;
    public $fecha_ingreso;
    public $descripcion;
    public $idestado_reparacion;
    public $observacion;
    public $fecha_salida;
    public $conexion;
    // Contructor
    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    // Alta
    public function altaReparacion($idusuario, $fecha_ingreso, $descripcion, $idestado_reparacion, $observacion, $fecha_salida)
    {
        $consulta = $this->conexion->prepare("INSERT INTO reparacion (idusuario, fecha_ingreso, descripcion, idestado_reparacion, observacion, fecha_salida) VALUES (:idusuario, :fecha_ingreso, :descripcion, :idestado_reparacion, :observacion, :fecha_salida)");
        $consulta->bindParam(':idusuario', $idusuario);
        $consulta->bindParam(':fecha_ingreso', $fecha_ingreso);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->bindParam(':descripcion', $descripcion);
        $consulta->bindParam(':idestado_reparacion', $idestado_reparacion);
        $consulta->bindParam(':observacion', $observacion);
        $consulta->bindParam(':fecha_salida', $fecha_salida);
        $consulta->execute();
    }
    // Baja
    public function eliminarReparacion($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM reparacion WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Buscar reparacion por su id
    public function buscarReparacion($id)
    {
        $consulta = $this->conexion->prepare("SELECT r.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoReparacion, a.nombre AS nombreArea
                                            FROM reparacion r
                                            INNER JOIN usuario u ON r.idusuario = u.id
                                            INNER JOIN estado_reparacion e ON r.idestado_reparacion=e.id
											INNER JOIN area a ON a.id = u.idarea
                                            WHERE r.id=?");
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
    // Listado de reparaciones
    public function listarReparaciones()
    {
        $consulta = $this->conexion->prepare("SELECT r.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoReparacion
                                            FROM reparacion r
                                            INNER JOIN usuario u ON r.idusuario = u.id
                                            INNER JOIN estado_reparacion e ON r.idestado_reparacion=e.id
                                            ORDER BY r.fecha_ingreso DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de reparaciones pendientes
    public function listarReparacionesPendientes()
    {
        $consulta = $this->conexion->prepare("SELECT r.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoReparacion
                                            FROM reparacion r
                                            INNER JOIN usuario u ON r.idusuario = u.id
                                            INNER JOIN estado_reparacion e ON r.idestado_reparacion=e.id
                                            WHERE r.idestado_reparacion=1
                                            ORDER BY r.fecha_ingreso DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de reparaciones en reparacion
    public function listarReparacionesReparando()
    {
        $consulta = $this->conexion->prepare("SELECT r.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoReparacion
                                            FROM reparacion r
                                            INNER JOIN usuario u ON r.idusuario = u.id
                                            INNER JOIN estado_reparacion e ON r.idestado_reparacion=e.id
                                            WHERE r.idestado_reparacion=2
                                            ORDER BY r.fecha_ingreso DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de reparaciones listas
    public function listarReparacionesListas()
    {
        $consulta = $this->conexion->prepare("SELECT r.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoReparacion
                                            FROM reparacion r
                                            INNER JOIN usuario u ON r.idusuario = u.id
                                            INNER JOIN estado_reparacion e ON r.idestado_reparacion=e.id
                                            WHERE r.idestado_reparacion=3
                                            ORDER BY r.fecha_ingreso DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de reparaciones sin arreglo
    public function listarReparacionesSinArreglo()
    {
        $consulta = $this->conexion->prepare("SELECT r.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoReparacion
                                            FROM reparacion r
                                            INNER JOIN usuario u ON r.idusuario = u.id
                                            INNER JOIN estado_reparacion e ON r.idestado_reparacion=e.id
                                            WHERE r.idestado_reparacion=4
                                            ORDER BY r.fecha_ingreso DESC");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado mis reparaciones para cada usuario
    public function misReparaciones($id)
    {
        $consulta = $this->conexion->prepare("SELECT r.*, u.usuario AS usuario, u.nombre AS nombreUsuario, u.apellido AS apellidoUsuario,
                                            e.nombre AS estadoReparacion
                                            FROM reparacion r
                                            INNER JOIN usuario u ON r.idusuario = u.id
                                            INNER JOIN estado_reparacion e ON r.idestado_reparacion=e.id
                                            WHERE r.idusuario=?
                                            ORDER BY r.fecha_ingreso DESC");
        $consulta->bindParam(1, $id);
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Actualizar datos de la reparacion (Administrador)
    public function actualizarEstado($id, $idestado_reparacion, $observacion, $fecha_salida)
    {
        $consulta = $this->conexion->prepare("UPDATE reparacion SET idestado_reparacion=?, observacion=?, fecha_salida=? WHERE id=?");
        $consulta->bindParam(1, $idestado_reparacion);
        $consulta->bindParam(2, $observacion);
        $consulta->bindParam(3, $fecha_salida);
        $consulta->bindParam(4, $id);
        $consulta->execute();
    }
    // Cantidad de reparacones pendientes (Notificaciones)
    public function cantidadReparacionesPendientes()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM reparacion WHERE idestado_reparacion=1");
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 0) {
            $resultado = 0;
        } else {
            $resultado = $count;
        }
        return $resultado;
    }
    // Cantidad de reparaciones en reparacion (Notificaciones)
    public function cantidadReparacionesEnReparacion()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM reparacion WHERE idestado_reparacion=2");
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 0) {
            $resultado = 0;
        } else {
            $resultado = $count;
        }
        return $resultado;
    }
}
