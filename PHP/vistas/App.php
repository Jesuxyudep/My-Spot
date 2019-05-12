
<div>
    <section class="grid-container">
      <h1 style="text-align: center" class="tituloInicio">MySpot - Cambia cómo descubres nueva música</h1>
      <div class="container" style="text-align: center"><strong style="text-align: center; font-size: 1.5em">¿Qué puedes hacer con esta aplicación?</strong></div>
      <div class="cols grid-area">
        <figure class="col grid-1">
          <img src="IMG/IMGgemas.jpg" alt="">
          <figcaption>
            <h2>Recuerda</h2>
            <div class="grid-button-wrapper">
              <button class="rww_grid_button">Ver más</button>
            </div>
            <p>Con MySpot puedes encontrar tus antiguas gemas de Spotify.</p>
            <a class="enlaceNavegacion" id="TopCanciones" href="">View More</a>
          </figcaption>
        </figure>
        <figure class="col grid-2">
          <img src="IMG/IMGAdministrar.jpg" alt="">
          <figcaption>
            <h2>Administra</h2>
            <div class="grid-button-wrapper">
              <button class="rww_grid_button">Ver más</button>
            </div>
            <p>Maneja tus listas cómoda y rápidamente.</p>
            <a class="enlaceNavegacion" id="Administrar" href="">View More</a>
          </figcaption>
      </figure>
        <figure class="col grid-3">
          <img src="IMG/IMGnuevaMusica.jpg" alt="">
          <figcaption>
            <h2>Descubre</h2>
            <div class="grid-button-wrapper">
              <button class="rww_grid_button">Ver más</button>
            </div>
            <p>Descubre nueva música sin tener que preocuparte de nada más que escucharla.</p>
            <a href="#recomendaciones">View More</a>
          </figcaption>
        </figure>
        <figure class="col grid-6">
          <figcaption>
               <img src="IMG/IMGSocial.jpg" alt="">
               <h2>Síguenos</h2>
               <div class="grid-button-wrapper">
                 <button class="rww_grid_button">Ver más</button>
               </div>
               <p>Sé el primero en conocer las próximas novedades.</p>
               <a href="#social">View More</a>
         </figcaption>
       </figure>
        <figure class="col grid-7">
         <figcaption>
          <img src="IMG/IMGFavoritas.jpg" alt="">
          <h2>Tus favoritos</h2>
          <div class="grid-button-wrapper">
            <button class="rww_grid_button">Ver más</button>
          </div>
          <p>Crea playlists a partir de tus canciones y artistas favoritos o de las canciones que has escuchado recientemente.</p>
          <a class="enlaceNavegacion" id="TopArtistas" href="">View More</a>
       </figcaption>
      </figure>
    </section>

    <br>
    <hr></hr>
    <br>

    <?php
        obtenerPlaylistDestacadas();
    ?>

    <br>
    <hr></hr>
    <br>

    <h2 style="text-align: center" class="tituloInicio" id="recomendaciones">Recomendaciones creadas solo para tí</h2>
    <div class="container" style="text-align: center; margin-bottom: 15px;"><strong style="text-align: center; font-size: 1.4em">¡Puedes escuchar cualquiera de las canciones pulsando sobre ellas!</strong></div>
    <?php
        obtenerRecomendaciones( "tops" );
    ?>

</div>

<script>

    $(document).ready(function(){
        $('.customer-logos').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 1500,
            arrows: false,
            dots: false,
            pauseOnHover: false,
            responsive: [{
                breakpoint: 768,
                settings: {
                    slidesToShow: 4
                }
            }, {
                breakpoint: 520,
                settings: {
                    slidesToShow: 3
                }
            }]
        });

        $('.recomendacion').click( cargarCancion );
    });

    function cargarCancion ()
    {
        var idCancion = $(this).attr('cancion');
        var padre = $(this).parent();
        var elemento = $(this);

        $.ajax({
            data: {"cancion" : idCancion, "operacion" : "cargarCancionInicio"},
            url: "PHP/cargarContenido.php",
            type: "post",
            dataType: 'html',
            success: function (respuesta) {
                padre.append( respuesta );
                elemento.unbind("click");
            }
        });
     }

</script>
