<?php
// Controlador que imprime el listado del total de pedido por cada area
require_once 'cargaTwig.php';
session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        include "../model/pedido-detalle.php";
        $pedidoDetalle    = new PedidoDetalle();
        $listadoDePedidos = $pedidoDetalle->totalPedidosPorArea();
        // Creacion del objeto de la clase heredada
        include '../controller/reporte-plantilla-total-pedidos-por-area.php';
        $pdf = new PDF();
        $pdf->SetTopMargin(5.4);
        $pdf->SetLeftMargin(4.5);
        $pdf->AliasNbPages();
        $pdf->SetFont('Times', 'I', 9);

        $pdf->AddPage();
        foreach ($listadoDePedidos as $fila => $valor) {
            $pdf->Text(15, $pdf->GetY(), utf8_decode($listadoDePedidos[$fila][0]));
            $pdf->Text(90, $pdf->GetY(), utf8_decode($listadoDePedidos[$fila][1]) . " (" . utf8_decode($listadoDePedidos[$fila][2]) . ")");
            $pdf->Text(180, $pdf->GetY(), utf8_decode($listadoDePedidos[$fila][3]));

            $pdf->cell(0, 5.5, '', 0, 1);
        }

        $pdf->Output();
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
