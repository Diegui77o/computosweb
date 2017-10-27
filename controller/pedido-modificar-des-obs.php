<?php
// Controlador (solo para usuario administrador) se encarga de mostrar un formulario para modificar la descripción y observación del pedido por parte del usuario
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        $idPedidoDetalle = $_POST["idpedido_detalle"];

        include "../model/pedido-detalle.php";
        $pedidoDetalle = new PedidoDetalle();
        // Obtengo el id del pedido
        $idPedido = $pedidoDetalle->devolverIdPedido($idPedidoDetalle);
        // Me traigo todos los datos del pedido y su detalle
        $pedido = $pedidoDetalle->visualizarDetallePedido($idPedido);
        // Cargo la vista
        echo $twig->render('pedido/modificar-des-obs.html.twig', array(
            'pagina' => ' - Modificar descripción/observación',
            'rol'    => $rol,
            'pedido' => $pedido,
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
