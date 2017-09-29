<?php

require_once "db.php";

class Usuario
{
    public $id;
    public $usuario;
    public $clave;
    public $nombre;
    public $apellido;
    public $telefono;
    public $email;
    public $habilitado;
    public $idrol;
    public $idarea;
    public $conexion;
    // Constructor
    public function __construct()
    {
        $this->conexion = $conexion = new Conexion();
        $this->conexion = $conexion->conectar();
    }
    /*
     * Alta, baja y modificación del usuario *
     */
    public function altaUsuario($usuario, $clave, $nombre, $apellido, $telefono, $email, $habilitado, $idrol, $idarea)
    {
        $consulta = $this->conexion->prepare("INSERT INTO usuario (usuario, clave, nombre, apellido, telefono, email, habilitado, idrol, idarea) VALUES (:usuario, :clave, :nombre, :apellido, :telefono, :email, :habilitado, :idrol, :idarea)");
        $consulta->bindParam(':usuario', $usuario);
        $consulta->bindParam(':clave', $clave);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':apellido', $apellido);
        $consulta->bindParam(':telefono', $telefono);
        $consulta->bindParam(':email', $email);
        $consulta->bindParam(':habilitado', $habilitado);
        $consulta->bindParam(':idrol', $idrol);
        $consulta->bindParam(':idarea', $idarea);
        $consulta->execute();
    }
    // Baja
    public function eliminarUsuario($id)
    {
        $consulta = $this->conexion->prepare("DELETE FROM usuario WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Modificar usuario
    public function modificarUsuario($usuario, $clave, $nombre, $apellido, $telefono, $email, $idrol, $idarea, $id)
    {
        $consulta = $this->conexion->prepare("UPDATE usuario SET usuario=?, clave=?, nombre=?, apellido=?, telefono=?, email=?, idrol=?, idarea=? WHERE id=?");
        $consulta->bindParam(1, $usuario);
        $consulta->bindParam(2, $clave);
        $consulta->bindParam(3, $nombre);
        $consulta->bindParam(4, $apellido);
        $consulta->bindParam(5, $telefono);
        $consulta->bindParam(6, $email);
        $consulta->bindParam(7, $idrol);
        $consulta->bindParam(8, $idarea);
        $consulta->bindParam(9, $id);
        $consulta->execute();
    }
    // Listado de usuarios
    public function listarUsuarios()
    {
        $consulta = $this->conexion->prepare("SELECT u.*, r.nombre AS nombreRol, a.nombre AS nombreArea
                                            FROM usuario u
                                            INNER JOIN rol r ON u.idrol=r.id
                                            INNER JOIN area a ON u.idarea=a.id
                                            ORDER BY u.usuario, u.nombre, u.apellido");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de usuarios habilitados
    public function listarUsuariosHabilitados()
    {
        $consulta = $this->conexion->prepare("SELECT u.*, r.nombre AS nombreRol, a.nombre AS nombreArea
                                            FROM usuario u
                                            INNER JOIN rol r ON u.idrol=r.id
                                            INNER JOIN area a ON u.idarea=a.id
                                            WHERE u.habilitado = 1
                                            ORDER BY u.usuario, u.nombre, u.apellido");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Listado de usuarios deshabilitados
    public function listarUsuariosDeshabilitados()
    {
        $consulta = $this->conexion->prepare("SELECT u.*, r.nombre AS nombreRol, a.nombre AS nombreArea
                                            FROM usuario u
                                            INNER JOIN rol r ON u.idrol=r.id
                                            INNER JOIN area a ON u.idarea=a.id
                                            WHERE u.habilitado = 0
                                            ORDER BY u.usuario, u.nombre, u.apellido");
        $consulta->execute();
        $datos = $consulta->fetchAll();
        return $datos;
    }
    // Habilitar usuario
    public function habilitarUsuario($id)
    {
        $consulta = $this->conexion->prepare("UPDATE usuario SET habilitado=1 WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Deshabilitar usuario
    public function deshabilitarUsuario($id)
    {
        $consulta = $this->conexion->prepare("UPDATE usuario SET habilitado=0 WHERE id=?");
        $consulta->bindParam(1, $id);
        $consulta->execute();
    }
    // Verifica si el usuario existe en la base de datos
    public function existeUsuario($usuario)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM usuario WHERE usuario=?");
        $consulta->bindParam(1, $usuario);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }

    // Verifica si el nombre del usuario se encuentra en la base de datos
    public function existeNombreUsuario($usuario)
    {
        $consulta = $this->conexion->prepare("SELECT * FROM usuario WHERE usuario=?");
        $consulta->bindParam(1, $usuario);
        $consulta->execute();
        $count = $consulta->rowcount();
        if ($count == 1) {
            $datos = $consulta->fetch();
        } else {
            $datos = null;
        }
        return $datos;
    }
    // Busca un usuario en la base según un id
    public function buscarUsuario($id)
    {
        $consulta = $this->conexion->prepare("SELECT u.*, r.nombre AS nombreRol, a.nombre AS nombreArea
                                            FROM usuario u
                                            INNER JOIN rol r ON u.idrol=r.id
                                            INNER JOIN area a ON u.idarea=a.id
                                            WHERE u.id=?");
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
    // Cambiar contraseña (Cada usuario puede cambiar la suya)
    public function cambiarClave($clave, $id)
    {
        $consulta = $this->conexion->prepare("UPDATE usuario SET clave=? WHERE id=?");
        $consulta->bindParam(1, $clave);
        $consulta->bindParam(2, $id);
        $consulta->execute();
    }
    // Cambiar datos del usuario (Cada usuario puede cambiar sus datos)
    public function modificarDatos($nombre, $apellido, $telefono, $email, $idarea, $id)
    {
        $consulta = $this->conexion->prepare("UPDATE usuario SET nombre=?, apellido=?, telefono=?, email=?, idarea=? WHERE id=?");
        $consulta->bindParam(1, $nombre);
        $consulta->bindParam(2, $apellido);
        $consulta->bindParam(3, $telefono);
        $consulta->bindParam(4, $email);
        $consulta->bindParam(5, $idarea);
        $consulta->bindParam(6, $id);
        $consulta->execute();
    }
    // Cantidad de usuarios pendientes (Notificacion)
    public function cantidadUsuariosPendientes()
    {
        $consulta = $this->conexion->prepare("SELECT * FROM usuario WHERE habilitado=0");
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
