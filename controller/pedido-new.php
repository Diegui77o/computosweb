<?php
// Controlador que realiza el alta del pedido
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    // Distintos datos dependiendo el rol del usuario
    if ($rol == 1) {
        $idUsuario      = $_POST["usuario"];
        $idEstadoPedido = 2;
        $observacion    = "El pedido se registró en nuestro sistema y se entregó al usuario. Gracias por utilizar nuestro sistema, que tenga buen día!";
    } else {
        $idUsuario      = $_SESSION["id"];
        $idEstadoPedido = 1;
        $observacion    = "El producto se reservará por 5 días o hasta que el usuario pase a retirar por Centro de Cómputos con el fin de no retener el stock por si otro usuario necesita del mismo. Gracias por utilizar nuestro sistema!";
    }
    $idProducto  = $_POST["producto"];
    $fecha_alta  = date("Y-m-d");
    $cantidad    = $_POST["cantidad"];
    $descripcion = $_POST["descripcion"];

    if ($cantidad > 0) {
        include "../model/producto.php";
        $producto           = new Producto();
        $productoEncontrado = $producto->buscarProducto($idProducto);
        $stockActual        = $productoEncontrado["stock"];
        // Si el stock actual es mayor a lo que el usuario pide entra y se registra la operación
        if ($stockActual >= $cantidad) {
            include "../model/pedido.php";
            $pedido = new Pedido();
            // Alta del pedido, me quedo con su id para registrar los detalles del mismo
            $idPedido = $pedido->altaPedido($idUsuario, $fecha_alta, $descripcion, $observacion, $idEstadoPedido);
            // Alta del detalle del pedido
            include "../model/pedido-detalle.php";
            $pedidoDetalle = new PedidoDetalle();
            $pedidoDetalle->altaPedidoDetalle($idPedido, $idProducto, $cantidad);
            // Actualizo el stock del producto
            $total = $stockActual - $cantidad;
            $producto->actualizarStock($total, $idProducto);
            // Listado de productos para pasarlo a la vista y asi el usuario poder seguir cargando pedidos
            $listadoDeProductos = $producto->listarProductos();
            if ($rol != 3) {
                // Si el rol del usuario es administrador o gestor se le pasa el listado de los usuarios para que lo seleccione
                // Listado de usuarios
                include "../model/usuario.php";
                $usuario           = new Usuario();
                $listadoDeUsuarios = $usuario->listarUsuarios();
                $msj               = "El pedido se registro correctamente. El producto " . $productoEncontrado["nombre"] . " actualizo su stock a " . $total . ".";
                // Cargo la vista
                echo $twig->render('pedido/nuevo.html.twig', array(
                    'pagina'             => ' - Nuevo pedido',
                    'rol'                => $rol,
                    'listadoDeProductos' => $listadoDeProductos,
                    'listadoDeUsuarios'  => $listadoDeUsuarios,
                    'msjExito'           => true,
                    'msj'                => $msj,
                ));
            } else {
                // Cargo la vista
                echo $twig->render('pedido/nuevo.html.twig', array(
                    'pagina'             => ' - Nuevo pedido',
                    'rol'                => $rol,
                    'listadoDeProductos' => $listadoDeProductos,
                    'msjExito'           => true,
                    'msj'                => 'El producto se reservará por 5 días o hasta que el usuario pase a retirar por Centro de Cómputos con el fin de no retener el stock por si otro usuario necesita del mismo. Gracias por utilizar nuestro sistema!',
                ));
            }
        } else {
            $listadoDeProductos = $producto->listarProductos();
            if ($rol != 3) {
                // Listado de usuarios
                include "../model/usuario.php";
                $usuario           = new Usuario();
                $listadoDeUsuarios = $usuario->listarUsuarios();
                // Cargo la vista
                $msj = "La cantidad solicitada es mayor al stock que posee el producto. Actualmente el stock del producto es de " . $productoEncontrado["stock"];
                echo $twig->render('pedido/nuevo.html.twig', array(
                    'pagina'             => ' - Nuevo pedido',
                    'rol'                => $rol,
                    'listadoDeProductos' => $listadoDeProductos,
                    'listadoDeUsuarios'  => $listadoDeUsuarios,
                    'msjError'           => true,
                    'msj'                => $msj,
                ));
            } else {
                // Cargo la vista
                $msj = "La cantidad solicitada es mayor al stock que posee el producto. Actualmente el stock del producto " . $productoEncontrado["nombre"] . " (" . $productoEncontrado["marca"] . ") es de " . $productoEncontrado["stock"] . ".";
                echo $twig->render('pedido/nuevo.html.twig', array(
                    'pagina'             => ' - Nuevo pedido',
                    'rol'                => $rol,
                    'listadoDeProductos' => $listadoDeProductos,
                    'msjError'           => true,
                    'msj'                => $msj,
                ));
            }
        }

    } else {
        // Listado de productos
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
                'msjError'           => true,
                'msj'                => 'La cantidad selecciona no puede ser 0.',
            ));
        } else {
            // Cargo la vista
            echo $twig->render('pedido/nuevo.html.twig', array(
                'pagina'             => ' - Nuevo pedido',
                'rol'                => $rol,
                'listadoDeProductos' => $listadoDeProductos,
                'msjError'           => true,
                'msj'                => 'La cantidad selecciona no puede ser 0.',
            ));
        }
    }
} else {
    // Cargo la vista error de sesion
    echo $twig->render('layout/mensaje.html.twig', array(
        'pagina'      => ' - Mensaje del sistema',
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
