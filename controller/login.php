<?php
// Controller utilizado para el login de los usuarios
require_once 'cargaTwig.php';
require_once 'encriptador.php';
include "../model/usuario.php";
// Datos enviados por el fomulario de login
$usuario = $_POST["user"];
$pass    = $_POST["pass"];
// Creo instancia de persona (usuario)
$persona           = new Usuario();
$datos             = $persona->existeUsuario($usuario);
$passEnLaBase      = $datos["clave"];
$passDesencriptada = Encrypter::decrypt($passEnLaBase);
if (!($datos == null)) {
    if ($passDesencriptada == $pass) {
        // La consulta encontro al usuario
        $id  = $datos["id"];
        $rol = $datos["idrol"];
        // Si está habilitado me guardo datos de la sesion
        if ($datos["habilitado"] == 1) {
            session_start();
            $_SESSION["id"]      = $id;
            $_SESSION["usuario"] = $usuario;
            $_SESSION["rol"]     = $rol;
            include "index.php";
        } else {
            // Muestra msj error si el usuario está deshabilitado
            echo $twig->render('layout/base.html.twig', array(
                'pagina'   => ' - Error',
                'msjError' => true,
                'msj'      => 'Usuario deshabilitado. Contactese con el centro de cómputos.',
            ));
        }
    } else {
        // Por seguridad si las contraseñas no coinciden, muestra msj usuario o contraseña incorrectos
        echo $twig->render('layout/base.html.twig', array(
            'pagina'   => ' - Error',
            'msjError' => true,
            'msj'      => 'Usuario o contraseña incorrectos.',
        ));
    }
} else {
    // Por seguridad si las contraseñas no coinciden, muestra msj usuario o contraseña incorrectos
    echo $twig->render('layout/base.html.twig', array(
        'pagina'   => ' - Error',
        'msjError' => true,
        'msj'      => 'Usuario o contraseña incorrectos.',
    ));
}
