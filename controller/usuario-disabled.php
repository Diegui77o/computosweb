<?php

// Controller que deshabilita al usuario
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // id enviado por el formulario del listado de usuarios
        $id = $_POST["id"];
        // Creo instancia del usuario
        include "../model/usuario.php";
        $usuario = new Usuario();
        $usuario->deshabilitarUsuario($id);
        $listadoDeUsuarios = $usuario->listarUsuarios();
        // Cargo la vista (Pasandole el listado de los usuarios)
        echo $twig->render('usuario/listado.html.twig', array(
            'pagina'            => ' - Listado de usuarios',
            'rol'               => $rol,
            'listadoDeUsuarios' => $listadoDeUsuarios,
            'msjExito'          => true,
            'msj'               => 'El usuario fue deshabilitado correctamente!',
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
