<?php
    require_once 'PHP/operaciones.php';

    if (!isset($_POST["rango"]) && !isset($_POST["contenido"]))
    {
        $_POST["rango"] = "l";
        $_POST["contenido"] = "a";
    }
    else
    {
        $_POST["contenido"] = "a";
    }

?>

<div class="alert alert-success alert-dismissible fade in">
  <a href="#" class="close infoAlert" aria-label="close">&times;</a>
  <strong>¡Las canciones se han añadido correctamente a tu Playlist!</strong>
</div>

<div class="alert alert-warning alert-dismissible fade in">
  <a href="#" class="close infoAlert" aria-label="close">&times;</a>
  <strong>Por favor, selecciona al menos 1 canción para añadir a tu playlist</strong>
</div>

<div>
    <div class="topArtistas">
        <div class="listado">
            <div class="topArtistasIMG"></div>
            <h1 class="topTitulo">Tus Artistas Preferidos</h1>
            <div class="topContenido">
                <div class="topListado">
                    <div class="rangosTiempo">
                        <button id="inicios">Desde tus Inicios</button>
                        <button id="medio">De hace 6 meses</button>
                        <button id="cercano">De este mes</button>
                        <div class="opcionesCrear">
                            <button id="añadirCanciones" style="float:right">Crear Playlist</button>
                        </div>
                    </div>
                    <?php
                        obtenerTop($_POST["contenido"], $_POST["rango"]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded",function() {
        $('#inicios').click( cambiarRango );
        $('#medio').click( cambiarRango );
        $('#cercano').click( cambiarRango );

        $('#inicios').addClass("rangoActivo");

        $('#añadirCanciones').click( opcionesCancionesPlaylist );
        $('.infoAlert').click( ocultarInfoAlert );

        $('.artistaTop').click( cargarArtista );
    });

    function cargarArtista ()
    {
        var idArtista = $(this).attr('artista');
        elemento =  $(this).find('img');
        resumen =  $(this).find('.resumen');

        $.ajax({
            data: {"artista" : idArtista, "operacion" : "cargarArtista"},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            success: function (respuesta) {
                elemento.replaceWith( respuesta );
                resumen.css("margin-left", "1em");
            }
        });
    }

    function cambiarRango(event)
    {
        var rango =  $(this).attr("id");

        $("button.rangoActivo").each(function() {
            $(this).removeClass("rangoActivo");
        });

        $(this).addClass("rangoActivo");

        switch (rango) {
            case "inicios":
                rango = "l";
                break;
            case "medio":
                rango = "m";
                break;
            case "cercano":
                rango = "c";
                break;
            default:
                rango = "l";
                break;
        }

        $.ajax({
            data: {"tiempo" : rango, "operacion" : "cambioRangoArtistas"},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            success: function (respuesta) {
                $(".topListado a").remove();
                $(".topListado").append(respuesta);
                $('.artistaTop').click( cargarArtista )
            }
        });
    }

    function opcionesCancionesPlaylist ()
    {
        $.ajax({
            data: {"operacion" : "cargarOpcionesCanciones"},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            success: function (respuesta) {
                $('#crearPlaylistModal').remove();
                $('body').prepend(respuesta);
                $('#crearPlaylistModal').modal({show:true});
                $('div.opcionCanciones').click( cargarOpcionesPlaylists );
            }
        });
    }

    function cargarOpcionesPlaylists ()
    {
        $.ajax({
            data: {"operacion" : "cargarOpcionesListas", "canciones" : $(this).attr("id")},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            success: function (respuesta) {
                $('#crearPlaylistModal').remove();
                $('body').prepend(respuesta);
                $('#crearPlaylistModal').modal({show:true});
                $('.modal-backdrop.fade.show').css('display', 'none');

                $('div.playlist').click( cargarSeleccionCanciones );
                $('div.nuevaPlaylist i').click( cargarSeleccionCanciones );
                $('div.nuevaPlaylist span').click( cargarSeleccionCanciones );
            }
        });
    }

    function cargarSeleccionCanciones ()
    {
        var playlist = "";
        var nomPlaylist = "";

        if ( $(this).attr("class") == "nuevaPlaylist" )
        {
            playlist = "nueva";
            nomPlaylist = $('#nombreNuevaPlaylist').val();

            if (nomPlaylist == null || nomPlaylist == undefined || nomPlaylist == "")
            {
                nomPlaylist = "Mi TOP de Artistas - MySpot";
            }
        }
        else
        {
            playlist = $(this).attr("id");
        }

        var rangoActivo = $(".rangoActivo").attr('id');

        switch (rangoActivo) {
            case "inicios":
                rangoActivo = "l";
                break;
            case "medio":
                rangoActivo = "m";
                break;
            case "cercano":
                rangoActivo = "c";
                break;
            default:
                rangoActivo = "l";
                break;
        }

        $.ajax({
            data: {"operacion" : "cargarSeleccionTopArtistas", "playlist" : playlist, "nombrePlaylist" : nomPlaylist, "tiempo" : rangoActivo},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            beforeSend: function(){
                $('#crearPlaylistModal .modal-body').empty();
                $('#crearPlaylistModal .modal-body').append("<div class='cargandoContenedor'><img src='IMG/cargando.gif' class='cargaIMG' /></div>");
            },
            success: function (respuesta) {
                $('#crearPlaylistModal').remove();
                $('body').prepend(respuesta);
                $('#crearPlaylistModal').modal({show:true});
                $('.modal-backdrop.fade.show').css('display', 'none');

                $('.cancionReciente').click(function () {
                    if ($(this).hasClass("cancionSeleccionada"))
                    {
                        $(this).removeClass("cancionSeleccionada");
                    }
                    else
                    {
                        $(this).addClass("cancionSeleccionada");
                    }
                });

                $('.todasLasCanciones').click(function () {
                    $('#crearPlaylistModal .cancionReciente').each(function (){
                        $(this).addClass("cancionSeleccionada");
                    });
                });

                $('.añadirCanciones').click(añadirCanciones);

                console.log(nomPlaylist);
            }
        });
    }

    function añadirCanciones ()
    {
        if ( $('.cancionSeleccionada').length > 0)
        {
            var cancionesSeleccionadas = [];

            $('#crearPlaylistModal .cancionSeleccionada').each(function (){
                cancionesSeleccionadas[cancionesSeleccionadas.length] = $(this).attr("id");
            });

            console.log(cancionesSeleccionadas);

            $.ajax({
                data: {"operacion" : "crearPlaylist", "cancionesSeleccionadas" : cancionesSeleccionadas},
                url: "PHP/cargarContenido.php",
                type: "post",
                dataType: 'html',
                beforeSend: function(){
                    $('#crearPlaylistModal').remove();
                    $(".ajax-loader").css('visibility', 'visible');
                },
                success: function (respuesta) {
                    $(".ajax-loader").css('visibility', 'hidden');
                    $(".alert.alert-success.alert-dismissible.fade.in").css('display', 'block');
                    $(".alert.alert-success.alert-dismissible.fade.in").css('opacity', '1');
                    console.log(respuesta);
                }
            });
        }
        else
        {
            $('#crearPlaylistModal').remove();
            $(".alert.alert-warning.alert-dismissible.fade.in").css('display', 'block');
            $(".alert.alert-warning.alert-dismissible.fade.in").css('opacity', '1');
        }
    }

    function ocultarInfoAlert ()
    {
        $(this).parent().hide();
    }

</script>
