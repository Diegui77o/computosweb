<?php

// Controler que realiza la baja del egreso y actualiza el stock del producto correspondiente
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2)) {
        $id = $_POST["id"];
        // Busco cantidades y el id del producto del egreso
        include "../model/egreso.php";
        $egreso           = new Egreso();
        $egresoEncontrado = $egreso->buscarEgreso($id);
        $cantidad         = $egresoEncontrado["cantidad"];
        $idProducto       = $egresoEncontrado["idproducto"];
        // Incluyo el modelo del producto y actualizo su stock
        include "../model/producto.php";
        $producto            = new Producto();
        $seEncuentraProducto = $producto->buscarProducto($idProducto);
        $stockActual         = $seEncuentraProducto["stock"];
        $total               = $stockActual + $cantidad;
        $producto->actualizarStock($total, $idProducto);
        // Elimino el egreso
        $egreso->eliminarEgreso($id);
        $listadoDeEgresos = $egreso->listarEgresos();
        // Cargo la vista con su respectivo mensaje
        $msj = "El egreso se eliminó correctamente y se actualizo a '" . $total . "' el stock del producto: " . $seEncuentraProducto["nombre"] . " - " . $seEncuentraProducto["marca"];
        echo $twig->render('egreso/listado.html.twig', array(
            'pagina'           => ' - Listado egresos',
            'rol'              => $rol,
            'listadoDeEgresos' => $listadoDeEgresos,
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
