<?php

namespace Controlador;

use Clases\Email;
use Modelo\Usuario;
use MVC\Router;

class LoginController
{
    public static function login(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $auth = new Usuario($_POST);
            $auth->validarLogin();

            if (empty(Usuario::getAlertas())) {
                $usuario = $auth->where("email", $auth->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta("error", "El usuario no existe o no está confirmado");
                } else {

                    if (password_verify($auth->password, $usuario->password)) {

                        session_start();
                        $_SESSION["id"] = $usuario->id;
                        $_SESSION["nombre"] = $usuario->nombre;
                        $_SESSION["email"] = $usuario->email;
                        $_SESSION["login"] = true;

                        header("location: /dashboard");
                    } else {
                        Usuario::setAlerta("error", "Password incorrecto");
                    }
                }
            }
        }

        $router->render("auth/login", [
            "titulo" => "Iniciar Sesión",
            "alertas" => Usuario::getAlertas()
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header("location: /");
    }

    public static function registro(Router $router)
    {
        $usuario = new Usuario;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $usuario->validarRegistro();
            $existeUsuario = Usuario::where("email", $usuario->email);

            if (empty(Usuario::getAlertas())) {

                if ($existeUsuario) {
                    Usuario::setAlerta("error", "$usuario->email ya está asociado a una cuenta");
                } else {
                    $usuario->hashearPassword();
                    unset($usuario->password2);
                    $usuario->generarToken();
                    $resultado = $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header("location: /mensaje-confirmacion");
                    }
                }
            }
        }

        $router->render("auth/registro", [
            "titulo" => "Crear Cuenta",
            "usuario" => $usuario,
            "alertas" => Usuario::getAlertas()
        ]);
    }

    public static function olvidePassword(Router $router)
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = new Usuario($_POST);
            $usuario->validarEmail();

            if (empty(Usuario::getAlertas())) {
                $usuario = Usuario::where("email", $usuario->email);

                if ($usuario && $usuario->confirmado === "1") {

                    $usuario->generarToken();
                    $usuario->guardar();
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    Usuario::setAlerta("exito", "Hemos enviado las instrucciones a tu email");
                } else {
                    Usuario::setAlerta("error", "El usuario no existe o no está confirmado");
                }
            }
        }

        $router->render("auth/olvidePassword", [
            "titulo" => "Recuperar Password",
            "alertas" => Usuario::getAlertas()
        ]);
    }

    public static function reestablecerPassword(Router $router)
    {
        $token = escaparHtml($_GET["token"] ?? "");
        $mostrar = true;

        if (!$token) {
            header("location: /");
        }

        $usuario = Usuario::where("token", $token);

        if (!$usuario) {
            Usuario::setAlerta("error", "Token no válido");
            $mostrar = false;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $usuario->validarPassword();

            if (empty(Usuario::getAlertas())) {
                $usuario->hashearPassword();
                $usuario->token = "";
                $resultado = $usuario->guardar();

                if ($resultado) {
                    header("location: /");
                }
            }
        }

        $router->render("auth/reestablecerPassword", [
            "titulo" => "Reestablecer Password",
            "alertas" => Usuario::getAlertas(),
            "mostrar" => $mostrar
        ]);
    }

    public static function mensajeConfirmacion(Router $router)
    {
        $router->render("auth/mensajeConfirmacion", [
            "titulo" => "Cuenta Creada"
        ]);
    }

    public static function confirmacionCuenta(Router $router)
    {
        $token = escaparHtml($_GET["token"] ?? "");

        if (!$token) {
            header("location: /");
        }

        $usuario = Usuario::where("token", $token);

        if (!$usuario) {
            Usuario::setAlerta("error", "Token no válido");
        } else {
            $usuario->confirmado = 1;
            $usuario->token = "";
            unset($usuario->password2);
            $usuario->guardar();
            Usuario::setAlerta("exito", "Cuenta comprobada correctamente");
        }

        $router->render("auth/confirmacionCuenta", [
            "titulo" => "Confirma tu cuenta",
            "alertas" => Usuario::getAlertas()
        ]);
    }
}
