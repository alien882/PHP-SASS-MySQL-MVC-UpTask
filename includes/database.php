<?php

function conectarDB():mysqli {
    
    $db = new mysqli($_ENV["DB_HOST"], $_ENV["DB_USUARIO"], $_ENV["DB_PASSWORD"], $_ENV["DB_NOMBRE"]);
    $db->set_charset("utf8");

    if(!$db) {
       echo "Conexión fallida";
       exit;
    }

    return $db;
}