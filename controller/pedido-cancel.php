<?php
// Controlador que cancela el pedido, agrega observación y actualiza el stock del producto
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $idpedido         = $_POST["idpedido"];
        $idpedido_detalle = $_POST["idpedido_detalle"];
        $observacion      = $_POST["observacion"];
        $idestado_pedido  = 3; // Cancelado
        include "../model/pedido-detalle.php";
        $pedidoDetalle = new PedidoDetalle();
        $cantidad      = $pedidoDetalle->devolverCantidad($idpedido_detalle);
        $idproducto    = $pedidoDetalle->devolverIdProducto($idpedido_detalle);

        // Aactualizo estado del pedido y agrego una observación de cancelación
        include "../model/pedido.php";
        $pedido = new Pedido();
        $pedido->actualizarEstado($idpedido, $idestado_pedido, $observacion);

        // Actualizo el stock del producto
        include "../model/producto.php";
        $producto           = new Producto();
        $productoActualizar = $producto->buscarProducto($idproducto);
        $stockActual        = $productoActualizar["stock"];
        $productoNombre     = $productoActualizar["nombre"];

        $total              = $stockActual + $cantidad;
        $productoActualizar = $producto->actualizarStock($total, $idproducto);

        $listadoDePedidos = $pedidoDetalle->listarPedidosReservados();
        // Cargo la vista
        $msj = "El pedido se canceló correctamente. El producto " . $productoNombre . " actualizó su stock a " . $total . ".";
        echo $twig->render('pedido/listado.html.twig', array(
            'pagina'           => ' - Listado de pedidos reservados',
            'rol'              => $rol,
            'listadoDePedidos' => $listadoDePedidos,
            'tituloPanel'      => 'Reservados',
            'msjExito'         => true,
            'msj'              => $msj,
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
