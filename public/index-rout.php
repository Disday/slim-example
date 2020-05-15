<?php

use App\Validator;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

// $repo = new App\Repository();

// $container = new Container();
// $container->set('renderer', function () {
//     return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
// });
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/users', function ($request, $response) {
  return $response->write('users');
})->setName('users');

$app->get('/users/{id}', function ($request, $response) {
  return $response->write('user');
})->setName('user');

// Получаем роутер – объект отвечающий за хранение и обработку маршрутов
$router = $app->getRouteCollector();
file_put_contents('log.json', $router->getRoutes());

// Не забываем прокинуть его в обработчик
$app->get('/', function ($request, $response) use ($router) {
  // в функцию передаётся имя маршрута, а она возвращает url
  // $a = $router->urlFor('users'); // /users
  // $b = $router->urlFor('user', ['id' => 4]); // /users/4
  return $response->write($a)->write($b);
});
$app->run();