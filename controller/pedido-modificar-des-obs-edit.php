<?php
// Controlador que realiza la modificación de la descripción o la observación del pedido
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // Datos enviados por el formulario
        $idPedido    = $_POST["id"];
        $descripcion = $_POST["descripcion"];
        $observacion = $_POST["observacion"];

        // Modificación de la descripción o observación
        include "../model/pedido.php";
        $pedido = new Pedido();
        $pedido->modificarDesObs($idPedido, $descripcion, $observacion);

        // Busco el pedido para quedarme con el id del estado del pedido
        $pedidoEncontrado = $pedido->buscarPedido($idPedido);
        $idEstadoPedido   = $pedidoEncontrado[5];

        if ($idEstadoPedido == 1) {
            // incluyo el modelo para traerme el listado
            include "../model/pedido-detalle.php";
            $pedidoDetalle    = new PedidoDetalle();
            $listadoDePedidos = $pedidoDetalle->listarPedidosReservados();
            // Cargo la vista
            echo $twig->render('pedido/listado.html.twig', array(
                'pagina'           => ' - Listado de pedidos reservados',
                'rol'              => $rol,
                'listadoDePedidos' => $listadoDePedidos,
                'tituloPanel'      => 'Reservados',
            ));
        } elseif ($idEstadoPedido == 2) {
            // incluyo el modelo para traerme el listado
            include "../model/pedido-detalle.php";
            $pedidoDetalle    = new PedidoDetalle();
            $listadoDePedidos = $pedidoDetalle->listarPedidosEntregados();
            // Cargo la vista
            echo $twig->render('pedido/listado.html.twig', array(
                'pagina'           => ' - Listado de pedidos entregados',
                'rol'              => $rol,
                'listadoDePedidos' => $listadoDePedidos,
                'tituloPanel'      => 'Entregados',
            ));
        } elseif ($idEstadoPedido == 3) {
            // incluyo el modelo para traerme el listado
            include "../model/pedido-detalle.php";
            $pedidoDetalle    = new PedidoDetalle();
            $listadoDePedidos = $pedidoDetalle->listarPedidosCancelados();
            // Cargo la vista
            echo $twig->render('pedido/listado.html.twig', array(
                'pagina'           => ' - Listado de pedidos cancelados',
                'rol'              => $rol,
                'listadoDePedidos' => $listadoDePedidos,
                'tituloPanel'      => 'Cancelados',
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
