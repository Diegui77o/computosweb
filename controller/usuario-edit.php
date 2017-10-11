<?php
// Controller que realiza la modificación del usuario
require_once 'cargaTwig.php';
require_once 'encriptador.php';

session_start();
if (isset($_SESSION["rol"])) {
    $rol = $_SESSION["rol"];
    if ($rol == 1) {
        // Datos enviados por el formulario
        $idusuario      = $_POST["id"];
        $usuarioNuevo   = $_POST["usuario"];
        $passNueva      = $_POST["pass_confirmation"];
        $passEncriptada = Encrypter::encrypt($passNueva);
        $nombre         = $_POST["name"];
        $apellido       = $_POST["lastname"];
        $email          = $_POST["email"];
        $telefono       = $_POST["telefono"];
        $areaNueva      = $_POST["area"];
        $rolUsuario     = $_POST["rol"];
        $habilitado     = 1;
        // Incluyo modelo del usuario para correspondientes operaciones
        include "../model/usuario.php";
        $usuario            = new Usuario();
        $seEncuentraUsuario = $usuario->existeNombreUsuario($usuarioNuevo);
        if (($idusuario == $seEncuentraUsuario["id"]) or ($seEncuentraUsuario == null)) {
            $usuario->modificarUsuario($usuarioNuevo, $passEncriptada, $nombre, $apellido, $telefono, $email, $rolUsuario, $areaNueva, $idusuario);
            $listadoDeUsuarios = $usuario->listarUsuarios();
            // Carga la vista (Pasandole el rol, listado de áreas y roles)
            echo $twig->render('usuario/listado.html.twig', array(
                'pagina'            => ' - Listado de usuarios',
                'rol'               => $rol,
                'listadoDeUsuarios' => $listadoDeUsuarios,
                'msjExito'          => true,
                'msj'               => 'El usuario fue modificado correctamente!',
            ));
        } else {
            // Error si el usuario no está disponible
            // Listado de áreas
            include "../model/area.php";
            $area           = new Area();
            $listadoDeAreas = $area->listarAreas();
            // Listado de roles
            include "../model/rol.php";
            $nuevoRol       = new Rol();
            $listadoDeRoles = $nuevoRol->listarRoles();
            // Busco usuario para pasarselo a la vista
            $usuarioEncontrado = $usuario->buscarUsuario($idusuario);
            // Cargo la vista (Pasandole el rol, listado de áreas y roles)
            $msj = "Ya hay otra persona que utiliza el usuario ''" . $usuarioNuevo . "''. Pruebe con otro usuario.";
            echo $twig->render('usuario/modificar.html.twig', array(
                'pagina'            => ' - Error',
                'rol'               => $rol,
                'listadoDeAreas'    => $listadoDeAreas,
                'listadoDeRoles'    => $listadoDeRoles,
                'usuarioEncontrado' => $usuarioEncontrado,
                'msjError'          => true,
                'msj'               => $msj,
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
