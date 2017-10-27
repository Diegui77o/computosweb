<?php
// Controlador que muestra la vista para agregar la observación de porque se canceló el pedido
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $idpedido         = $_POST["idpedido"];
        $idpedido_detalle = $_POST["idpedido_detalle"];
        include "../model/pedido-detalle.php";
        $pedidoDetalle           = new PedidoDetalle();
        $pedidoDetalleEncontrado = $pedidoDetalle->visualizarDetallePedido($idpedido);
        // Cargo la vista
        echo $twig->render('pedido/cancelar.html.twig', array(
            'pagina'                  => ' - Pedido cancelado',
            'rol'                     => $rol,
            'pedidoDetalleEncontrado' => $pedidoDetalleEncontrado,
            'idpedido'                => $idpedido,
            'idpedido_detalle'        => $idpedido_detalle,
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
