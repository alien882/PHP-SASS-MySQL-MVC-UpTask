<?php

namespace Modelo;

class Usuario extends ClaseBase
{
    protected static $tabla = "usuarios";
    protected static $columnasDB = ["id", "nombre", "email", "password", "token", "confirmado"];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args["id"] ?? "";
        $this->nombre = $args["nombre"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->password2 = $args["password2"] ?? "";
        $this->password_actual = $args["password_actual"] ?? "";
        $this->password_nuevo = $args["password_nuevo"] ?? "";
        $this->token = $args["token"] ?? "";
        $this->confirmado = $args["confirmado"] ?? 0;
    }

    public function validarRegistro()
    {
        if (!$this->nombre) {
            self::$alertas["error"][] = "El nombre del usuario es obligatorio";
        }

        if (!$this->email) {
            self::$alertas["error"][] = "El correo del usuario es obligatorio";
        }

        if (!$this->password) {
            self::$alertas["error"][] = "El password del usuario es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password debe ser de 6 caracteres o más";
        }

        if ($this->password !== $this->password2) {
            self::$alertas["error"][] = "Las contraseñas deben ser iguales";
        }
    }

    public function hashearPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken()
    {
        $this->token = md5(uniqid());
    }

    public function validarEmail()
    {
        if (!$this->email) {
            self::$alertas["error"][] = "El correo es obligatorio";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "Email no válido";
        }
    }

    public function validarPassword()
    {
        if (!$this->password) {
            self::$alertas["error"][] = "El password del usuario es obligatorio";
        }

        if (strlen($this->password) < 6) {
            self::$alertas["error"][] = "El password debe ser de 6 caracteres o más";
        }
    }

    public function validarLogin()
    {
        if (!$this->email) {
            self::$alertas["error"][] = "El correo es obligatorio";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "Email no válido";
        }

        if (!$this->password) {
            self::$alertas["error"][] = "El password del usuario es obligatorio";
        }
    }

    public function validar_perfil()
    {
        if (!$this->email) {
            self::$alertas["error"][] = "El correo es obligatorio";
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas["error"][] = "Email no válido";
        }

        if (!$this->nombre) {
            self::$alertas["error"][] = "El nombre del usuario es obligatorio";
        }
    }

    public function validar_nuevo_password()
    {
        if (!$this->password_actual) {
            self::$alertas["error"][] = "Ingresa el password actual";
        }

        if (!$this->password_nuevo) {
            self::$alertas["error"][] = "El password nuevo es obligatorio";
        }

        if (strlen($this->password_nuevo) < 6) {
            self::$alertas["error"][] = "El password nuevo debe ser de 6 caracteres o más";
        }
    }

    public function comprobar_password()
    {
        return password_verify($this->password_actual, $this->password);
    }
}
