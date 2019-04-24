<?php
session_start();

if ( !isset($_SESSION["logueado"]) || $_SESSION["vista"] == "" )
{
    //Mostrar contenido de página de login directamente en caso de que no esté logueado
    $_SESSION["vista"] = "Login";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        require('PHP/operaciones.php');
        require('PHP/Estructura/head.php');
    ?>
    <!-- Obtener título en función de la página en la que nos encontremos -->
    <title><?php cargarTitulo($_SESSION["vista"]) ?></title>

</head>
<body>

    <div class="ajax-loader">
        <div>
            <img src="IMG/cargando.gif">
            <h4>Creando tu nueva playlist favorita...</h4>
        </div>
    </div>

    <div class="contenedor">
        <?php require('PHP/Estructura/appNavegation.php'); ?>
        <div class="contenido">
            <?php
                //Cargará el contenido de una vista u otra en función de lo que seleccione el usuario
                cargarVista($_SESSION["vista"]);
            ?>
        </div>

        <button id="toTop" title="Volver arriba"><i class="fas fa-chevron-up"></i></button>
    </div>
</body>

<script>

    document.addEventListener("DOMContentLoaded",function() {
        $('a').click( cambiarVista );
        $("#toTop").click( volverArriba );
        $('#<?php echo $_SESSION["vista"]?>').addClass("activo");
        window.onscroll = function() {mostrarToTop()};
    });

    function cambiarVista(event)
    {
        var vista =  $(this).attr("id");

        $("a.activo").each(function() {
            $(this).removeClass("activo");
        })

        $.ajax({
            data: {"vista" : vista, "operacion" : "cambioVista"},
            url: "PHP/cargarContenido.php",
            type: "post"
        });
    }

    function mostrarToTop ()
    {
          if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20)
          {
            $("#toTop").show();
          }
          else
          {
            $("#toTop").hide();
          }
    }

    function volverArriba ()
    {
        document.body.scrollTop = 0; //Safari
        document.documentElement.scrollTop = 0; //Chrome, Firefox, IE y Opera
    }

</script>

</html>
