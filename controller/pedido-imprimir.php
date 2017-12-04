<?php
// Controlador que imprime un listado de los pedidos del usuario del día en estado reservados
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        $idpedido = $_GET["idpedido"];
        include "../model/pedido-detalle.php";
        $pedidoDetalle    = new PedidoDetalle();
        $pedido           = $pedidoDetalle->visualizarDetallePedido($idpedido);
        $fechaBase        = $pedido[6];
        $fecha            = date("d-m-Y", strtotime($fechaBase));
        $idusuario        = $pedido["idusuario"];
        $listadoDePedidos = $pedidoDetalle->reportePedidosDelUsuario($idusuario, $fechaBase);

        // Creación del PDF
        include '../controller/reporte-plantilla-pedido.php';
        $pdf = new PDF();
        $pdf->SetTopMargin(5.4);
        $pdf->SetLeftMargin(4.5);
        $pdf->AliasNbPages();
        $pdf->SetFont('Times', 'I', 9);

        $pdf->AddPage();
        //$pdf->SetXY ( 145, 8 );
        $pdf->Text(88, 12, utf8_decode("$fecha"));
        $pdf->Text(88, 16, utf8_decode($pedido["apellidoUsuario"] . "," . $pedido["nombreUsuario"] . " (" . $pedido["usuario"] . ")"));
        $pdf->Text(88, 20, utf8_decode($pedido["nombreArea"]));
        $pdf->Text(155, 25, utf8_decode("Firma del usuario solicitante"));

        foreach ($listadoDePedidos as $fila => $valor) {
            $pdf->Text(5, $pdf->GetY(), utf8_decode("0000" . $listadoDePedidos[$fila][0]));
            $pdf->Text(50, $pdf->GetY(), utf8_decode($listadoDePedidos[$fila][1] . " (" . $listadoDePedidos[$fila][2]) . ")");
            $pdf->Text(133, $pdf->GetY(), utf8_decode($listadoDePedidos[$fila][3]));
            //$pdf->MultiCell ( 110, $pdf->GetY (), utf8_decode ( $listadoDePedidos [$fila] [10] ));
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
