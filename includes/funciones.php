<?php

function debuguear($variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function escaparHtml(string $html): string
{
    $satinizado = htmlspecialchars($html);

    return $satinizado;
}


function estaAutenticado()
{
    session_start();

    if (!$_SESSION["login"]) {
        header("location: /");
    }
}

function ultimaPosicion($posicionActual, $posicionProxima): bool
{
    if ($posicionActual !== $posicionProxima) {
        return true;
    } else {
        return false;
    }
}

function esAdministrador()
{
    if (!$_SESSION["admin"]) {
        header("location: /");
    }
}
