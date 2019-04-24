<?php
require_once 'resources/vendor/autoload.php';
require_once 'operaciones_sql.php';

//Fichero en el que se incluyen las funciones de la aplicación web

    //Cargar contenido de vista, se conoce la vista gracias a $_REQUEST
    function cargarVista ($vista)
    {
        require("vistas/$vista.php"); //Coje valor de la variable de sesión vista
    }

    //Establecer el título de cada página automáticamente en función de su ubicación
    function cargarTitulo ($pagina)
    {
        echo $pagina . " | MySpot";
    }

    //Crear instancia de SpotifyWebAPI para usarla
    function crearWebAPI ()
    {
        $api = new SpotifyWebAPI\SpotifyWebAPI();
        $accessToken = obtenerTokens($_SESSION["id"])[0];
        $api->setAccessToken($accessToken);

        return $api;
    }

    //Obtener informacion sobre el top de canciones del usuario en diferentes terms de tiempo
    function obtenerTop ($contenido, $rango)
    {
        $api = crearWebAPI();

        switch ($rango) {
            case 'l':
                $rango = "long_term";
                break;
            case 'm':
                $rango = "medium_term";
                break;
            case 'c':
                $rango = "short_term";
                break;
            default:
                $rango = "long_term";
                break;
        }

        switch ($contenido) {
            case 'c':
                $contenido = "tracks";
                break;
            case 'a':
                $contenido = "artists";
                break;
            default:
                $contenido = "tracks";
                break;
        }

        $opciones = [
            "limit" => 50,
            "time_range" => $rango
        ];

        $dataUser = $api->getMyTop($contenido, $opciones);

        if ($contenido == "tracks")
        {
            for ($a = 0; $a < count($dataUser->items); $a++)
            {
                //Para cada elemento generamos la Estructura
                echo "<a class='cancionTop' target='_blank'  href='" . $dataUser->items[$a]->external_urls->spotify . "'>";
                    echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                    echo "<span class='infoCancion'>";
                        echo "<img src='" . $dataUser->items[$a]->album->images[2]->url . "'>";
                        echo "<span class='resumen'>";
                            echo "<span class='cancion'>" . $dataUser->items[$a]->name . "</span>";
                            echo "<span class='separador'>----</span>";
                            echo "<span class='artista'>" . $dataUser->items[$a]->artists[0]->name . "</span>";
                        echo "</span>";
                    echo "</span>";
                echo "</a>";
            }
        }
        else if ($contenido == "artists")
        {
            for ($a = 0; $a < count($dataUser->items); $a++)
            {
                $generos = 0;

                //Para cada elemento generamos la Estructura
                echo "<a class='artistaTop' target='_blank'  href='" . $dataUser->items[$a]->external_urls->spotify . "'>";
                    echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                    echo "<span class='infoArtista'>";
                        echo "<img class='imgArtista' src='" . $dataUser->items[$a]->images[2]->url . "'>";
                        echo "<span class='resumen'>";
                            echo "<span class='nombreArtista'>" . $dataUser->items[$a]->name . "</span>";
                            echo "<span class='separador'>----</span>";
                            echo "<span class='generos'>";
                                for ($b = 0; $b < count($dataUser->items[$a]->genres); $b++)
                                {
                                    if ($generos < 4)
                                    {
                                        echo $dataUser->items[$a]->genres[$b] . ","; //sacar todos
                                        $generos++;
                                    }
                                    else
                                    {
                                        break;
                                    }
                                }
                            echo "</span>";
                        echo "</span>";
                    echo "</span>";
                echo "</a>";
            }
        }
    }

    function obtenerRecientes ()
    {
        $api = crearWebAPI();

        $opciones = [
            "limit" => 50
        ];

        $dataUser = $api->getMyRecentTracks($opciones);

        for ($a = 0; $a < count($dataUser->items); $a++)
        {
            //Para cada elemento generamos la Estructura
            echo "<a class='cancionReciente' target='_blank'  href='" . $dataUser->items[$a]->track->external_urls->spotify . "'>";
                echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                echo "<span class='infoCancion'>";
                    echo "<img src='" . $dataUser->items[$a]->track->album->images[2]->url . "'>";
                    echo "<span class='resumen'>";
                        echo "<span class='cancion'>" . $dataUser->items[$a]->track->name . "</span>";
                        echo "<span class='separador'>----</span>";
                        echo "<span class='artista'>" . $dataUser->items[$a]->track->album->artists[0]->name . "</span>";
                    echo "</span>";
                echo "</span>";
            echo "</a>";
        }
    }

    function crearPlaylistTop ($rango)
    {
        $api = crearWebAPI();
        $nombrePlaylist = "";

        switch ($rango) {
            case 'l':
                $rango = "long_term";
                $nombrePlaylist = "Mi Top 50 de todos los tiempos";
                break;
            case 'm':
                $rango = "medium_term";
                $nombrePlaylist = "Mi Top 50 de estos ultimos 6 meses";
                break;
            case 'c':
                $rango = "short_term";
                $nombrePlaylist = "Mi Top 50 de este mes";
                break;
            default:
                $rango = "long_term";
                break;
        }

        $opciones = [
            "limit" => 50,
            "time_range" => $rango
        ];

        $canciones = $api->getMyTop("tracks", $opciones);

        $api->createPlaylist([
            'name' => $nombrePlaylist
        ]);

        //Poner imagen a la playlist ¿?

        $playlists = $api->getUserPlaylists($api->me()->id, [
            'limit' => 50
        ]);

        foreach ($playlists->items as $playlist)
        {
            if ($playlist->name == $nombrePlaylist )
            {
                for ($a = 0; $a < count($canciones->items); $a++)
                {
                    $api->addPlaylistTracks($playlist->id, [
                        $canciones->items[$a]->id
                    ]);
                }

                break; //Salir al añadir las canciones a la nueva playlist ya encontrada
            }
        }
    }

    function crearPlaylistTopArtistas ($rango)
    {
            $api = crearWebAPI();
            $nombrePlaylist = "";

            switch ($rango) {
                case 'l':
                    $rango = "long_term";
                    $nombrePlaylist = "Mi Top 50 de todos los tiempos";
                    break;
                case 'm':
                    $rango = "medium_term";
                    $nombrePlaylist = "Mi Top 50 de estos ultimos 6 meses";
                    break;
                case 'c':
                    $rango = "short_term";
                    $nombrePlaylist = "Mi Top 50 de este mes";
                    break;
                default:
                    $rango = "long_term";
                    break;
            }

            $opciones = [
                "limit" => 50,
                "time_range" => $rango
            ];

            $api->createPlaylist([
                'name' => "TopArtistas"
            ]);

            $playlists = $api->getUserPlaylists($api->me()->id, [
                'limit' => 50
            ]);

            $infoArtistas = $api->getMyTop("artists", $opciones);
            $playlistID = "";

            foreach ($playlists->items as $playlist)
            {
                if ($playlist->name == "TopArtistas" )
                {
                    $playlistID = $playlist->id;
                    break;
                }
            }

            foreach ($infoArtistas->items as $artista)
            {
                    $tracks = $api->getArtistTopTracks($artista->id, [
                        'country' => 'es'
                    ]);

                    $canciones = 0;

                    foreach ($tracks->tracks as $track)
                    {
                        $api->addPlaylistTracks($playlistID, [
                            $track->id
                        ]);

                        $canciones++;

                        if ($canciones >= 3)
                            break;
                    }
             }
        }

    function crearPlaylistRecientes ()
    {
        $api = crearWebAPI();

        $opciones = [
            "limit" => 50
        ];

        $cancionesRecientes = $api->getMyRecentTracks($opciones);

        $api->createPlaylist([
            'name' => "Recientes - MySpot"
        ]);

        //Poner imagen a la playlist ¿?

        $playlists = $api->getUserPlaylists($api->me()->id, [
            'limit' => 50
        ]);

        foreach ($playlists->items as $playlist)
        {
            if ($playlist->name == "Recientes - MySpot" )
            {
                for ($a = 0; $a < count($cancionesRecientes->items); $a++)
                {
                    $api->addPlaylistTracks($playlist->id, [
                        $cancionesRecientes->items[$a]->track->id
                    ]);
                }

                break; //Salir al añadir las canciones a la nueva playlist ya encontrada
            }
        }
     }

    function obtenerPlaylists ()
    {
         $api = crearWebAPI();
         $idUser = $api->me()->id;

         $playlists = $api->getUserPlaylists($idUser, [
            'limit' => 50
        ]);

        for ($a = 0; $a < count($playlists->items); $a++)
        {
            if ($playlists->items[$a]->owner->id == $idUser) //mostrar solamente las playlists que son propiedad de un usuario, ya que una lista que sea de otra persona no la va a poder administrar
            {
                echo "<a class='playlist' target='_blank'  href='" . $playlists->items[$a]->external_urls->spotify . "'>";
                    echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                    echo "<span class='infoPlaylist'>";
                        echo "<img src='" . $playlists->items[$a]->images[0]->url . "'>";
                        echo "<span class='resumen'>";
                            echo "<span class='nomPlaylist'>" . $playlists->items[$a]->name . "</span>";
                            echo "<span class='separador'>----</span>";
                            echo "<span class='propietario'>" . $playlists->items[$a]->owner->display_name . "</span>";
                        echo "</span>";
                    echo "</span>";
                echo "</a>";
            }
        }

        /*foreach ($playlists->items as $playlist) //Debug
        {
            echo '<a href="' . $playlist->external_urls->spotify . '">' . $playlist->name . '</a> <br>';

            echo "<pre>";
                var_dump($playlist);
            echo "</pre>";
        }*/
     }

?>
