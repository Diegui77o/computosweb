<?php
// Controller que muestra el fomulario para editar usuarios del sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // Si el usuario es administrador
        $id = $_POST["id"];
        include "../model/usuario.php";
        $usuario           = new Usuario();
        $usuarioEncontrado = $usuario->buscarUsuario($id);
        // Listado de áreas
        include "../model/area.php";
        $area           = new Area();
        $listadoDeAreas = $area->listarAreas();
        // Listado de roles
        include "../model/rol.php";
        $nuevoRol       = new Rol();
        $listadoDeRoles = $nuevoRol->listarRoles();
        // Cargo la vista (Pasandole el rol, listado de áreas y roles)
        echo $twig->render('usuario/modificar.html.twig', array(
            'pagina'            => ' - Modificar usuario',
            'rol'               => $rol,
            'listadoDeAreas'    => $listadoDeAreas,
            'listadoDeRoles'    => $listadoDeRoles,
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
