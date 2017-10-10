<?php
// Controlador que realiza la baja del ingreso
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        // id del ingreso enviado por el listado
        $id = $_POST["id"];
        include "../model/ingreso.php";
        $ingreso           = new Ingreso();
        $ingresoEncontrado = $ingreso->buscarIngreso($id);
        $cantidad          = $ingresoEncontrado["cantidad"];
        $idProducto        = $ingresoEncontrado["idproducto"];
        // Busco el producto y actualizo su stock
        include "../model/producto.php";
        $producto            = new Producto();
        $seEncuentraProducto = $producto->buscarProducto($idProducto);
        $stockActual         = $seEncuentraProducto["stock"];
        $total               = $stockActual - $cantidad;
        $producto->actualizarStock($total, $idProducto);
        // Elimino el ingreso y me quedo con el listado de ingresos para pasarlo a la vista
        $ingresoEncontrado = $ingreso->eliminarIngreso($id);
        $listadoDeIngresos = $ingreso->listarIngresos();
        // Cargo la vista con su respectivo mensaje
        $msj = "El ingreso se eliminó correctamente y se actualizo a '" . $total . "' el stock del producto: " . $seEncuentraProducto["nombre"] . " - " . $seEncuentraProducto["marca"];
        echo $twig->render('ingreso/listado.html.twig', array(
            'pagina'            => ' - Listado ingresos',
            'rol'               => $rol,
            'listadoDeIngresos' => $listadoDeIngresos,
            'msjExito'          => true,
            'msj'               => $msj,
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
        'rol'         => $rol,
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
