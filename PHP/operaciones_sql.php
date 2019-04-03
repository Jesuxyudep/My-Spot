<?php

//Fichero en el que se incluyen las funciones relacionadas con la interacción con la BD de la propia aplicación

//Constantes que van a almacenar los datos de conexion con la BD que se va a usar
define ("DATABASE", 'myspot');
define ("USER", 'jesus');
define ("PASS", 'P@ssw0rd');
define ("SERVER", 'localhost');

    //Conecta con la BD
    function conectarBD ()
    {
        $enlace = mysqli_connect(SERVER, USER, PASS, DATABASE);

        if (!$enlace)
        {
            return false;
        }
        else
        {
            return $enlace;
        }
    }

    //Cierra la conexion con la BD
    function cerrarBD ($enlace)
    {
        mysqli_close($enlace);
    }

    //Recuperar los datos de acceso de la aplicacion
    function recuperarCredenciales ()
    {
        $credenciales = [];

        if ( $enlace = conectarBD() )
        {
            $sql = "SELECT * FROM credenciales";
            $resultado = mysqli_query($enlace, $sql);

            while ( ($reg = mysqli_fetch_array($resultado) ) )
            {
                $credenciales[] = $reg[0];
                $credenciales[] = $reg[1];
                $credenciales[] = $reg[2];
            }
        }

        return $credenciales;
    }

    /*
        Comprueba si un usuario ha visitado antes la aplicacion (su ID está registrado en BD).
        Si el usuario ya ha entrado antes a la aplicacion, se refrescan sus token (Refresh y Access)
        Si no había entrado antes, se registra al usuario en la BD de la aplicación
    */
    function checkUsuario ($id, $access, $refresh)
    {
        if ( $enlace = conectarBD() )
        {
            $sql = "SELECT AccessToken, RefreshToken FROM usuarios WHERE ID = '$id'";
            $resultado = mysqli_query($enlace,$sql);

            if (mysqli_num_rows($resultado) > 0)
            {
                $actualizar = "UPDATE usuarios SET AccessToken = '$access', RefreshToken = '$refresh' WHERE ID = '$id'";
                $resultado = mysqli_query($enlace, $actualizar);
            }
            else
            {
                $insertar = "INSERT INTO usuarios (ID, AccessToken, RefreshToken) VALUES ('$id', '$access', '$refresh')";
                $resultado = mysqli_query($enlace, $insertar);
            }
        }
    }

    //Obtener tokens del usuario
    function obtenerTokens ($id)
    {
        $tokens = [];

        if ( $enlace = conectarBD() )
        {
            $sql = "SELECT AccessToken, RefreshToken FROM usuarios WHERE ID = '$id'";
            $resultado = mysqli_query($enlace,$sql);

            while ( ($reg = mysqli_fetch_array($resultado) ) )
            {
                $tokens[] = $reg[0];
                $tokens[] = $reg[1];
            }

            return $tokens;
        }
        else
        {
            return false;
        }
    }

    /*
    // Fetch the refresh token from somewhere. A database for example.

    $session->refreshAccessToken($refreshToken);

    $accessToken = $session->getAccessToken();

    // Set our new access token on the API wrapper and continue to use the API as usual
    $api->setAccessToken($accessToken);

    */

?>
