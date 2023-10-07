<?php
// Made By : Yusuf Limited

error_reporting(E_ALL);
ini_set('display_errors', 1);

// call autoload
require_once 'vendor/autoload.php';

spl_autoload_register(function ($className) {
  $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
  require_once __DIR__ . '/app/Controllers/' . $className . '.php';
});

require_once 'core/Config.php';
require_once 'core/Helper.php';
require_once 'core/Request.php';
require_once 'core/Validation.php';
require_once 'core/Model.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';

$router = new Router();

// Define routes
require_once 'app/Routes/web.php';

$router->dispatch();
