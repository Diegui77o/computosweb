<?php
// Controlador que muestra el listado de usuarios deshabilitados del sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        include "../model/usuario.php";
        $usuario           = new Usuario();
        $listadoDeUsuarios = $usuario->listarUsuariosDeshabilitados();
        // Cargo la vista y le paso el listado de los usuarios deshabilitados del sistema
        echo $twig->render('usuario/listado.html.twig', array(
            'pagina'            => ' - Listado de usuarios deshabilitados',
            'rol'               => $rol,
            'listadoDeUsuarios' => $listadoDeUsuarios,
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
