<?php
// Controlador que realiza la baja de la reparación
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $id = $_POST["id"];
        include "../model/reparacion.php";
        $reparacion           = new Reparacion();
        $reparacionEncontrada = $reparacion->buscarReparacion($id);
        $idEstadoReparacion   = $reparacionEncontrada["idestado_reparacion"];
        $reparacion->eliminarReparacion($id);
        // Dependiendo el estado de la reparacion muestra vista con listado y su estado correspondiente
        if ($idEstadoReparacion == 1) {
            $listadoDeReparaciones = $reparacion->listarReparacionesPendientes();
            // Cargo la vista y le paso el listado de las reparaciones pendientes del sistema
            echo $twig->render('reparacion/listado.html.twig', array(
                'pagina'                => ' - Listado de reparaciones pendientes',
                'rol'                   => $rol,
                'listadoDeReparaciones' => $listadoDeReparaciones,
                'tituloPanel'           => 'Pendientes',
                'msjExito'              => true,
                'msj'                   => 'La reparación se eliminó correctamente.',
            ));
        } elseif ($idEstadoReparacion == 2) {
            $listadoDeReparaciones = $reparacion->listarReparacionesReparando();
            // Cargo la vista y le paso el listado de las reparaciones en reparación del sistema
            echo $twig->render('reparacion/listado.html.twig', array(
                'pagina'                => ' - Listado de reparaciones en reparación',
                'rol'                   => $rol,
                'listadoDeReparaciones' => $listadoDeReparaciones,
                'tituloPanel'           => 'En reparación',
                'msjExito'              => true,
                'msj'                   => 'La reparación se eliminó correctamente.',
            ));
        } elseif ($idEstadoReparacion == 3) {
            $listadoDeReparaciones = $reparacion->listarReparacionesListas();
            // Cargo la vista y le paso el listado de las reparaciones listas del sistema
            echo $twig->render('reparacion/listado.html.twig', array(
                'pagina'                => ' - Listado de reparaciones listas',
                'rol'                   => $rol,
                'listadoDeReparaciones' => $listadoDeReparaciones,
                'tituloPanel'           => 'Listas',
                'msjExito'              => true,
                'msj'                   => 'La reparación se eliminó correctamente.',
            ));
        } elseif ($idEstadoReparacion == 4) {
            $listadoDeReparaciones = $reparacion->listarReparacionesSinArreglo();
            // Cargo la vista y le paso el listado de las reparaciones sin arreglo del sistema
            echo $twig->render('reparacion/listado.html.twig', array(
                'pagina'                => ' - Listado de reparaciones sin arreglo',
                'rol'                   => $rol,
                'listadoDeReparaciones' => $listadoDeReparaciones,
                'tituloPanel'           => 'Sin arreglo',
                'msjExito'              => true,
                'msj'                   => 'La reparación se eliminó correctamente.',
            ));
        }
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
