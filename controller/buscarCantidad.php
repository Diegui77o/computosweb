<?php
require_once "cargaTwig.php";

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        // DATOS ENVIADOS POR POST DESDE AJAX
        $cantidadSolicitada = $_POST['valorCantidad'];
        $id                 = $_POST["idProducto"];
        // BUSQUEDA DEL PRODUCTO
        include "../model/producto.php";
        $producto           = new Producto();
        $productoEncontrado = $producto->buscarProducto($id);

        $stockActual = $productoEncontrado["stock"];
        $mensaje     = "";

        if ($cantidadSolicitada > 0) {
            if (($stockActual - $cantidadSolicitada) >= 0) {
                $mensaje = "El producto posee el stock solicitado!";
            } else {
                $mensaje = "No hay stock del producto!";
            }
        }
        echo $mensaje;
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
