<?php if ($_SESSION["vista"] != "Login"): ?>

    <div class="navegacion">
        <a class="logo" id="App" href="">
            <img src="IMG/logo.png" alt="MySpot">
        </a>
        <a id="App" href="">
            <h1>MySpot</h1>
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
        <?php
            mostrarFotoPerfil();
        ?>
        <ul class="dropdown-menu dropdown-menu-right">
                <li class="inicio"><a id="App" href=""><i class="fas fa-home"></i>Inicio</a></li>
                <li><a href="<?php linkPerfil(); ?>" target="_blank"><i class="far fa-user-circle"></i>Cuenta</a></li>
                <li class="cambiarTema"><span><i class="fas fa-cloud-moon"></i>Cambiar Tema</span></li>
                <li><a href="" data-toggle="modal" data-target="#privacidadModal"><i class="fas fa-key"></i>Privacidad</a></li>
                <li><a href="PHP/logout.php"><i class="fas fa-sign-out-alt"></i>Cerrar Sesión</a></li>
        </ul>
    </div>

<?php endif ?>
