<?php
// Controlador que realiza el alta del nuevo usuario
require_once 'cargaTwig.php';
require_once 'encriptador.php';
// Datos enviados por el formulario de registro
$usuarioNuevo    = $_POST["usuario"];
$clave1          = $_POST["pass_confirmation"];
$claveEncriptada = Encrypter::encrypt($clave1);
$clave2          = $_POST["pass"];
$nombre          = $_POST["name"];
$apellido        = $_POST["lastname"];
$telefono        = $_POST["telefono"];
$email           = $_POST["email"];
$areaNueva       = $_POST["area"];
$habilitado      = 0;
$rolUsuario      = 3;

if ($clave1 == $clave2) {
    // Incluyo el modelo del usuario y creo instancia del mismo
    include '../model/usuario.php';
    $usuario            = new Usuario();
    $seEncuentraUsuario = $usuario->existeUsuario($usuarioNuevo);
    if ($seEncuentraUsuario == null) {
        $usuario->altaUsuario($usuarioNuevo, $claveEncriptada, $nombre, $apellido, $telefono, $email, $habilitado, $rolUsuario, $areaNueva);
        // Cargo la vista del registro mostrando mensaje
        echo $twig->render('layout/registrarse.html.twig', array(
            'pagina'   => ' - Registrarse',
            'msjExito' => true,
            'msj'      => 'La operación ha sido registrada. Le solicitamos que llamé al centro de Cómputos (02983 439253/254/255) para que pueda ser habilitado y así poder ingresar al sistema. ¡Muchas Gracias!',
        ));
    } else {
        include "../model/area.php";
        $area           = new Area();
        $listadoDeAreas = $area->listarAreas();
        // Cargo la vista del registro mostrando mensaje
        $msj = "ya hay otra persona que utiliza el usuario: '" . $usuarioNuevo . "'. Pruebe con otro usuario";
        echo $twig->render('layout/registrarse.html.twig', array(
            'pagina'         => ' - Registrarse',
            'msjError'       => true,
            'msj'            => $msj,
            'listadoDeAreas' => $listadoDeAreas,
        ));
    }
} else {
    include "../model/area.php";
    $area           = new Area();
    $listadoDeAreas = $area->listarAreas();
    // Cargo la vista del registro mostrando mensaje
    $msj = "las contraseñas no coinciden!";
    echo $twig->render('layout/registrarse.html.twig', array(
        'pagina'         => ' - Registrarse',
        'msjError'       => true,
        'msj'            => $msj,
        'listadoDeAreas' => $listadoDeAreas(),
    ));
}
