<?php
/*
 * ARCHIVO QUE SE ENCARGA DE HACER LA CARGA DE TWIG
 * DEFINE RUTAS POR MEDIO DE LA VARIABLE BASE_PATH, OBTENIENDO ASI LA RUTA COMPLETA DE LA CARPETA
 */
require_once '../vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register ();
$dir = "../view";
$loader = new Twig_Loader_Filesystem ( $dir );
$twig = new Twig_Environment ( $loader, array (
		'cache' => false
) );

// VARIABLES PARA LAS RUTAS
/*define ( 'BASE_PATH', '/obras' );
$twig->addGlobal ( 'BASE_PATH', BASE_PATH );*/