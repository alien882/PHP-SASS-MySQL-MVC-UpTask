<?php

namespace Modelo;

class Proyecto extends ClaseBase
{
    protected static $tabla = "proyectos";
    protected static $columnasDB = ["id", "proyecto", "url", "propietarioId"];

    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->proyecto = $args["proyecto"] ?? "";
        $this->url = $args["url"] ?? "";
        $this->propietarioId = $args["propietarioId"] ?? "";
    }

    public function validar_proyecto()
    {
        if (!$this->proyecto) {
            self::$alertas["error"][] = "El nombre del proyecto es obligatorio";
        }
    }
}
