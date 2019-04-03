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
    <div class="contenedor">
        <?php require('PHP/Estructura/appNavegation.php'); ?>
        <div class="contenido">
            <?php
                //Cargará el contenido de una vista u otra en función de lo que seleccione el usuario
                cargarVista($_SESSION["vista"]);
            ?>
        </div>
    </div>
</body>

<script>

    document.addEventListener("DOMContentLoaded",function() {
        $('a').click( cambiarVista );
    });

    function cambiarVista(event)
    {
        var vista =  $(this).attr("id");

        $.ajax({
            data: {"vista" : vista, "operacion" : "cambioVista"},
            url: "PHP/cargarContenido.php",
            type: "post"
        });
    }

</script>

</html>
