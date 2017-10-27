<?php
// Controlador que confirma el pedido
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $idPedido       = $_POST["idpedido"];
        $idEstadoPedido = 2;
        $observacion    = "El pedido se registró en nuestro sistema y se entregó al usuario. Gracias por utilizar nuestro sistema, que tenga buen día!";
        include "../model/pedido.php";
        $pedido = new Pedido();
        $pedido->actualizarEstado($idPedido, $idEstadoPedido, $observacion);
        // incluyo el modelo del detalle del pedido para traerme el listado de reservados
        include "../model/pedido-detalle.php";
        $pedidoDetalle    = new PedidoDetalle();
        $listadoDePedidos = $pedidoDetalle->listarPedidosReservados();
        // Cargo la vista
        echo $twig->render('pedido/listado.html.twig', array(
            'pagina'           => ' - Listado de pedidos reservados',
            'rol'              => $rol,
            'listadoDePedidos' => $listadoDePedidos,
            'tituloPanel'      => 'Reservados',
            'msjExito'         => true,
            'msj'              => 'El pedido se registró en nuestro sistema y se entregó al usuario.',
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
