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
    else if ($tarea == "cargarOpcionesCanciones")
    {
        cargarOpcionesCanciones();
    }
    else if ($tarea == "cargarOpcionesListas")
    {
        $_SESSION["canciones"] = $_POST["canciones"];
        cargarOpcionesPlaylists();
    }
    else if ($tarea == "cargarSeleccionCancionesRecientes")
    {
        $_SESSION["playlist"] = $_POST["playlist"];
        $_SESSION["nombrePlaylist"] = $_POST["nombrePlaylist"];
        cargarSeleccionCancionesRecientes();
    }
    else if ($tarea == "cargarSeleccionTopCanciones")
    {
        $_SESSION["playlist"] = $_POST["playlist"];
        $_SESSION["nombrePlaylist"] = $_POST["nombrePlaylist"];
        $_SESSION["rango"] = $_POST["tiempo"];

        cargarSeleccionTop('c', $_SESSION["rango"]);
    }
    else if ($tarea == "cargarSeleccionTopArtistas")
    {
        $_SESSION["playlist"] = $_POST["playlist"];
        $_SESSION["nombrePlaylist"] = $_POST["nombrePlaylist"];
        $_SESSION["rango"] = $_POST["tiempo"];

        cargarSeleccionTop('a', $_SESSION["rango"]);
    }
    else if ($tarea == "crearPlaylist")
    {
        $_SESSION["cancionesSeleccionadas"] = $_POST["cancionesSeleccionadas"];
        crearPlaylist();
    }
    else if ($tarea == "cargarCancionInicio")
    {
        $_SESSION["cancionReproducir"] = $_POST["cancion"];
        iframeInicio();
    }
    else if ($tarea == "cargarArtista")
    {
        $_SESSION["artistaReproducir"] = $_POST["artista"];
        iframeArtista();
    }
    else if ($tarea == "cargarCancion")
    {
        $_SESSION["cancionReproducir"] = $_POST["cancion"];
        iframeCancion();
    }
    //Administrar
    else if ($tarea == "cargarCancionesPlaylist")
    {
        $_SESSION["playlist"] = $_POST["playlist"];
        cargarCancionesPlaylist($_SESSION["playlist"], $_POST["offset"]);
    }
    else if ($tarea == "cargarMasCancionesPlaylist")
    {
        cargarCancionesPlaylist($_SESSION["playlist"], $_POST["offset"]);
    }
}


?>
