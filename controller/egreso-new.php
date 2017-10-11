<?php
// Controlador que realiza el alta del egreso y actualiza el stock del producto
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2)) {
        $producto    = $_POST["producto"];
        $cantidad    = $_POST["cantidad"];
        $area        = $_POST["area"];
        $fecha       = $_POST["fecha_alta"];
        $fecha_alta  = date("Y-m-d", strtotime($fecha));
        $descripcion = $_POST["descripcion"];

        if ($cantidad > 0) {
            include "../model/producto.php";
            $productoActualizar  = new Producto();
            $seEncuentraProducto = $productoActualizar->buscarProducto($producto);
            $stockActual         = $seEncuentraProducto["stock"];
            if ($seEncuentraProducto != null) {
                // Creo instancia de egreso
                include "../model/egreso.php";
                $egreso = new Egreso();

                if ($stockActual >= $cantidad) {
                    $egreso->altaEgreso($producto, $area, $cantidad, $fecha_alta, $descripcion);
                    $total = $stockActual - $cantidad;
                    $productoActualizar->actualizarStock($total, $producto);
                    // Listado de productos
                    $listadoDeProductos = $productoActualizar->listarProductos();
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
                        'msjExito'           => true,
                        'msj'                => 'El egreso se registro correctamente!',
                    ));
                } else {
                    // Listado de productos
                    $listadoDeProductos = $productoActualizar->listarProductos();
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
                        'msjError'           => true,
                        'msj'                => 'El producto no posee stock suficiente para realizar la operación!',
                    ));
                }
            } else {
                // Listado de productos
                $listadoDeProductos = $productoActualizar->listarProductos();
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
                    'msjError'           => true,
                    'msj'                => 'El producto no existe!',
                ));
            }
        } else {
            // Listado de productos
            $listadoDeProductos = $productoActualizar->listarProductos();
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
                'msjError'           => true,
                'msj'                => 'La cantidad solicitada debe ser mayor a 0!',
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
