<?php

// Controller que realiza la modificación del área
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $idarea     = $_POST["id"];
        $nombreArea = $_POST["area"];

        include "../model/area.php";
        $area            = new Area();
        $seEncuentraArea = $area->existeArea($nombreArea);

        if ($seEncuentraArea == null) {
            $area->modificarArea($nombreArea, $idarea);
            $listadoDeAreas = $area->listarAreas();
            // Cargo la vista y le paso el listado de las áreas del sistema
            echo $twig->render('area/listado.html.twig', array(
                'pagina'         => ' - Listado de áreas',
                'rol'            => $rol,
                'listadoDeAreas' => $listadoDeAreas,
                'msjExito'       => true,
                'msj'            => 'El área se modificado correctamente!',
            ));
        } else {
            $areaEncontrada = $area->buscarArea($idarea);
            $msj            = "Ya existe un área llamada " . $nombreArea . " en el sistema.";
            // Cargo la vista
            echo $twig->render('area/modificar.html.twig', array(
                'pagina'         => ' - Modificar área',
                'rol'            => $rol,
                'areaEncontrada' => $areaEncontrada,
                'msjError'       => true,
                'msj'            => $msj,
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
