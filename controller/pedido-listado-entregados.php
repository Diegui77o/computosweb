<?php
// Controler que muestra el listado de pedidos entregados del sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2)) {
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
