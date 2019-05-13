<?php
    require_once 'PHP/operaciones.php';
?>

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
            if( $(window).scrollTop() <= $(document).height() - $(window).height() )
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
                }
            });
        }

        console.log( $('.numOrden').last().html() );
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

</script>
