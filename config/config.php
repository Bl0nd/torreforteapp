<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Informar a URL base do projeto
define('URL_BASE', 'http://localhost/torreforte_app/public/');
define('API_BASE', 'http://localhost/torreforte/public/api/');
define('FOTO_BASE', 'http://localhost/torreforte/public/');

spl_autoload_register(function ($class) {

    // Comentario
    if (file_exists('../app/controllers/' . $class . '.php')) {
        require_once '../app/controllers/' . $class . '.php';
    }

    if (file_exists('../rotas/' . $class . '.php')) {
        require_once '../rotas/' . $class . '.php';
    }
});
