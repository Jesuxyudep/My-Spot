<?php
    require_once 'PHP/operaciones.php';
?>

<div>
    <div class="recientes">
        <div class="listado">
            <div class="recientesIMG"></div>
            <h1 class="recientesTitulo">Escuchadas recientemente</h1>
            <div class="recientesContenido">
                <div class="recientesListado">
                    <div class="recientesCrear">
                        <button id="crearPlaylist" style="float:right">Crear Playlist</button>
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
        $('#crearPlaylist').click( crearPlaylist );
    });

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

</script>
