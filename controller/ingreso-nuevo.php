<?php

// Controlador que muestra un formulario para cargar un ingreso al sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2)) {
        // Cargo listado de productos
        include "../model/producto.php";
        $producto           = new Producto();
        $listadoDeProductos = $producto->listarProductos();
        // Fecha actual
        $diaActual = date("d-m-Y");
        // Cargo la vista
        echo $twig->render('ingreso/nuevo.html.twig', array(
            'pagina'             => ' - Nuevo ingreso',
            'rol'                => $rol,
            'diaActual'          => $diaActual,
            'listadoDeProductos' => $listadoDeProductos,
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
