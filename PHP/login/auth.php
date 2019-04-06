<?php

    require_once '../resources/vendor/autoload.php';
    require_once '../operaciones_sql.php';

    //Crear una nueva sesion de trabajo usando las credenciales de la aplicacion registrada en Developers
    $credenciales = recuperarCredenciales();

        $session = new SpotifyWebAPI\Session(
            $credenciales[0],
            $credenciales[1],
            $credenciales[2]
        );

    $options = [
        'scope' => [
            'playlist-read-private',
            'user-read-private',
            'user-top-read',
            'user-read-recently-played'
        ],
    ];

    header('Location: ' . $session->getAuthorizeUrl($options));
    die();
?>