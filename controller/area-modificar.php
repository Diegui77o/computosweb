<?php
// Controlador que muestra un formulario para modificar un área
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // id del area enviado por el formulario
        $id = $_POST["id"];
        include "../model/area.php";
        $area           = new Area();
        $areaEncontrada = $area->buscarArea($id);
        // Cargo la vista
        echo $twig->render('area/modificar.html.twig', array(
            'pagina'         => ' - Modificar área',
            'rol'            => $rol,
            'areaEncontrada' => $areaEncontrada,
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
