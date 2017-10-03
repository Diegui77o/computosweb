<?php
// Controller que realiza el alta del producto
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // Datos enviados por el formulario
        $nombre            = $_POST["nombre"];
        $marca             = $_POST["marca"];
        $stock             = 0;
        $stock_minimo      = $_POST["stock_minimo"];
        $descripcion       = $_POST["descripcion"];
        $fecha             = $_POST["fecha_alta"];
        $fecha_alta        = date("Y-m-d", strtotime($fecha));
        $fecha_actualizado = $fecha_alta;
        //$diaActual         = date("Y-m-d"); // funcion que retorna el dia actual
        // Incluyo y creo instancia de producto
        include "../model/producto.php";
        $producto            = new Producto();
        $seEncuentraProducto = $producto->existeProducto($nombre, $marca);

        if ($seEncuentraProducto == null) {
            if ($stock >= 0) {
                $producto->altaProducto($nombre, $marca, $stock, $stock_minimo, $descripcion, $fecha_alta, $fecha_actualizado);
                // Cargo la vista
                echo $twig->render('producto/nuevo.html.twig', array(
                    'pagina'   => ' - Nuevo producto',
                    'rol'      => $rol,
                    'msjExito' => true,
                    'msj'      => 'El producto fue creado correctamente!',
                ));
            } else {
                // Cargo la vista
                echo $twig->render('producto/nuevo.html.twig', array(
                    'pagina'   => ' - Nuevo producto',
                    'rol'      => $rol,
                    'msjError' => true,
                    'msj'      => 'El stock del producto no puede ser menor a 0.',
                ));
            }
        } else {
            $msj = "Ya existe el producto: " . $nombre . " - " . $marca . ".";
            // Cargo la vista
            echo $twig->render('producto/nuevo.html.twig', array(
                'pagina'   => ' - Nuevo producto',
                'rol'      => $rol,
                'msjError' => true,
                'msj'      => $msj,
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
        'rol'         => $rol,
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
