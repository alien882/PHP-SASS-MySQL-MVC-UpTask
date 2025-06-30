<?php

namespace Modelo;

use mysqli;

class ClaseBase
{
    protected static mysqli $db;
    protected static $columnasDB = [];
    protected static $tabla = "";

    protected static $alertas = [];

    public static function setBaseDeDatos($database)
    {
        self::$db = $database;
    }

    public static function setAlerta($tipo, $mensaje)
    {
        static::$alertas[$tipo][] = $mensaje;
    }


    public function guardar()
    {
        if (!empty($this->id)) {
            $resultado = $this->actualizar();
        } else {
            $resultado = $this->crear();
        }

        return $resultado;
    }

    public function actualizar()
    {
        $atributosLimpios = $this->sanitizarDatos();

        $valores = [];

        foreach ($atributosLimpios as $key => $value) {
            $valores[] = "$key = '$value'";
        }

        $textoValores = join(", ", $valores);

        $actualizarRegistro = "UPDATE " . static::$tabla . " SET $textoValores WHERE id = " . self::$db->escape_string($this->id);

        $resultado = self::$db->query($actualizarRegistro);

        return $resultado;
    }

    public function crear()
    {
        $atributosLimpios = $this->sanitizarDatos();

        $textoCampos = join(", ", array_keys($atributosLimpios));
        $textoValores = join("', '", array_values($atributosLimpios));

        $crearRegistro = "INSERT INTO " . static::$tabla . " ($textoCampos) VALUES ('$textoValores')";

        $resultado = self::$db->query($crearRegistro);

        return [
            'resultado' =>  $resultado,
            'id' => self::$db->insert_id
        ];
    }

    public function eliminar()
    {
        $eliminarRegistro = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id);
        $resultado = self::$db->query($eliminarRegistro);

        return $resultado;
    }

    public function atributos()
    {
        $atributos = [];

        foreach (static::$columnasDB as $columna) {

            if ($columna === "id") {
                continue;
            }

            $atributos[$columna] = $this->$columna;
        }

        return $atributos;
    }

    public function sanitizarDatos()
    {
        $atributos = $this->atributos();

        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    public static function getAlertas()
    {
        return static::$alertas;
    }

    public static function all()
    {
        $obtenerRegistros = "SELECT * FROM " . static::$tabla;
        $arreglo = self::consultarSQL($obtenerRegistros);
        return $arreglo;
    }

    public static function get($cantidad)
    {
        $obtenerRegistros = "SELECT * FROM " . static::$tabla . " LIMIT $cantidad";
        $arreglo = self::consultarSQL($obtenerRegistros);
        return $arreglo;
    }

    public static function find($id)
    {
        $obtenerRegistro = "SELECT * FROM " . static::$tabla . " WHERE id = $id";
        $arregloObjetos = self::consultarSQL($obtenerRegistro);
        $objetoRegistro = $arregloObjetos[0];
        return $objetoRegistro;
    }

    public static function where($campo, $valor)
    {
        $obtenerRegistro = "SELECT * FROM " . static::$tabla . " WHERE $campo = '$valor'";
        $arregloObjetos = self::consultarSQL($obtenerRegistro);
        $objetoRegistro = $arregloObjetos[0] ?? null;
        return $objetoRegistro;
    }

    public static function whereAll($campo, $valor)
    {
        $obtenerRegistro = "SELECT * FROM " . static::$tabla . " WHERE $campo = '$valor'";
        $arregloObjetos = self::consultarSQL($obtenerRegistro);
        return $arregloObjetos;
    }

    public static function consultarSQL(string $consulta)
    {
        $resultado = self::$db->query($consulta);

        $arreglo = [];

        foreach ($resultado->fetch_all(MYSQLI_ASSOC) as $registro) {
            $arreglo[] = static::crearObjeto($registro);
        }

        // liberar memoria
        $resultado->free();

        return $arreglo;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {

            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // sincronizar el objeto en memoria con los cambios realizados por el usuario
    public function sincronizar(array $args)
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    public static function SQL($consulta)
    {
        $arregloObjetos = self::consultarSQL($consulta);
        return $arregloObjetos;
    }
}
