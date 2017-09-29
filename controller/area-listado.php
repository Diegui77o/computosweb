<?php
// Controller que muestra el listado de las áreas del sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        include "../model/area.php";
        $area           = new Area();
        $listadoDeAreas = $area->listarAreas();
        // Cargo la vista y le paso el listado de las áreas del sistema
        echo $twig->render('area/listado.html.twig', array(
            'pagina'         => ' - Listado de áreas',
            'rol'            => $rol,
            'listadoDeAreas' => $listadoDeAreas,
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
