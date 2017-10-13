<?php
// Controlador que muestra un formulario para cambiar el estado y agregar un observación de la reparación
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $id = $_POST["id"];
        include "../model/reparacion.php";
        $reparacion           = new Reparacion();
        $reparacionEncontrada = $reparacion->buscarReparacion($id);
        include "../model/estado-reparacion.php";
        $estadoReparacion        = new EstadoReparacion();
        $listadoEstadoReparacion = $estadoReparacion->listarEstadoReparaciones();
        // Dia actual para el formulario
        $diaActual = date("d-m-Y");
        // Cargo la vista
        echo $twig->render('reparacion/cambiar-estado.html.twig', array(
            'pagina'                  => ' - Cambiar estado',
            'rol'                     => $rol,
            'reparacionEncontrada'    => $reparacionEncontrada,
            'listadoEstadoReparacion' => $listadoEstadoReparacion,
            'diaActual'               => $diaActual,
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
