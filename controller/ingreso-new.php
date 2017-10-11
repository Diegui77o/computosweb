<?php
// Controlador que realiza el ingreso del producto al sistema para actualizar su stock
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2)) {
        $producto    = $_POST["producto"];
        $cantidad    = $_POST["cantidad"];
        $fecha       = $_POST["fecha_alta"];
        $fecha_alta  = date("Y-m-d", strtotime($fecha));
        $descripcion = $_POST["descripcion"];

        if ($cantidad > 0) {
            include "../model/producto.php";
            $productoActualizar  = new Producto();
            $seEncuentraProducto = $productoActualizar->buscarProducto($producto);

            if ($seEncuentraProducto != null) {
                // Agrego el ingreso a su tabla
                include "../model/ingreso.php";
                $ingreso = new Ingreso();
                $ingreso->altaIngreso($producto, $cantidad, $fecha_alta, $descripcion);
                // Actualizo el stock del producto
                $stockActual = $seEncuentraProducto["stock"];
                $total       = $stockActual + $cantidad;
                $productoActualizar->actualizarStock($total, $producto);
                // Listado de productos con la nueva actualización del stock del producto
                $listadoDeProductos = $productoActualizar->listarProductos();
                // Fecha actual
                $diaActual = date("d-m-Y");
                // Actualizo la fecha_actualizado del producto
                $productoActualizar->ultimaActualizacion($fecha_alta, $producto);
                // Cargo la vista
                echo $twig->render('ingreso/nuevo.html.twig', array(
                    'pagina'             => ' - Nuevo ingreso',
                    'rol'                => $rol,
                    'diaActual'          => $diaActual,
                    'listadoDeProductos' => $listadoDeProductos,
                    'msjExito'           => true,
                    'msj'                => 'El ingreso se registro correctamente!',
                ));
            } else {
                $listadoDeProductos = $productoActualizar->listarProductos();
                // Fecha actual
                $diaActual = date("d-m-Y");
                // Cargo la vista
                echo $twig->render('ingreso/nuevo.html.twig', array(
                    'pagina'             => ' - Nuevo ingreso',
                    'rol'                => $rol,
                    'diaActual'          => $diaActual,
                    'listadoDeProductos' => $listadoDeProductos,
                    'msjError'           => true,
                    'msj'                => 'El producto no existe!',
                ));
            }
        } else {
            $listadoDeProductos = $productoActualizar->listarProductos();
            // Fecha actual
            $diaActual = date("d-m-Y");
            // Cargo la vista
            echo $twig->render('ingreso/nuevo.html.twig', array(
                'pagina'             => ' - Nuevo ingreso',
                'rol'                => $rol,
                'diaActual'          => $diaActual,
                'listadoDeProductos' => $listadoDeProductos,
                'msjError'           => true,
                'msj'                => 'la cantidad de stock que debe ingresar tiene que ser mayor a 0',
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
