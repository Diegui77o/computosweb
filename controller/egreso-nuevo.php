<?php
// Controlador que muestra un formulario para el egreso del producto
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2)) {
        // Listado de productos
        include "../model/producto.php";
        $producto           = new Producto();
        $listadoDeProductos = $producto->listarProductos();
        // Listado de areas
        include "../model/area.php";
        $area           = new Area();
        $listadoDeAreas = $area->listarAreas();
        // Fecha actual para el formulario
        $diaActual = date("d-m-Y");
        // Cargo la vista
        echo $twig->render('egreso/nuevo.html.twig', array(
            'pagina'             => ' - Nuevo egreso',
            'rol'                => $rol,
            'diaActual'          => $diaActual,
            'listadoDeProductos' => $listadoDeProductos,
            'listadoDeAreas'     => $listadoDeAreas,
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
