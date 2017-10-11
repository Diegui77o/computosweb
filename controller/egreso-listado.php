<?php
// Controller que muestra el listado de egresos del sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2)) {
        include "../model/egreso.php";
        $egreso           = new Egreso();
        $listadoDeEgresos = $egreso->listarEgresos();
        // Cargo la vista
        echo $twig->render('egreso/listado.html.twig', array(
            'pagina'           => ' - Listado de egresos',
            'rol'              => $rol,
            'listadoDeEgresos' => $listadoDeEgresos,
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
