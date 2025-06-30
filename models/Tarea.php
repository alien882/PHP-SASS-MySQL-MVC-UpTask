<?php

namespace Modelo;

class Tarea extends ClaseBase
{
    protected static $tabla = "tareas";
    protected static $columnasDB = ["id", "nombre", "estado", "proyectosId"];

    public $id;
    public $nombre;
    public $estado;
    public $proyectosId;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->nombre = $args["nombre"] ?? "";
        $this->estado = $args["estado"] ?? 0;
        $this->proyectosId = $args["proyectosId"] ?? "";
    }
}
