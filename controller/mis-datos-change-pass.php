<?php
// Controlador que realiza el cambio de la contraseña del usuario
require_once 'cargaTwig.php';
require_once 'encriptador.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if (($rol == 1) or ($rol == 2) or ($rol == 3)) {
        $id            = $_POST["id"];
        $pass_anterior = $_POST["pass_anterior"];
        $clave1        = $_POST["pass_confirmation"];
        $clave2        = $_POST["pass"];
        include "../model/usuario.php";
        $usuario           = new Usuario();
        $usuarioEncontrado = $usuario->buscarUsuario($id);
        $passEnLaBase      = Encrypter::decrypt($usuarioEncontrado["clave"]);
        if ($pass_anterior == $passEnLaBase) {
            if ($clave1 == $clave2) {
                $claveEncriptada   = Encrypter::encrypt($clave1);
                $usuarioEncontrado = $usuario->cambiarClave($claveEncriptada, $id);
                session_destroy();
                $rol == null;
                // Cargo la vista
                echo $twig->render('layout/base.html.twig', array(
                    'pagina'   => ' - Volver a ingresar',
                    'msjExito' => true,
                    'msj'      => 'La contraseña fue modificada correctamente! Vuelva a ingresar.',
                ));
            } else {
                // Cargo la vista
                echo $twig->render('layout/mis-datos-cambiar-clave.html.twig', array(
                    'pagina'            => ' - Error',
                    'rol'               => $rol,
                    'msjError'          => true,
                    'msj'               => 'Las contraseñas no coinciden. Vuelva a ingresarlas.',
                    'usuarioEncontrado' => $usuarioEncontrado,
                ));
            }
        } else {
            // Cargo la vista
            echo $twig->render('layout/mis-datos-cambiar-clave.html.twig', array(
                'pagina'            => ' - Error',
                'rol'               => $rol,
                'msjError'          => true,
                'msj'               => 'La contraseña anterior es incorrecta, por favor vuelva a ingresarla.',
                'usuarioEncontrado' => $usuarioEncontrado,
            ));
        }
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
