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

?>
