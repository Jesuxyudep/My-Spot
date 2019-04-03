<?php if ($_SESSION["vista"] != "Login"): ?>

    <div class="navegacion">
        <a class="logo" id="App" href="">
            <img src="IMG/logo.png" alt="MySpot">
        </a>
        <a class="enlaceNavegacion" id="TopCanciones" href="">
            <i class="far fa-heart"></i>
            <span class="etiquetaNavegacion">Top Canciones</span>
        </a>
        <a class="enlaceNavegacion" id="TopArtistas" href="">
            <i class="fas fa-crown"></i>
            <span class="etiquetaNavegacion">Top artistas</span>
        </a>
        <a class="enlaceNavegacion" id="Recientes" href="">
            <i class="far fa-clock"></i>
            <span class="etiquetaNavegacion">Recientes</span>
        </a>
        <a class="enlaceNavegacion" id="Administrar" href="">
            <i class="fab fa-wpforms"></i>
            <span class="etiquetaNavegacion">Administrar</span>
        </a>
    </div>

<?php endif ?>
