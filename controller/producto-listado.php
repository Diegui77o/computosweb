<?php
// Controlador que muestra el listado de los productos del sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // Incluto modelo para obtener el listado
        include "../model/producto.php";
        $producto           = new Producto();
        $listadoDeProductos = $producto->listarProductos();
        // Cargo la vista
        echo $twig->render('producto/listado.html.twig', array(
            'pagina'             => ' - Listado de productos',
            'rol'                => $rol,
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
