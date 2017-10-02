<?php
// Controller que realiza el alta de la nueva área
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        include "../model/area.php";
        // Datos enviados por el formulario
        $areaNueva = $_POST["area"];
        // Creo instancia de area
        $area            = new Area();
        $seEncuentraArea = $area->existeArea($areaNueva);
        if ($seEncuentraArea == null) {
            $area->altaArea($areaNueva);
            // Cargo la vista
            echo $twig->render('area/nueva.html.twig', array(
                'pagina'   => ' - Nueva área',
                'rol'      => $rol,
                'msjExito' => true,
                'msj'      => 'El área fue creada correctamente!',
            ));
        } else {
            // Cargo la vista
            echo $twig->render('area/nueva.html.twig', array(
                'pagina'   => ' - Nueva área',
                'rol'      => $rol,
                'msjError' => true,
                'msj'      => 'El área ya se encuentra creada.',
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
        'rol'         => $rol,
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
