<?php

namespace MVC;

class Router
{
    public $rutasGET = [];
    public $rutasPOST = [];

    public function comprobarRutas()
    {
        //$urlActual = $_SERVER["PATH_INFO"] ?? "/";
        $urlActual = strtok($_SERVER["REQUEST_URI"], "?") ?? "/"; // para leer la url sin el query string

        $metodo = $_SERVER["REQUEST_METHOD"];

        if ($metodo === "GET") {
            $funcion = $this->rutasGET[$urlActual] ?? null;
        } elseif ($metodo === "POST") {
            $funcion = $this->rutasPOST[$urlActual] ?? null;
        }

        if ($funcion) {
            call_user_func($funcion, $this);
        } else {
            echo "Página no encontrada";
        }
    }

    public function get($url, $fn)
    {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->rutasPOST[$url] = $fn;
    }

    // mostrar una vista
    public function render($vista, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        // almacenar archivo en memoria
        ob_start();
        include "views/$vista.php";

        $contenido = ob_get_clean();

        include "views/layout.php";
    }
}
