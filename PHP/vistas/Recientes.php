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
                    <?php
                        obtenerRecientes();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    /*document.addEventListener("DOMContentLoaded",function() {
        $('button').click( cambiarRango );
    });

    function cambiarRango(event)
    {
        var rango =  $(this).attr("id");

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
            }
        });
    }*/

</script>
