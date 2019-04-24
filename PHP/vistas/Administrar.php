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
