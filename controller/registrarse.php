<?php
// Controlador que carga la vista para mostrar el formulario
require_once 'cargaTwig.php';
include "../model/area.php";

session_start();
if (!isset($_SESSION["rol"])) {
    $area           = new Area();
    $listadoDeAreas = $area->listarAreas();
    // Cargo la vista y le paso el listado de las areas del sistema
    echo $twig->render('layout/registrarse.html.twig', array(
        'pagina'         => ' - Registrarse',
        'listadoDeAreas' => $listadoDeAreas,
    ));
} else {
    // Si ya se encuentra logueado, se muestra el msj correspondiente
    $rol = $_SESSION["rol"];
    // Cargo la vista y le paso el listado de las areas del sistema
    echo $twig->render('layout/mensaje.html.twig', array(
        'pagina'      => 'Mensaje del sistema',
        'msj'         => 'Error! usted ya se encuentra registrado en nuestro sistema.',
        'tipoDePanel' => 'panel-danger',
    ));
}
