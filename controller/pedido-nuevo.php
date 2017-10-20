<?php
// Controlador que muestra un formulario para completar el pedido
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        // Listado de prodcutos
        include "../model/producto.php";
        $producto           = new Producto();
        $listadoDeProductos = $producto->listarProductos();
        if ($rol != 3) {
            // Listado de usuarios
            include "../model/usuario.php";
            $usuario           = new Usuario();
            $listadoDeUsuarios = $usuario->listarUsuarios();
            // Cargo la vista
            echo $twig->render('pedido/nuevo.html.twig', array(
                'pagina'             => ' - Nuevo pedido',
                'rol'                => $rol,
                'listadoDeProductos' => $listadoDeProductos,
                'listadoDeUsuarios'  => $listadoDeUsuarios,
            ));
        } else {
            // Cargo la vista
            echo $twig->render('pedido/nuevo.html.twig', array(
                'pagina'             => ' - Nuevo pedido',
                'rol'                => $rol,
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
