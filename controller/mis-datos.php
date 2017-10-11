<?php

// Controlador que muestra los datos del usuario logueado en el sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        $idusuario = $_SESSION["id"];
        include '../model/usuario.php';
        $usuario           = new Usuario();
        $usuarioEncontrado = $usuario->buscarUsuario($idusuario);
        // Cargo la vista
        echo $twig->render('layout/mis-datos.html.twig', array(
            'pagina'            => ' - Mis datos',
            'rol'               => $rol,
            'usuarioEncontrado' => $usuarioEncontrado,
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
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
