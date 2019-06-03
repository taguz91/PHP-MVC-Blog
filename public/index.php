<?php
  //Errores de php solo para modo desarollo
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  //Iniciamos nuestra sesion
  session_start();

  //Usando composer
  require_once '../vendor/autoload.php';

  $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']),
  '',$_SERVER['SCRIPT_NAME']);
  $baseUrl = 'http://'.$_SERVER['HTTP_HOST'].$baseDir;
  define('BASE_URL', $baseUrl);

  $dotenv = Dotenv\Dotenv::create(__DIR__.'/..');
  $dotenv->load();


  //Base de datos
  use Illuminate\Database\Capsule\Manager as Capsule;

  $capsule = new Capsule;

  $capsule->addConnection([
      'driver'    => 'mysql',
      'host'      => getenv('DB_HOST'),
      'database'  => getenv('DB_NAME'),
      'username'  => getenv('DB_USER'),
      'password'  => getenv('DB_PASS'),
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => '',
  ]);

  $capsule->setAsGlobal();
  $capsule->bootEloquent();

  //Ruta
  $route = $_GET['route'] ?? '/';

  use Phroute\Phroute\RouteCollector;

  $router = new RouteCollector();

  //Filtro
  $router->filter('auth', function() {
    if (!isset($_SESSION['userID'])) {
      header('Location: '.BASE_URL.'auth/login');
      return false;
    }
  });

  $router ->controller('/auth',
  App\Controllers\Admin\LoginCTR::class);

  //Agrupamos los para que pregunten por el friltro
  $router->group(['before' => 'auth'],
  function ($router) {
    $router->controller('/admin/users',
    App\Controllers\Admin\UserCTR::class);

    $router->controller('/admin/posts',
    App\Controllers\Admin\PostCTR::class);

    $router ->controller('/admin',
    App\Controllers\Admin\IndexCTR::class);
  });

  $router->controller('/',
  App\Controllers\IndexCTR::class);

  $dispacher = new Phroute\Phroute\Dispatcher($router->getData());

  $response = $dispacher->dispatch($_SERVER['REQUEST_METHOD'], $route);

  echo $response;
 ?>
