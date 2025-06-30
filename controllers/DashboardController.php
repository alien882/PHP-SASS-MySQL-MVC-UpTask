<?php

namespace Controlador;

use Modelo\Proyecto;
use Modelo\Usuario;
use MVC\Router;

class DashboardController
{
    public static function index(Router $router)
    {
        estaAutenticado();

        $proyectos = Proyecto::whereAll("propietarioId", $_SESSION["id"]);

        $router->render("dashboard/index", [
            "titulo" => "Proyectos",
            "nombre" => $_SESSION["nombre"],
            "proyectos" => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router)
    {
        estaAutenticado();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $proyecto = new Proyecto($_POST);
            $proyecto->validar_proyecto();

            if (empty(Proyecto::getAlertas())) {
                $proyecto->url = md5(uniqid());
                $proyecto->propietarioId = $_SESSION["id"];
                $proyecto->guardar();

                header("location: /proyecto?id=$proyecto->url");
            }
        }

        $router->render("dashboard/crearProyecto", [
            "titulo" => "Crear Proyecto",
            "nombre" => $_SESSION["nombre"],
            "alertas" => Proyecto::getAlertas()
        ]);
    }

    public static function perfil(Router $router)
    {
        estaAutenticado();

        $usuario = Usuario::find($_SESSION["id"]);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario->sincronizar($_POST);
            $usuario->validar_perfil();

            if (empty(Usuario::getAlertas())) {
                $existeUsuario = Usuario::where("email", $usuario->email);

                if ($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta("error", "El email ya pertenece a otra cuenta");
                } else {
                    $usuario->guardar();
                    $_SESSION["nombre"] = $usuario->nombre;
                    Usuario::setAlerta("exito", "Datos guardados correctamente");
                }
            }
        }

        $router->render("dashboard/perfil", [
            "titulo" => "Perfil",
            "nombre" => $_SESSION["nombre"],
            "alertas" => Usuario::getAlertas(),
            "usuario" => $usuario
        ]);
    }

    public static function proyecto(Router $router)
    {
        estaAutenticado();

        $proyectoUrl = $_GET["id"] ?? "";

        if (!$proyectoUrl) {
            header("location: /dashboard");
        }

        $proyecto = Proyecto::where("url", $proyectoUrl);

        if ($proyecto->propietarioId !== $_SESSION["id"]) {
            header("location: /dashboard");
        }

        $router->render("dashboard/proyecto", [
            "titulo" => $proyecto->proyecto,
            "nombre" => $_SESSION["nombre"]
        ]);
    }

    public static function cambiar_password(Router $router)
    {
        estaAutenticado();

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $usuario = Usuario::find($_SESSION["id"]);
            $usuario->sincronizar($_POST);
            $usuario->validar_nuevo_password();

            if (empty(Usuario::getAlertas())) {
                $resultado = $usuario->comprobar_password();
                if ($resultado) {
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);
                    $usuario->hashearPassword();
                    $exito = $usuario->guardar();

                    if ($exito) {
                        Usuario::setAlerta("exito", "Contraseña guardada correctemente");
                    } else {
                        Usuario::setAlerta("error", "Hubo un error");
                    }
                } else {
                    Usuario::setAlerta("error", "Password Incorrecto");
                }
            }
        }

        $router->render("dashboard/cambiarPassword", [
            "titulo" => "Cambiar Password",
            "alertas" => Usuario::getAlertas()
        ]);
    }
}
