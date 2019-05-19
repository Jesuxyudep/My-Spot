<?php
require_once 'PHP/resources/vendor/autoload.php';
session_start();

if ( !isset($_SESSION["logueado"]) )
{
    //Mostrar contenido de página de login directamente en caso de que no esté logueado
    $_SESSION["vista"] = "Login";
}

if ($_SESSION["vista"] == NULL)
{
    $_SESSION["vista"] = $_SESSION["bckpVista"];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php
        require('PHP/operaciones.php');
        require('PHP/Estructura/head.php');

        if ($_SESSION["vista"] != "Login")
        {
            require('PHP/actualizador.php');
        }

        $_SESSION["bckpVista"] = $_SESSION["vista"];
    ?>
    <!-- Obtener título en función de la página en la que nos encontremos -->
    <title><?php cargarTitulo($_SESSION["vista"]) ?></title>

</head>
<body>

    <!-- Modal de los datos de privacidad -->
    <div class="modal fade" id="privacidadModal">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">

            <div class="modal-header">
              <h1 style="text-align: center; width: 100%">MySpot - Privacidad</h1>
              <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
              <h2 style="text-align: center">He entrado con una cuenta de Spotify equivocada</h2>
                  <p>No te preocupes, sólo tienes que pulsar en el botón de cerrar sesión de tu perfil que te llevará a <a href="http://accounts.spotify.com/" target="_blank" rel="noopener noreferrer">accounts.spotify.com</a> donde debes pulsar el botón Cerrar sesión. <br><br> A continuación, vuelve a <a href="http://myspot.epizy.com/">MySpot</a> e inicia sesión con tu cuenta.</p>

                  <hr>

              <h2 style="text-align: center">Privacidad</h2>
                  <p>MySpot guarda los mínimos datos personales necesarios para poder identificarte cuando accedas en un futuro. Si tienes algún inconveniente o quieres obtener más información contacta con nosotros a través del siguiente correo: <a href="mailto:privacidad@myspot.com">privacidad@myspot.com</a></p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              	<button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
            </div>

          </div>
        </div>
      </div>

      <!-- Modal mostrado al querer añadir canciones a playlist -->
      <div class="modal fade" id="crearPlaylistModal">
          <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

              <div class="modal-header">
                <h1>Creando tu nueva playlist favorita</h1>
                <button type="button" class="close" data-dismiss="modal">×</button>
              </div>

              <div class="modal-body">

              </div>

              <!-- Modal footer -->
              <div class="modal-footer">
                	<button type="button" class="btn btn-success disabled" data-dismiss="modal">Añadir mis nuevas canciones</button>
              </div>

            </div>
          </div>
        </div>

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
                echo "<!--p>" . $_SESSION["vista"] . "<p-->";
                //Cargará el contenido de una vista u otra en función de lo que seleccione el usuario
                cargarVista($_SESSION["vista"]);
            ?>
        </div>

        <button id="toTop" title="Volver arriba"><i class="fas fa-chevron-up"></i></button>
    </div>

    <?php
        if ($_SESSION["vista"] != "Login")
            require('PHP/Estructura/footer.php');
    ?>

</body>

<script>

    document.addEventListener("DOMContentLoaded",function() {
        $('.enlaceNavegacion').click( cambiarVista );
        $('.navegacion a').click( cambiarVista );

        $("#toTop").click( volverArriba );
        window.onscroll = function() {mostrarToTop()};

        $('#<?php echo $_SESSION["vista"]?>').addClass("activo");

        $(".perfil").click( mostrarInfoPerfil );

        ponerTemaUsuario();

    });

    function cambiarVista(event)
    {
        var vista =  $(this).attr("id");

        console.log(vista);

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
          if (document.body.scrollTop > 1 || document.documentElement.scrollTop > 1)
          {
            $("#toTop").show();
          }
          else
          {
            $("#toTop").hide();
          }
    }

    function mostrarInfoPerfil ()
    {
        if ($("ul.dropdown-menu.dropdown-menu-right").is(':visible'))
        {
            $("ul.dropdown-menu.dropdown-menu-right").fadeOut();
        }
        else
        {
            $("ul.dropdown-menu.dropdown-menu-right").fadeIn();
        }
    }

    function volverArriba ()
    {
        document.body.scrollTop = 0; //Safari
        document.documentElement.scrollTop = 0; //Chrome, Firefox, IE y Opera
    }

    function ponerTemaUsuario ()
    {
        var cuerpo = $('body');
        var temaAlmacenado = localStorage.getItem("tema");

        if(temaAlmacenado == 'oscuro')
        {
            $(cuerpo).addClass( "oscuro" );
        }

        $(".cambiarTema").click(function() {
              $( cuerpo ).toggleClass( "oscuro" );
              comprobarTema();
        });
    }

    function comprobarTema ()
    {
        var temaAlmacenado = localStorage.getItem("tema");

        if(temaAlmacenado == 'oscuro')
        {
            localStorage.setItem("tema", "claro");
        }
        else if(temaAlmacenado == 'claro')
        {
            localStorage.setItem("tema", "oscuro");
        }
        else if(temaAlmacenado == null)
        {
            localStorage.setItem("tema", "oscuro");
        }
    }

</script>

</html>
