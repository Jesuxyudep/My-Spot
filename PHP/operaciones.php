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

    function iframeCancion ()
    {
        $idCancion = $_SESSION["cancionReproducir"];

        echo "<iframe src='https://open.spotify.com/embed/track/" . $idCancion . "' allow='encrypted-media' width='80' height='80'></iframe>";
    }

    function iframeArtista ()
    {
        $idArtista = $_SESSION["artistaReproducir"];

        echo "<iframe src='https://open.spotify.com/embed/artist/" . $idArtista . "' allow='encrypted-media' width='80' height='80'></iframe>";
    }

    function iframeInicio ()
    {
        $idCancion = $_SESSION["cancionReproducir"];

        echo "<iframe src='https://open.spotify.com/embed/track/" . $idCancion . "' allow='encrypted-media' width='280' height='80'></iframe>";
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
                echo "<a class='cancionTop' cancion='" . $dataUser->items[$a]->id . "'>";
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
                echo "<a class='artistaTop' artista='" . $dataUser->items[$a]->id . "'>";
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
                                        if ($generos == count($dataUser->items[$a]->genres) - 1 || $generos == 3)
                                        {
                                            echo $dataUser->items[$a]->genres[$b]; //sacar todos
                                        }
                                        else
                                        {
                                            echo $dataUser->items[$a]->genres[$b] . ", "; //sacar todos
                                        }
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
            echo "<a class='cancionReciente' cancion='" . $dataUser->items[$a]->track->id . "'>";
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

     function obtenerPlaylists ()
     {
         $api = crearWebAPI();
         $idUser = $api->me()->id;
         $contador = 1;

         $playlists = $api->getUserPlaylists($idUser, [
            'limit' => 50
        ]);

        for ($a = 0; $a < count($playlists->items); $a++)
        {
            if ($playlists->items[$a]->owner->id == $idUser) //mostrar solamente las playlists que son propiedad de un usuario, ya que una lista que sea de otra persona no la va a poder administrar
            {
                echo "<a class='playlist' id='" . $playlists->items[$a]->id . "''>";
                    echo "<span class='numOrden'>" . $contador . "</span>";
                    echo "<span class='infoPlaylist'>";
                        echo "<img src='" . $playlists->items[$a]->images[0]->url . "'>";
                        echo "<span class='resumen'>";
                            echo "<span class='nomPlaylist'>" . $playlists->items[$a]->name . "</span>";
                            echo "<span class='separador'>----</span>";
                            echo "<span class='propietario'>" . $playlists->items[$a]->owner->display_name . "</span>";
                        echo "</span>";
                    echo "</span>";
                echo "</a>";

                $contador++;
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

     function cargarCancionesPlaylist($idPlaylist, $offset, $accion = NULL)
     {
         $api = crearWebAPI();
         $opciones = [
             "limit" => 50,
             'offset' => $offset
         ];

         $canciones = $api->getPlaylistTracks($idPlaylist, $opciones);
         $contador = 0 + $offset;

         if ( count($canciones->items) > 0 )
         {
             if ($accion == "inicio")
             {
                 echo '<div class="container" style="margin: 0; margin-bottom: 2vh; max-width: 100%">
                         <div class="row">
                             <div class="col-12 col-sm-9 columnaInfoAdministrar">
                                 <span id="infoAdministrar">Selecciona las canciones a administrar y pulsa sobre el botón para indicar la tarea a realizar</span>
                             </div>
                             <div class="col-12 col-sm-3 columnaInfoAdministrar">
                                 <button id="administrarCanciones" style="float:right; width: 100%">Administrar</button>
                             </div>
                         </div>
                     </div>';
            }

             foreach ($canciones->items as $cancion)
             {
                 echo "<a class='cancionAdministrar' cancion='" . $cancion->track->id . "'>";
                     echo "<span class='numOrden'>" . ($contador + 1) . "</span>";
                     echo "<span class='infoCancion'>";
                         echo "<img src='" . $cancion->track->album->images[2]->url . "'>";
                         echo "<span class='resumen'>";
                             echo "<span class='cancion'>" .$cancion->track->name . "</span>";
                             echo "<span class='separador'>----</span>";
                             echo "<span class='artista'>" . $cancion->track->artists[0]->name . "</span>";
                         echo "</span>";
                     echo "</span>";
                 echo "</a>";

                 $contador++;

                 /*echo "<pre>"; //Debug
                     var_dump($cancion);
                 echo "</pre>";*/
             }
        }

     }

     function  mostrarFotoPerfil()
     {
         $api = crearWebAPI();
         $dataUser = $api->me();

         echo "<div class='perfil'>";

             if (count($dataUser->images) <= 0)
             {
                 echo "<img src='IMG/fotoPorDefecto.png' width='40px' height='40px'>";
                 echo "<span>Perfil</span>";
             }
             else
             {
                 echo "<img src='" . $dataUser->images[0]->url . "' width='40px' height='40px'>";
                 echo "<span>". substr($dataUser->display_name, 0, stripos($dataUser->display_name, " ")) ."</span>";
             }

        echo "</div>";
     }

     function linkPerfil ()
     {
          $api = crearWebAPI();
          echo $api->me()->external_urls->spotify;
     }

     function obtenerPlaylistDestacadas ()
     {
         //Sacar recomendaciones de spotify dirigidas a España

             $api = crearWebAPI();

             $opciones = [
                 "limit" => 10,
                 'country' => 'es'
             ];

             $playlists = $api->getFeaturedPlaylists($opciones);

             echo "<div class='container'>";
                echo "<h2 style='text-align: center' class='tituloInicio'>Playlists destacadas a nivel nacional:</h2>";
                echo "<div class='container' style='text-align: center; margin-bottom: 15px;'><strong style='text-align: center; font-size: 1.4em'>Spotify te recomienda escuchar en el día de hoy:</strong></div>";
                echo "<section class='customer-logos slider'>";
                     foreach ($playlists->playlists->items as $playlist)
                     {
                        echo "<div class='slide'>";
                            echo "<a href='" . $playlist->external_urls->spotify . "'>";
                                echo "<img src='" . $playlist->images[0]->url . "'>";
                            echo "</a>";
                        echo "</div>";
                     }
                echo "</section>";
            echo "</div>";

             /*echo "<pre>";
                var_dump($playlists);
             echo "</pre>";*/
     }

     function obtenerRecomendaciones ( $base )
     {
         $api = crearWebAPI();
         $opciones = "";

             if ( $base == "tops" ) //Recomendaciones basadas en las canciones favoritas del usuario
             {
                 $opciones = [
                     "limit" => 50,
                     "time_range" => "long_term"
                 ];

                 $canciones = $api->getMyTop("tracks", $opciones);
                 $artistas = $api->getMyTop("artists", $opciones);

                 $idsSemillaCanciones = randomizarRecomendaciones( $canciones, "inicio" );
                 $idsSemillaArtistas = randomizarRecomendaciones( $artistas, "inicio" );

                 $opcionesRecomendaciones = [
                     "seed_tracks" => $idsSemillaCanciones,
                     "seed_artists" => $idsSemillaArtistas
                 ];

                 $recomendaciones = $api->getRecommendations($opcionesRecomendaciones);

                 echo "<div class='container contenedorRecomendaciones'>";
                    echo "<ul class='row recomendacionesGrid'>";

                        foreach ($recomendaciones->tracks as $cancion)
                        {
                            echo "<li class='col-6 col-sm-4 col-md-3'>";
                                echo "<a class='recomendacion' data-overlay-text='" . $cancion->name . "' cancion='" . $cancion->id ."'>";
                                    echo "<img src='" . $cancion->album->images[1]->url . "' class='overlay-img'/>";
                               echo "</a>";
                           echo "</li>";
                        }

                    echo "</ul>";
                echo "</div>";

                 /*echo "<pre>";
                     var_dump($recomendaciones);
                 echo "</pre>";*/

             }
     }

     function randomizarRecomendaciones ( $elementos, $lugarRecomendacion ) //Devolverá ID aleatorios y no repetidos de objetos del array pasado como parámetro (pueden ser IDS de canciones o de artistas)
     {
         $numeroRecomendaciones = 2;
         $posicionesRecogidas = [];
         $idsRecomendaciones = [];

         for ($a = 0; $a < $numeroRecomendaciones; $a++)
         {
             $conmutador = false;
             $posicionRecomendacion = round(rand(0, count($elementos->items)), 0);

             if ( count($posicionesRecogidas) > 0 )
             {
                 foreach ($posicionesRecogidas as $posicion)
                 {
                    if ($posicion == $posicionRecomendacion)
                    {
                        $conmutador = true;
                    }
                 }

                 if ($conmutador)
                 {
                     $a--;
                 }
                 else
                 {
                     if ($lugarRecomendacion == "inicio" || $lugarRecomendacion == "topCanciones" || $lugarRecomendacion == "topArtistas")
                     {
                         if ($elementos->items[$posicionRecomendacion]->id == NULL)
                         {
                              $a--;
                         }
                         else
                         {
                             $posicionesRecogidas[] = $posicionRecomendacion;
                             $idsRecomendaciones[] = $elementos->items[$posicionRecomendacion]->id;
                         }
                     }
                     else
                     {
                         if ($elementos->items[$posicionRecomendacion]->track->id == NULL)
                         {
                              $a--;
                         }
                         else
                         {
                             $posicionesRecogidas[] = $posicionRecomendacion;
                             $idsRecomendaciones[] = $elementos->items[$posicionRecomendacion]->track->id;
                         }
                     }
                 }
             }
             else
             {
                 if ($lugarRecomendacion == "inicio" || $lugarRecomendacion == "topCanciones" || $lugarRecomendacion == "topArtistas")
                 {
                     if ($elementos->items[$posicionRecomendacion]->id == NULL)
                     {
                          $a--;
                     }
                     else
                     {
                         $posicionesRecogidas[] = $posicionRecomendacion;
                         $idsRecomendaciones[] = $elementos->items[$posicionRecomendacion]->id;
                     }
                 }
                 else
                 {
                     if ($elementos->items[$posicionRecomendacion]->track->id == NULL)
                     {
                          $a--;
                     }
                     else
                     {
                         $posicionesRecogidas[] = $posicionRecomendacion;
                         $idsRecomendaciones[] = $elementos->items[$posicionRecomendacion]->track->id;
                     }
                 }
             }
         }

         return $idsRecomendaciones;
     }

     function cargarOpcionesCanciones ()
     {
         echo '<div class="modal fade" id="crearPlaylistModal">
                 <div class="modal-dialog modal-dialog-scrollable modal-lg">
                   <div class="modal-content">

                     <div class="modal-header">
                       <h1>Creando tu nueva playlist favorita</h1>
                       <button type="button" class="close" data-dismiss="modal">×</button>
                     </div>

                     <div class="modal-body">

                         <div class="opcionCanciones" id="mismasCanciones">
                             <i class="fas fa-music"></i></i>
                             <span>Añadir las mismas canciones de la lista</span>
                         </div>
                         <hr>
                         <div class="opcionCanciones" id="cancionesSimilares">
                             <i class="fas fa-sliders-h"></i></i>
                             <span>Añadir canciones SIMILARES a las que aparecen en la lista</span>
                         </div>

                     </div>

                     <!-- Modal footer -->
                     <div class="modal-footer">

                     </div>

                   </div>
                 </div>
               </div>';
     }

     function cargarOpcionesPlaylists ()
     {
         $api = crearWebAPI();
         $idUser = $api->me()->id;

         $playlists = $api->getUserPlaylists($idUser, [
            'limit' => 50
        ]);

        echo '<div class="modal fade" id="crearPlaylistModal">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">

                    <div class="modal-header">
                      <h1>Crear una nueva playlist o añadir canciones a una existente</h1>
                      <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">

                        <div class="nuevaPlaylist">
                            <input type="text" id="nombreNuevaPlaylist" placeholder="Nombre de tu nueva Playlist">
                            <i class="fas fa-plus-circle nuevaPlaylist"></i>
                            <span class="nuevaPlaylist">Crear una nueva Playlist</span>
                        </div>
                        <hr>
                    ';

                        for ($a = 0; $a < count($playlists->items); $a++)
                        {
                            if ($playlists->items[$a]->owner->id == $idUser) //mostrar solamente las playlists que son propiedad del usuario, ya que una lista que sea de otra persona no la va a poder administrar
                            {
                                echo "<div class='playlist' id='". $playlists->items[$a]->id . "'>";
                                    echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                                    echo "<span class='infoPlaylist'>";
                                        echo "<img src='" . $playlists->items[$a]->images[0]->url . "'>";
                                        echo "<span class='resumen'>";
                                            echo "<span class='nomPlaylist'>" . $playlists->items[$a]->name . "</span>";
                                            echo "<span class='separador'>----</span>";
                                            echo "<span class='propietario'>" . $playlists->items[$a]->owner->display_name . "</span>";
                                        echo "</span>";
                                    echo "</span>";
                                echo "</div>";
                            }
                        }

        echo '      </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                    </div>

                  </div>
                </div>
              </div>';
     }

     function cargarSeleccionCancionesRecientes ()
     {
         $api = crearWebAPI();

         $opciones = [
             "limit" => 50
         ];

         $canciones = $api->getMyRecentTracks($opciones);

         echo '<div class="modal fade" id="crearPlaylistModal">
                 <div class="modal-dialog modal-dialog-scrollable modal-lg">
                   <div class="modal-content">

                     <div class="modal-header">
                       <h1>Selecciona qué canciones quieres añadir</h1>
                       <button type="button" class="close" data-dismiss="modal">×</button>
                     </div>

                     <div class="modal-body">

                         <div class="todasLasCanciones">
                             <i class="fas fa-plus-circle"></i>
                             <span>Añadir todas las canciones</span>
                         </div>
                         <hr>
                     ';

                     if ($_SESSION["canciones"] == "mismasCanciones")
                     {
                         for ($a = 0; $a < count($canciones->items); $a++)
                         {
                             //Para cada elemento generamos la Estructura
                             echo "<div class='cancionReciente' id='" . $canciones->items[$a]->track->id . "'>";
                                 echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                                 echo "<span class='infoCancion'>";
                                     echo "<img src='" . $canciones->items[$a]->track->album->images[2]->url . "'>";
                                     echo "<span class='resumen'>";
                                         echo "<span class='cancion'>" . $canciones->items[$a]->track->name . "</span>";
                                         echo "<span class='separador'>----</span>";
                                         echo "<span class='artista'>" . $canciones->items[$a]->track->album->artists[0]->name . "</span>";
                                     echo "</span>";
                                 echo "</span>";
                             echo "</div>";
                         }
                     }
                     else
                     {
                         $idsSemillaCanciones = randomizarRecomendaciones( $canciones, "recientes" );
                         $opcionesRecomendaciones = [
                                             "seed_tracks" => $idsSemillaCanciones,
                                             "limit" => 50
                                         ];

                         $recomendaciones = $api->getRecommendations($opcionesRecomendaciones);

                         for ($a = 0; $a < count($recomendaciones->tracks); $a++)
                         {
                             //Para cada elemento generamos la Estructura
                             echo "<div class='cancionReciente' id='" . $recomendaciones->tracks[$a]->id . "'>";
                                 echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                                 echo "<span class='infoCancion'>";
                                     echo "<img src='" . $recomendaciones->tracks[$a]->album->images[2]->url . "'>";
                                     echo "<span class='resumen'>";
                                         echo "<span class='cancion'>" .$recomendaciones->tracks[$a]->name . "</span>";
                                         echo "<span class='separador'>----</span>";
                                         echo "<span class='artista'>" . $recomendaciones->tracks[$a]->album->artists[0]->name . "</span>";
                                     echo "</span>";
                                 echo "</span>";
                             echo "</div>";
                         }
                     }

                 echo '      </div>

                             <!-- Modal footer -->
                             <div class="modal-footer">
                               	<button type="button" class="btn btn-success añadirCanciones" data-dismiss="modal">Añadir mis nuevas canciones</button>
                             </div>

                           </div>
                         </div>
                       </div>';
     }

     function cargarSeleccionTop ($contenido, $rango)
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

        $datosTop = $api->getMyTop($contenido, $opciones);

        echo '<div class="modal fade" id="crearPlaylistModal">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">

                    <div class="modal-header">
                      <h1>Selecciona qué canciones quieres añadir</h1>
                      <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">

                        <div class="todasLasCanciones">
                            <i class="fas fa-plus-circle"></i>
                            <span>Añadir todas las canciones</span>
                        </div>
                        <hr>
                    ';

                    if ($_SESSION["canciones"] == "mismasCanciones")
                    {
                        if ($contenido == "tracks")
                        {
                            for ($a = 0; $a < count($datosTop->items); $a++)
                            {
                                //Para cada elemento generamos la Estructura
                                echo "<div class='cancionReciente' id='" . $datosTop->items[$a]->id . "'>";
                                    echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                                    echo "<span class='infoCancion'>";
                                        echo "<img src='" . $datosTop->items[$a]->album->images[2]->url . "'>";
                                        echo "<span class='resumen'>";
                                            echo "<span class='cancion'>" . $datosTop->items[$a]->name . "</span>";
                                            echo "<span class='separador'>----</span>";
                                            echo "<span class='artista'>" . $datosTop->items[$a]->album->artists[0]->name . "</span>";
                                        echo "</span>";
                                    echo "</span>";
                                echo "</div>";
                            }
                        }
                        else
                        {
                            $contador = 0;

                            foreach ($datosTop->items as $artista)
                            {
                                    $tracks = $api->getArtistTopTracks($artista->id, [
                                        'country' => 'es'
                                    ]);

                                    $canciones = 0;

                                    foreach ($tracks->tracks as $cancion)
                                    {
                                        echo "<div class='cancionReciente' id='" . $cancion->id . "'>";
                                            echo "<span class='numOrden'>" . ($contador + 1) . "</span>";
                                            echo "<span class='infoCancion'>";
                                                echo "<img src='" . $cancion->album->images[2]->url . "'>";
                                                echo "<span class='resumen'>";
                                                    echo "<span class='cancion'>" . $cancion->name . "</span>";
                                                    echo "<span class='separador'>----</span>";
                                                    echo "<span class='artista'>" . $cancion->album->artists[0]->name . "</span>";
                                                echo "</span>";
                                            echo "</span>";
                                        echo "</div>";

                                        $canciones++;
                                        $contador++;

                                        if ($canciones >= 3)
                                            break;
                                  }
                           }
                       }

                    }
                    else
                    {
                        if ($contenido == "tracks")
                        {
                            $idsSemillaCanciones = randomizarRecomendaciones( $datosTop , "topCanciones" );
                            $opcionesRecomendaciones = [
                                                "seed_tracks" => $idsSemillaCanciones,
                                                "limit" => 50
                                            ];

                            $recomendaciones = $api->getRecommendations($opcionesRecomendaciones);
                        }
                        else
                        {
                            $idsSemillaArtistas = randomizarRecomendaciones( $datosTop, "topArtistas" );
                            $opcionesRecomendaciones = [
                                                "seed_artists" => $idsSemillaArtistas,
                                                "limit" => 50
                                            ];

                            $recomendaciones = $api->getRecommendations($opcionesRecomendaciones);
                        }

                        for ($a = 0; $a < count($recomendaciones->tracks); $a++)
                        {
                            //Para cada elemento generamos la Estructura
                            echo "<div class='cancionReciente' id='" . $recomendaciones->tracks[$a]->id . "'>";
                                echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                                echo "<span class='infoCancion'>";
                                    echo "<img src='" . $recomendaciones->tracks[$a]->album->images[2]->url . "'>";
                                    echo "<span class='resumen'>";
                                        echo "<span class='cancion'>" .$recomendaciones->tracks[$a]->name . "</span>";
                                        echo "<span class='separador'>----</span>";
                                        echo "<span class='artista'>" . $recomendaciones->tracks[$a]->album->artists[0]->name . "</span>";
                                    echo "</span>";
                                echo "</span>";
                            echo "</div>";
                        }
                    }

                echo '      </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                               <button type="button" class="btn btn-success añadirCanciones" data-dismiss="modal">Añadir mis nuevas canciones</button>
                            </div>

                          </div>
                        </div>
                      </div>';
     }

     function cargarSeleccionTopArtistas ($rango)
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

        $opciones = [
            "limit" => 50,
            "time_range" => $rango
        ];

        $infoArtistas = $api->getMyTop("artists", $opciones);

        echo '<div class="modal fade" id="crearPlaylistModal">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                  <div class="modal-content">

                    <div class="modal-header">
                      <h1>Selecciona qué canciones quieres añadir</h1>
                      <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>

                    <div class="modal-body">

                        <div class="todasLasCanciones">
                            <i class="fas fa-plus-circle"></i>
                            <span>Añadir todas las canciones</span>
                        </div>
                        <hr>
                    ';

                    if ($_SESSION["canciones"] == "mismasCanciones")
                    {
                        $contador = 0;

                        foreach ($infoArtistas->items as $artista)
                        {
                                $tracks = $api->getArtistTopTracks($artista->id, [
                                    'country' => 'es'
                                ]);

                                $canciones = 0;

                                foreach ($tracks->tracks as $cancion)
                                {
                                    echo "<div class='cancionReciente' id='" . $cancion->id . "'>";
                                        echo "<span class='numOrden'>" . ($contador + 1) . "</span>";
                                        echo "<span class='infoCancion'>";
                                            echo "<img src='" . $cancion->album->images[2]->url . "'>";
                                            echo "<span class='resumen'>";
                                                echo "<span class='cancion'>" . $cancion->name . "</span>";
                                                echo "<span class='separador'>----</span>";
                                                echo "<span class='artista'>" . $cancion->album->artists[0]->name . "</span>";
                                            echo "</span>";
                                        echo "</span>";
                                    echo "</div>";

                                    $canciones++;
                                    $contador++;

                                    if ($canciones >= 3)
                                        break;
                                 }
                         }
                    }
                    else
                    {
                        $idsSemillaArtistas = randomizarRecomendaciones( $infoArtistas, "topArtistas" );
                        $opcionesRecomendaciones = [
                                            "seed_artists" => $idsSemillaArtistas,
                                            "limit" => 50
                                        ];

                        $recomendaciones = $api->getRecommendations($opcionesRecomendaciones);

                        for ($a = 0; $a < count($recomendaciones->tracks); $a++)
                        {
                            //Para cada elemento generamos la Estructura
                            echo "<div class='cancionReciente' id='" . $recomendaciones->tracks[$a]->id . "'>";
                                echo "<span class='numOrden'>" . ($a + 1) . "</span>";
                                echo "<span class='infoCancion'>";
                                    echo "<img src='" . $recomendaciones->tracks[$a]->album->images[2]->url . "'>";
                                    echo "<span class='resumen'>";
                                        echo "<span class='cancion'>" .$recomendaciones->tracks[$a]->name . "</span>";
                                        echo "<span class='separador'>----</span>";
                                        echo "<span class='artista'>" . $recomendaciones->tracks[$a]->album->artists[0]->name . "</span>";
                                    echo "</span>";
                                echo "</span>";
                            echo "</div>";
                        }
                    }

                echo '      </div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                               <button type="button" class="btn btn-success añadirCanciones" data-dismiss="modal">Añadir mis nuevas canciones</button>
                            </div>

                          </div>
                        </div>
                      </div>';
     }

     function crearPlaylist ($lugar = NULL)
     {
        $api = crearWebAPI();
        $seleccionadas = [];

        if ($lugar == "administrar")
        {
            $seleccionadas = $_SESSION["cancionesSeleccionadasAdministrar"];
        }
        else
        {
            $seleccionadas = $_SESSION["cancionesSeleccionadas"];
        }

        if ($_SESSION["playlist"] == "nueva")
        {
            $nomPlaylistNueva = $_SESSION["nombrePlaylist"];

            $api->createPlaylist([
                'name' => $nomPlaylistNueva
            ]);

            $playlists = $api->getUserPlaylists($api->me()->id, [
                'limit' => 50
            ]);

            $playlistID = "";

            foreach ($playlists->items as $playlist)
            {
                if ($playlist->name == $nomPlaylistNueva )
                {
                    $playlistID = $playlist->id;
                }
            }

            for ($a = 0; $a < count($seleccionadas); $a++)
            {
                $api->addPlaylistTracks($playlistID, [
                    $seleccionadas[$a]
                ]);
            }
        }
        else
        {
            for ($a = 0; $a < count($seleccionadas); $a++)
            {
                $api->addPlaylistTracks($_SESSION["playlist"], [
                    $seleccionadas[$a]
                ]);
            }
        }
     }

     function cargarOpcionesAdministracion ()
     {
         echo '<div class="modal fade" id="administrarPlaylistModal">
                 <div class="modal-dialog modal-dialog-scrollable modal-lg">
                   <div class="modal-content">

                     <div class="modal-header">
                       <h1>Indica que tarea quieres realizar</h1>
                       <button type="button" class="close" data-dismiss="modal">×</button>
                     </div>

                     <div class="modal-body">

                         <div class="opcionAdministrar" id="borrarCanciones">
                             <i class="fas fa-trash"></i></i>
                             <span>Borrar las canciones seleccionadas</span>
                         </div>
                         <hr>
                         <div class="opcionAdministrar" id="reemplazarCanciones">
                             <i class="far fa-star"></i>
                             <span>Reemplazar las canciones por otras nuevas</span>
                         </div>
                         <hr>
                         <div class="opcionAdministrar" id="listasTraspasarCanciones">
                             <i class="fas fa-route"></i>
                             <span>Añadir las canciones a otra de mis listas</span>
                         </div>

                     </div>

                     <!-- Modal footer -->
                     <div class="modal-footer">

                     </div>

                   </div>
                 </div>
               </div>';
     }

     function randomizarRecomendacionesAdministrar ( $canciones ) //Devolverá ID aleatorios y no repetidos de objetos del array pasado como parámetro
     {
         $numeroRecomendaciones = 2;
         $posicionesRecogidas = [];
         $idsRecomendaciones = [];

         for ($a = 0; $a < $numeroRecomendaciones; $a++)
         {
             $conmutador = false;
             $posicionRecomendacion = round(rand(0, count($canciones)), 0);

             if ( count($posicionesRecogidas) > 0 )
             {
                 foreach ($posicionesRecogidas as $posicion)
                 {
                    if ($posicion == $posicionRecomendacion)
                    {
                        $conmutador = true;
                    }
                 }

                 if ($conmutador)
                 {
                     $a--;
                 }
                 else
                 {
                     if ($canciones[$posicionRecomendacion] == NULL)
                     {
                          $a--;
                     }
                     else
                     {
                         $posicionesRecogidas[] = $posicionRecomendacion;
                         $idsRecomendaciones[] = $canciones[$posicionRecomendacion];
                     }
                 }
             }
             else
             {
                 if ($canciones[$posicionRecomendacion] == NULL)
                 {
                      $a--;
                 }
                 else
                 {
                     $posicionesRecogidas[] = $posicionRecomendacion;
                     $idsRecomendaciones[] = $canciones[$posicionRecomendacion];
                 }
             }
         }

         return $idsRecomendaciones;
     }

     function borradoMultiple ($playlist, $cancionesSeleccionadas)
     {
         $api = crearWebAPI();

         $cancionesBorrar = [];

         foreach ($cancionesSeleccionadas as $cancionID)
         {
             $arrayID = [
                 "id" => $cancionID
             ];

            $cancionesBorrar[] = $arrayID;
         }

         if ( count($cancionesBorrar) > 100 )
         {
             while ( count($cancionesBorrar) > 0 )
             {
                 $borradoParcial = array_slice($cancionesBorrar, 0, 99);

                 $cancionesAdministrar = [
                    "tracks"  => $borradoParcial
                 ];

                 $api->deletePlaylistTracks($playlist, $cancionesAdministrar); //Borrado de las canciones
             }
         }
         else
         {
             $cancionesAdministrar = [
                "tracks"  => $cancionesBorrar
             ];

             $api->deletePlaylistTracks($playlist, $cancionesAdministrar); //Borrado de las canciones
         }
     }

     function tareaAdministracion ($playlist, $tarea, $cancionesSeleccionadas)
     {
         $api = crearWebAPI();

        if ($tarea == "borrarCanciones")
        {
            borradoMultiple($playlist, $cancionesSeleccionadas);
            cargarCancionesPlaylist($_SESSION["playlist"], 0, "inicio"); //Cargar otra vez la lista una vez borradas las canciones
        }
        else if ($tarea == "listasTraspasarCanciones")
        {
            cargarOpcionesPlaylists();
        }
        else if ($tarea == "traspasarCanciones")
        {
            crearPlaylist("administrar"); //Reutilización de la función para añadir las canciones a otra lista o a una nueva
        }
        else if ($tarea == "reemplazarCanciones")
        {
            if ( count($cancionesSeleccionadas) > 1 )
            {
                $idsSemillaCanciones = randomizarRecomendacionesAdministrar( $cancionesSeleccionadas );

                $limiteRecomendaciones = 0;

                if ( count($cancionesSeleccionadas) < 100 )
                {
                    $limiteRecomendaciones = count($cancionesSeleccionadas);
                }
                else
                {
                    $limiteRecomendaciones = 100;
                }

                $opcionesRecomendaciones = [
                    "seed_tracks" => $idsSemillaCanciones,
                    "limit" => $limiteRecomendaciones
                ];

                $recomendaciones = $api->getRecommendations($opcionesRecomendaciones);

                borradoMultiple($playlist, $cancionesSeleccionadas);

                foreach ($recomendaciones->tracks as $cancion)
                {
                    $api->addPlaylistTracks($playlist, [
                        $cancion->id
                    ]);
                }

                cargarCancionesPlaylist($_SESSION["playlist"], 0, "inicio"); //Cargar otra vez la lista una vez reemplazadas las canciones
            }
            else
            {
                //Indicar al usuario que necesita seleccionar al menos 2 canciones para que las recomendaciones funcionen
            }

        }

     }
?>
