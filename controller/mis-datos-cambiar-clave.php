<?php

// Controlador que muestra un formulario para poder cambiar la contraseña del usuario
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        $id = $_POST["id"];
        include '../model/usuario.php';
        $usuario           = new Usuario();
        $usuarioEncontrado = $usuario->buscarUsuario($id);
        // Cargo la vista
        echo $twig->render('layout/mis-datos-cambiar-clave.html.twig', array(
            'pagina'            => ' - Cambiar contraseña',
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
        'rol'         => $rol,
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
