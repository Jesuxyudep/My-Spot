
<div>
    <div class="container">
        <h1 style="text-align: center" class="tituloInicio">MySpot - Cambia cómo descubres nueva música</h1>
        <div class="container" style="text-align: center"><strong style="text-align: center; font-size: 1.5em">¿Qué puedes hacer con esta aplicación?</strong></div>
        <ul style="list-style: none" class="listaInicial">
            <li><i class="far fa-gem"></i>Con MySpot puedes encontrar tus antiguas gemas de Spotify. Algunas de las canciones que quizás ya hayas olvidado.</li>
            <li><i class="fab fa-gratipay"></i>Refresca tus recuerdos y crea playlists a partir de tus canciones y artistas favoritos o de las canciones que has escuchado recientemente.</li>
            <li><i class="fas fa-tools"></i>Administra tus playlist rápidamente de una manera cómoda y simple</li>
            <li><i class="fas fa-headphones-alt"></i>Descubre nueva música de manera automática sin tener que preocuparte de nada más que escucharla</li>
        </ul>
    </div>

    <br>
    <hr></hr>
    <br>

    <?php
        obtenerPlaylistDestacadas();
    ?>

    <br>
    <hr></hr>
    <br>

    <h2 style="text-align: center" class="tituloInicio">Recomendaciones hechas a medida:</h2>
    <div class="container" style="text-align: center; margin-bottom: 15px;"><strong style="text-align: center; font-size: 1.4em">Estas son nuestras recomendaciones de hoy en función de la música que más escuchas:</strong></div>
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
    });

</script>
