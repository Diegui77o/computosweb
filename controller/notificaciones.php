<?php
// Controlador que muestra la vista de las notificaciones necesarias del sistema
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // Usuarios pendientes
        include "../model/usuario.php";
        $usuario                      = new Usuario();
        $cantidadDeUsuariosPendientes = $usuario->cantidadUsuariosPendientes();
        // Cantidad de pedidos pendientes o reservados
        include "../model/pedido.php";
        $pedido                      = new Pedido();
        $cantidadDePedidosPendientes = $pedido->cantidadPedidosReservados();
        // Reparaciones pendientes
        include "../model/reparacion.php";
        $reparacion                         = new Reparacion();
        $cantidadDeReparacionesPendientes   = $reparacion->cantidadReparacionesPendientes();
        $cantidadDeReparacionesEnReparacion = $reparacion->cantidadReparacionesEnReparacion();
        // Cargo la vista
        echo $twig->render('layout/notificaciones.html.twig', array(
            'pagina'                             => ' - Notificaciones',
            'rol'                                => $rol,
            'cantidadDeUsuariosPendientes'       => $cantidadDeUsuariosPendientes,
            'cantidadDePedidosPendientes'        => $cantidadDePedidosPendientes,
            'cantidadDeReparacionesPendientes'   => $cantidadDeReparacionesPendientes,
            'cantidadDeReparacionesEnReparacion' => $cantidadDeReparacionesEnReparacion,
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
