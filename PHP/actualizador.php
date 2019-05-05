<?php

    //Con este actualizador lo que hago es actualizar los tokens de acceso a la aplicación antes de que estos vayan a expirar (duración de 60 minutos)

        //Compruebo el tiempo restante de validez del token de acceso y lo refresco si es necesario usando el RefreshToken
        $sesion = $_SESSION["sesion"];
        $minutosRestantes = round( ( $sesion->getTokenExpiration() - date("U") ) / 60 , 0,  PHP_ROUND_HALF_DOWN);

        if ($minutosRestantes <= 10)
        {
            // Fetch the refresh token from somewhere. A database for example.
            $refreshToken = obtenerTokens($_SESSION["id"])[1];

            $sesion->refreshAccessToken($refreshToken);

            $accessToken = $sesion->getAccessToken();

            checkUsuario($_SESSION["id"], $accessToken, $refreshToken);
        }
?>
