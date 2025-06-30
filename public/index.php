<?php

use Controlador\DashboardController;
use Controlador\LoginController;
use Controlador\TareaController;
use MVC\Router;

require_once "../includes/app.php";

$router = new Router();

$router->get("/", [LoginController::class, "login"]);
$router->post("/", [LoginController::class, "login"]);
$router->get("/logout", [LoginController::class, "logout"]);

$router->get("/registro", [LoginController::class, "registro"]);
$router->post("/registro", [LoginController::class, "registro"]);

$router->get("/olvide-password", [LoginController::class, "olvidePassword"]);
$router->post("/olvide-password", [LoginController::class, "olvidePassword"]);
$router->get("/reestablecer-password", [LoginController::class, "reestablecerPassword"]);
$router->post("/reestablecer-password", [LoginController::class, "reestablecerPassword"]);

$router->get("/mensaje-confirmacion", [LoginController::class, "mensajeConfirmacion"]);
$router->get("/confirmacion-cuenta", [LoginController::class, "confirmacionCuenta"]);


$router->get("/dashboard", [DashboardController::class, "index"]);
$router->get("/crear-proyecto", [DashboardController::class, "crear_proyecto"]);
$router->post("/crear-proyecto", [DashboardController::class, "crear_proyecto"]);
$router->get("/perfil", [DashboardController::class, "perfil"]);
$router->post("/perfil", [DashboardController::class, "perfil"]);
$router->get("/cambiar-password", [DashboardController::class, "cambiar_password"]);
$router->post("/cambiar-password", [DashboardController::class, "cambiar_password"]);
$router->get("/proyecto", [DashboardController::class, "proyecto"]);

$router->get("/api/tareas", [TareaController::class, "index"]);
$router->post("/api/tarea", [TareaController::class, "crear_tarea"]);
$router->post("/api/tarea/actualizar", [TareaController::class, "actualizar_tarea"]);
$router->post("/api/tarea/eliminar", [TareaController::class, "eliminar_tarea"]);

$router->comprobarRutas();
