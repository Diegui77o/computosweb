<?php
// Controller que elimina un área de la base de datos
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // id del área enviado por el formulario
        $id = $_POST["id"];
        // Incluyo el modelo y creo una instancia
        include "../model/area.php";
        $area = new Area();
        $area->eliminarArea($id);
        // Listado de áreas
        $listadoDeAreas = $area->listarAreas();
        // Busco si el área fue eliminada
        $areaEncontrada = $area->buscarArea($id);
        if ($areaEncontrada == null) {
            // Cargo la vista (Pasandole el listado de las áreas)
            echo $twig->render('area/listado.html.twig', array(
                'pagina'         => ' - Listado de áreas',
                'rol'            => $rol,
                'listadoDeAreas' => $listadoDeAreas,
                'msjExito'       => true,
                'msj'            => 'El área fue eliminada correctamente!',
            ));
        } else {
            // Cargo la vista (Pasandole el listado de las áreas)
            echo $twig->render('area/listado.html.twig', array(
                'pagina'         => ' - Listado de áreas',
                'rol'            => $rol,
                'listadoDeAreas' => $listadoDeAreas,
                'msjError'       => true,
                'msj'            => 'El área no puede ser eliminada porque posee referencias en otras tablas.',
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
