<?php

// Controller que elimina un producto de la base de datos
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // id del producto enviado por el formulario
        $id = $_POST["id"];
        include "../model/producto.php";
        $producto = new Producto();
        $producto->eliminarProducto($id);
        $buscarProducto     = $producto->buscarProducto($id);
        $listadoDeProductos = $producto->listarProductos();
        // Si cuando busco el id no se encuentra es porque fue eliminado
        if ($buscarProducto == null) {
            // Cargo la vista
            echo $twig->render('producto/listado.html.twig', array(
                'pagina'             => ' - Listado de productos',
                'rol'                => $rol,
                'msjExito'           => true,
                'msj'                => 'El producto fue eliminado correctamente!',
                'listadoDeProductos' => $listadoDeProductos,
            ));
        } else {
            // Cargo la vista
            echo $twig->render('producto/listado.html.twig', array(
                'pagina'             => ' - Error',
                'rol'                => $rol,
                'msjError'           => true,
                'msj'                => 'El producto no puede ser eliminado porque posee referencias en otras tablas.',
                'listadoDeProductos' => $listadoDeProductos,
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
