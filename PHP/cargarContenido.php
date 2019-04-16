<?php
session_start();
require_once 'operaciones.php';

//Fichero en el que se cambia la vista a cargar en el index y que permite cargar los distintos contenidos dentro de una misma sección (rangos de TOP)

if ($_POST)
{
    $tarea = $_POST["operacion"];

    if ($tarea == "cambioVista")
    {
        $_SESSION["vista"] = $_POST["vista"];
    }
    else if ($tarea == "cambioRangoCanciones")
    {
        $_POST["rango"] = $_POST["tiempo"];

        obtenerTop("c", $_POST["rango"]);
    }
    else if ($tarea == "cambioRangoArtistas")
    {
        $_POST["rango"] = $_POST["tiempo"];

        obtenerTop("a", $_POST["rango"]);
    }
    else if ($tarea == "crearPlaylistTop")
    {
        $_POST["rango"] = $_POST["tiempo"];

        crearPlaylistTop($_POST["rango"]);
    }
}


?>
