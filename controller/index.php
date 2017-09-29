<?php

require_once 'cargaTwig.php';

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_SESSION["usuario"])) {
    $rol = $_SESSION["rol"];
    switch ($rol) {
        case 1:
            echo $twig->render('layout/sistema.html.twig', array(
                'rol' => $rol,
            ));
            break;

        case 2:
            echo $twig->render('layout/sistema.html.twig', array(
                'rol' => $rol,
            ));
            break;

        case 3:
            echo $twig->render('layout/sistema.html.twig', array(
                'rol' => $rol,
            ));
            break;
    }
} else {
    echo $twig->render('layout/base.html.twig', array());
}
