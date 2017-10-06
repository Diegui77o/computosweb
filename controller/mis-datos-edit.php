<?php
// Controlador que modifica los datos del usuario
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        $id       = $_POST["id"];
        $nombre   = $_POST["name"];
        $apellido = $_POST["lastname"];
        $telefono = $_POST["telefono"];
        $email    = $_POST["email"];
        $area     = $_POST["area"];
        // Usuario
        include "../model/usuario.php";
        $usuario = new Usuario();
        $usuario->modificarDatos($nombre, $apellido, $telefono, $email, $area, $id);
        $usuarioEncontrado = $usuario->buscarUsuario($id);
        // Cargo la vista
        echo $twig->render('layout/mis-datos.html.twig', array(
            'pagina'            => ' - Mis datos',
            'rol'               => $rol,
            'usuarioEncontrado' => $usuarioEncontrado,
            'msjExito'          => true,
            'msj'               => 'Los datos han sido actualizados, muchas gracias!',
        ));
    } else {
        // Cargo la vista error permisos
        echo $twig->render('layout/mensaje.html.twig', array(
            'pagina'      => ' - Mensaje del sistema',
            'rol'         => $rol,
            'tipoDePanel' => 'panel-danger',
            'msj'         => 'Error! No posee los permisos necesarios para realizar está operación.',
        ));
    }
} else {
    // Cargo la vista error de sesion
    echo $twig->render('layout/mensaje.html.twig', array(
        'pagina'      => ' - Mensaje del sistema',
        'rol'         => $rol,
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
