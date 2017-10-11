<?php

// Controller que muestra un formulario para poder editar un producto determinado
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $id = $_POST["id"];
        include "../model/producto.php";
        $producto           = new Producto();
        $productoEncontrado = $producto->buscarProducto($id);
        // Cargo la vista
        echo $twig->render('producto/modificar.html.twig', array(
            'pagina'             => ' - Modificar producto',
            'rol'                => $rol,
            'productoEncontrado' => $productoEncontrado,
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
