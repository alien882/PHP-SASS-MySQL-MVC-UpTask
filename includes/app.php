<?php

use Dotenv\Dotenv;
use Modelo\ClaseBase;

require "funciones.php";
require "database.php";
require "../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$db = conectarDB();

ClaseBase::setBaseDeDatos($db);