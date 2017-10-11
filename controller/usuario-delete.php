<?php
// Controlador que elimina un usuario de la base de datos
require_once 'cargaTwig.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // id del usuario enviado por el formulario
        $id = $_POST["id"];
        include "../model/usuario.php";
        $usuario           = new Usuario();
        $usuarioEncontrado = $usuario->buscarUsuario($id);
        if ($usuarioEncontrado["idrol"] != 1) {
            // Si el usuario no es Administrador se puede eliminar
            $usuario->eliminarUsuario($id);
            $buscarUsuario     = $usuario->buscarUsuario($id);
            $listadoDeUsuarios = $usuario->listarUsuarios();
            // Si cuando busco el id no se encuentra es porque fue eliminado
            if ($buscarUsuario == null) {
                // Cargo la vista (Pasandole el listado de los usuarios)
                echo $twig->render('usuario/listado.html.twig', array(
                    'pagina'            => ' - Listado de usuarios',
                    'rol'               => $rol,
                    'listadoDeUsuarios' => $listadoDeUsuarios,
                    'msjExito'          => true,
                    'msj'               => 'El usuario fue eliminado correctamente!',
                ));
            } else {
                // Cargo la vista (Pasandole el listado de los usuarios)
                echo $twig->render('usuario/listado.html.twig', array(
                    'pagina'            => ' - Listado de usuarios',
                    'rol'               => $rol,
                    'listadoDeUsuarios' => $listadoDeUsuarios,
                    'msjError'          => true,
                    'msj'               => 'El usuario no puede ser eliminado porque posee referencias en otras tablas.',
                ));
            }
        } else {
            // Cargo la vista (Pasandole el listado de los usuarios)
            echo $twig->render('usuario/listado.html.twig', array(
                'pagina'            => ' - Error',
                'rol'               => $rol,
                'listadoDeUsuarios' => $listadoDeUsuarios,
                'msjError'          => true,
                'msj'               => 'Los usuarios administradores no pueden ser eliminados.',
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
        'tipoDePanel' => 'panel-danger',
        'msj'         => 'Error! No posee sesion activa, por favor vuelva a ingresar al sistema.',
    ));
}
