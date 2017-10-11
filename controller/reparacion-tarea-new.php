<?php
// Controller que realiza el alta de la nueva reparación o tarea
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        if ($rol == 1) {
            $usuario = $_POST["usuario"];
        } else {
            $usuario = $_SESSION["id"];
        }
        $fecha_ingreso      = date("Y-m-d");
        $descripcion        = $_POST["descripcion"];
        $idEstadoReparacion = 1; // Pendiente
        $observacion        = "No se registran observaciones de la reparación o tarea.";
        $fecha_salida       = strtotime('+3 day', strtotime($fecha_ingreso));
        $fecha_salida       = date('Y-m-j', $fecha_salida);

        include "../model/reparacion.php";
        $reparacion = new Reparacion();
        $reparacion->altaReparacion($usuario, $fecha_ingreso, $descripcion, $idEstadoReparacion, $observacion, $fecha_salida);
        // Listado de usuarios
        include "../model/usuario.php";
        $usuario           = new Usuario();
        $listadoDeUsuarios = $usuario->listarUsuarios();
        // Cargo la vista
        echo $twig->render('reparacion/nueva.html.twig', array(
            'pagina'            => ' - Listado de reparaciones/tareas pendientes',
            'rol'               => $rol,
            'listadoDeUsuarios' => $listadoDeUsuarios,
            'msjExito'          => true,
            'msj'               => 'La reparación o tarea fue creada correctamente!',
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
