<?php
require_once 'cargaTwig.php';
session_start();
if (!isset($_SESSION["rol"])) {
    echo $twig->render('layout/ayuda-videos.html.twig', array(
        'pagina' => ' - Video-tutoriales',
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
