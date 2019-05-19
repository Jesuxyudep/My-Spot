<?php
    require_once 'PHP/operaciones.php';
?>

<div class="alert alert-success alert-dismissible fade in">
  <a href="#" class="close infoAlert" aria-label="close">&times;</a>
  <strong>¡La administración de tu playlist se ha realizado correctamente!</strong>
</div>

<div class="alert alert-warning alert-dismissible fade in noCanciones">
  <a href="#" class="close infoAlert" aria-label="close">&times;</a>
  <strong>Por favor, selecciona al menos 1 canción para poder administrarla</strong>
</div>

<div class="alert alert-warning alert-dismissible fade in reemplazarCanciones">
  <a href="#" class="close infoAlert" aria-label="close">&times;</a>
  <strong>Por favor, selecciona más de 1 canción para poder reemplazar correctamente las canciones por recomendaciones</strong>
</div>

<div>
    <div class="administrar">
        <div class="listado">
            <div class="administrarIMG"></div>
            <h1 class="administrarTitulo">Administrar mis playlists</h1>
            <div class="administrarContenido">
                <div class="administrarListado">
                    <?php
                        obtenerPlaylists();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.addEventListener("DOMContentLoaded",function() {
        $('.playlist').click( cargarCancionesPlaylist );

        $(window).scroll(function() {
            if( $(window).scrollTop() == $(document).height() - $(window).height() )
            {
                //Cargar más canciones al llegar al final
                cargarMasCancionesPlaylist();
            }
        });

    });

    function cargarCancionesPlaylist ()
    {
        var idPlaylist = $(this).attr('id');

        $.ajax({
            data: {"playlist" : idPlaylist, "offset" : 0 ,"operacion" : "cargarCancionesPlaylist"},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            success: function (respuesta) {
                $(".administrarListado a").remove();
                $(".administrarListado").append(respuesta);

                $('.cancionAdministrar').click( cargarCancion );
                $('.cancionAdministrar').click( seleccionCancion );

                $('#administrarCanciones').click( opcionesAdministracionPlaylist );
            }
        });
    }

    function cargarMasCancionesPlaylist ()
    {
        if ( $('.playlist').length == 0 )
        {
            var offset = $('.numOrden').last().html();

            $.ajax({
                data: {"offset" : offset ,"operacion" : "cargarMasCancionesPlaylist"},
                url: "PHP/cargarContenido.php",
                type: "post",
                dataType: 'html',
                success: function (respuesta) {
                    $(".administrarListado").append(respuesta);

                    $('.cancionAdministrar').click( cargarCancion );
                    $('.cancionAdministrar').click( seleccionCancion );
                }
            });
        }
    }

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

    function seleccionCancion ()
    {
        if ($(this).hasClass("cancionSeleccionada"))
        {
            $(this).removeClass("cancionSeleccionada");
        }
        else
        {
            $(this).addClass("cancionSeleccionada");
        }
    }

    function opcionesAdministracionPlaylist ()
    {
        if ( $('.cancionSeleccionada').length <= 0 )
        {
            $(".alert.alert-warning.alert-dismissible.fade.in.noCanciones").css('display', 'block');
            $(".alert.alert-warning.alert-dismissible.fade.in.noCanciones").css('opacity', '1');

            document.body.scrollTop = 0; //Safari
            document.documentElement.scrollTop = 0; //Chrome, Firefox, IE y Opera
        }
        else
        {
            $(".alert.alert-warning.alert-dismissible.fade.in").css('display', 'none');
            $(".alert.alert-warning.alert-dismissible.fade.in").css('opacity', '0');

            $.ajax({
                data: {"operacion" : "cargarOpcionesAdministracion"},
                url: "PHP/cargarContenido.php",
                type: "post",
                dataType: 'html',
                success: function (respuesta) {
                    $('body').prepend(respuesta);
                    $('#administrarPlaylistModal').modal({show:true});

                    $('div.opcionAdministrar').click( administrarPlaylist );
                }
            });
        }
    }

    function administrarPlaylist ()
    {
        var cancionesSeleccionadas = [];

        $('.cancionSeleccionada').each(function (){
            cancionesSeleccionadas[cancionesSeleccionadas.length] = $(this).attr("cancion");
        });

        var tarea = $(this).attr('id');

        if (tarea == "reemplazarCanciones" && cancionesSeleccionadas.length < 2)
        {
            $(".alert.alert-warning.alert-dismissible.fade.in.reemplazarCanciones").css('display', 'block');
            $(".alert.alert-warning.alert-dismissible.fade.in.reemplazarCanciones").css('opacity', '1');

            document.body.scrollTop = 0; //Safari
            document.documentElement.scrollTop = 0; //Chrome, Firefox, IE y Opera
        }
        else
        {
            $.ajax({
                data: {"operacion" : "tareaAdministracion", "cancionesSeleccionadasAdministrar" : cancionesSeleccionadas, "tarea" : tarea},
                url: "PHP/cargarContenido.php",
                type: "post",
                dataType: 'html',
                beforeSend: function (){
                    $('#administrarPlaylistModal .modal-body').empty();
                    $('#administrarPlaylistModal .modal-body').append("<div class='cargandoContenedor'><img src='IMG/cargando.gif' class='cargaIMG' /></div>");
                },
                success: function (respuesta) {
                    $('#administrarPlaylistModal').remove();
                    $('.modal-backdrop.fade.show').css('display', 'none');

                    console.log(respuesta);

                    if (tarea == "borrarCanciones" || tarea == "reemplazarCanciones")
                    {
                        $(".administrarListado").empty();
                        $(".administrarListado").append(respuesta);

                        $('.cancionAdministrar').click( cargarCancion );
                        $('.cancionAdministrar').click( seleccionCancion );

                        $('#administrarCanciones').click( opcionesAdministracionPlaylist );
                    }
                    else if (tarea == "listasTraspasarCanciones")
                    {
                        $('#crearPlaylistModal').remove();
                        $('body').prepend(respuesta);
                        $('#crearPlaylistModal').modal({show:true});

                        $('div.playlist').click( traspasarCanciones );
                        $('div.nuevaPlaylist i').click( traspasarCanciones );
                        $('div.nuevaPlaylist span').click( traspasarCanciones );
                    }

                    $(".alert.alert-success.alert-dismissible.fade.in").css('display', 'block');
                    $(".alert.alert-success.alert-dismissible.fade.in").css('opacity', '1');
                }
            });
        }
    }

    function traspasarCanciones ()
    {
        var playlist = "";
        var nomPlaylist = "";

        if ( $(this).attr("class") == "nuevaPlaylist" )
        {
            playlist = "nueva";
            nomPlaylist = $('#nombreNuevaPlaylist').val();

            if (nomPlaylist == null || nomPlaylist == undefined || nomPlaylist == "")
            {
                nomPlaylist = "Mi nueva lista preferida - MySpot";
            }
        }
        else
        {
            playlist = $(this).attr("id");
        }

        $.ajax({
            data: {"operacion" : "tareaAdministracion", "tarea" : "traspasarCanciones", "playlist" : playlist, "nombrePlaylist" : nomPlaylist},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            beforeSend: function(){
                $('#crearPlaylistModal .modal-body').empty();
                $('#crearPlaylistModal .modal-body').append("<div class='cargandoContenedor'><img src='IMG/cargando.gif' class='cargaIMG' /></div>");
            },
            success: function (respuesta) {
                $('#crearPlaylistModal').remove();
                $('.modal-backdrop.fade.show').css('display', 'none');

                $(".alert.alert-success.alert-dismissible.fade.in").css('display', 'block');
                $(".alert.alert-success.alert-dismissible.fade.in").css('opacity', '1');
                console.log(respuesta);
            }
        });
    }

</script>
