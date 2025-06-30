<?php

namespace Controlador;

use Modelo\Proyecto;
use Modelo\Tarea;

class TareaController
{
    public static function index()
    {
        session_start();
        $proyectoId = $_GET["id"] ?? "";

        if (!$proyectoId) {

            $respuesta = [
                "tipo" => "error",
                "mensaje" => "no hay un id"
            ];

            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
            return;
        }

        $proyecto = Proyecto::where("url", $proyectoId);

        if (!$proyecto || $proyecto->propietarioId !== $_SESSION["id"]) {

            $respuesta = [
                "tipo" => "error",
                "mensaje" => "no existe"
            ];

            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
            return;
        }

        $tareas = Tarea::whereAll("proyectosId", $proyecto->id);
        echo json_encode(["tareas" => $tareas], JSON_UNESCAPED_UNICODE);
    }

    public static function crear_tarea()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            session_start();

            $proyecto = Proyecto::where("url", $_POST["proyectosId"]);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION["id"]) {

                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un error al agregar la tarea"
                ];

                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectosId = $proyecto->id;
            $resultado = $tarea->guardar();

            $respuesta = [
                "id" => $resultado["id"],
                "tipo" => "exito",
                "mensaje" => "Tarea agregada correctamente",
                "proyectoId" => $proyecto->id
            ];

            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }
    }

    public static function actualizar_tarea()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            session_start();

            $proyecto = Proyecto::where("url", $_POST["proyectosId"]);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION["id"]) {

                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un error"
                ];

                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
                return;
            }

            $tarea = new Tarea($_POST);
            $tarea->proyectosId = $proyecto->id;
            $resultado = $tarea->guardar();

            if ($resultado) {

                $respuesta = [
                    "id" => $tarea->id,
                    "tipo" => "exito",
                    "proyectoId" => $proyecto->id,
                    "mensaje" => "Acualizado correctamente"
                ];

                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public static function eliminar_tarea()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            session_start();

            $proyecto = Proyecto::where("url", $_POST["proyectosId"]);

            if (!$proyecto || $proyecto->propietarioId !== $_SESSION["id"]) {

                $respuesta = [
                    "tipo" => "error",
                    "mensaje" => "Hubo un error"
                ];

                echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
                return;
            }

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $respuesta = [
                "exito" => $resultado,
                "tipo" => "exito",
                "mensaje" => "Eliminado correctamente"
            ];
            echo json_encode($respuesta, JSON_UNESCAPED_UNICODE);
        }
    }
}
