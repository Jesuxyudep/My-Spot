<?php
require_once '../resources/vendor/autoload.php';
require_once '../operaciones_sql.php';

session_start();

    //Crear una nueva sesion de trabajo usando las credenciales de la aplicacion registrada en Developers
    $credenciales = recuperarCredenciales();

        $session = new SpotifyWebAPI\Session(
            $credenciales[0],
            $credenciales[1],
            $credenciales[2]
        );

    //Solicitar token de acceso usando el código de Spotify
    $session->requestAccessToken($_GET['code']);

        //Almaceno esos tokens para poder usarlos
        $accessToken = $session->getAccessToken();
        $refreshToken = $session->getRefreshToken();

    //Creo una nueva instancia de la API para poder trabajar con ella y establecer el token de acceso antes obtenido para esta sesion
    $api = new SpotifyWebAPI\SpotifyWebAPI();
    $api->setAccessToken($accessToken);

        //Obtener ID del usuario para poder utilizarlo
        $dataUser = $api->me();
        $_SESSION["id"] = $dataUser->id;

    //Registrar datos del usuario o actualizarlos (tokens)
    checkUsuario($_SESSION["id"], $accessToken, $refreshToken);

    //Almaceno la sesión de trabajo para poder evaluar el tiempo de validez del token y ver así si tengo que refrescarlo o no
    $_SESSION["sesion"] = $session;

    //Volver al index indicando que se cargue la vista de la página principal e indicando que el usuario se ha logueado
    $_SESSION["vista"] = "App";
    $_SESSION["bckpVista"] = $_SESSION["vista"];
    $_SESSION["logueado"] = "Loggued";

    header('Location: ../../');
    //die();

?>
