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
    }

</script>
