<?php

// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$app = AppFactory::create();
// print_r($app);

$get = $app->get('/users', function ($request, $response) {
    return $response->write('GET /users');
});
$post = $app->post('/users', function ($request, $response) {
  return $response->write('POST /users');
});

print_r($post);

// $app->run();