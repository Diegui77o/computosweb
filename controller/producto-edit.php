<?php
// Controller que realiza la modificación del producto
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // Datos enviados por el formulario
        $id           = $_POST["id"];
        $nombre       = $_POST["nombre"];
        $marca        = $_POST["marca"];
        $stock_minimo = $_POST["stock_minimo"];
        $fecha        = $_POST["fecha_alta"];
        $fecha_alta   = date("Y-m-d", strtotime($fecha));
        $descripcion  = $_POST["descripcion"];
        // Incluyo el modelo
        include "../model/producto.php";
        $producto            = new Producto();
        $seEncuentraProducto = $producto->existeProducto($nombre, $marca);
        if (($id == $seEncuentraProducto["id"]) or ($seEncuentraProducto == null)) {
            $producto->modificarProducto($nombre, $marca, $descripcion, $stock_minimo, $fecha_alta, $id);
            $listadoDeProductos = $producto->listarProductos();
            // Cargo la vista
            echo $twig->render('producto/listado.html.twig', array(
                'pagina'             => ' - Listado de productos',
                'rol'                => $rol,
                'listadoDeProductos' => $listadoDeProductos,
            ));
        } else {
            $msj                = "Ya existe el producto: " . $nombre . " - " . $marca;
            $productoEncontrado = $producto->buscarProducto($id);
            // Cargo la vista
            echo $twig->render('producto/modificar.html.twig', array(
                'pagina'             => ' - Modificar producto',
                'rol'                => $rol,
                'msjError'           => true,
                'msj'                => $msj,
                'productoEncontrado' => $productoEncontrado,
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
