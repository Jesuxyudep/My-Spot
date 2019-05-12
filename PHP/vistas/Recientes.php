<?php
    require_once 'PHP/operaciones.php';
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
    <div class="recientes">
        <div class="listado">
            <div class="recientesIMG"></div>
            <h1 class="recientesTitulo">Escuchadas recientemente</h1>
            <div class="recientesContenido">
                <div class="recientesListado">
                    <div class="recientesCrear">
                        <!--button id="crearPlaylist" style="float:right">Crear Playlist</button-->
                        <button id="añadirCanciones" style="float:right">Crear Playlist</button>
                    </div>
                    <?php
                        obtenerRecientes();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded",function() {
        $('.infoAlert').click( ocultarInfoAlert );
        $('#añadirCanciones').click( opcionesCancionesPlaylist );

        $('.cancionReciente').click( cargarCancion )
    });

    function cargarCancion ()
    {
        var idCancion = $(this).attr('cancion');
        elemento =  $(this).find('img');
        resumen =  $(this).find('.resumen');

        $.ajax({
            data: {"cancion" : idCancion, "operacion" : "cargarCancion"},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            success: function (respuesta) {
                elemento.replaceWith( respuesta );
                resumen.css("margin-left", "1em");
            }
        });
    }

    function crearPlaylist ()
    {
        $.ajax({
            data: {"tiempo" : "", "operacion" : "crearPlaylistRecientes"},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            beforeSend: function(){
                $(".ajax-loader").css('visibility', 'visible');
            },
            success: function (respuesta) {
                $(".ajax-loader").css('visibility', 'hidden');
                alert("¡Tu nueva playlist ha sido creada!");
                $(".recientesListado").append(respuesta);
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
                nomPlaylist = "Mis canciones Recientes - MySpot";
            }
        }
        else
        {
            playlist = $(this).attr("id");
        }

        $.ajax({
            data: {"operacion" : "cargarSeleccionCancionesRecientes", "playlist" : playlist, "nombrePlaylist" : nomPlaylist},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
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
