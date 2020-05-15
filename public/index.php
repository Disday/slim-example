<?php

use App\Validator;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$container = new Container();
$container->set('renderer', function () {
  return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$router = $app->getRouteCollector()->getRouteParser();

$app->get('/', function ($request, $response) {
  return $this->get('renderer')->render($response, 'index.phtml');
})->setName('index');

$app->get('/users', function ($request, $response) use ($router) {
  $params['file'] = file_get_contents('users.json');
  $params['router'] = $router;
  return $this->get('renderer')->render($response, "users/index.phtml", $params);
})->setName('users');

$app->get("/users/new", function ($request, $response, $args) use ($router) {
  $params['router'] = $router;
  return $this->get('renderer')->render($response, "users/new.phtml", $params);
})->setName('newUser');

$app->get('/users/{id}', function ($request, $response, $args) use ($router) {
  $users = explode("\n", file_get_contents('users.json'));
  $id = $args['id'];
  $user = collect($users)->map(function ($item, $key) {
    return json_decode($item);
  })->firstWhere('id', $id);
  if (empty($user)) {
    $response = $response->withStatus(404);
  }
  $params = ['user' => $user, 'router' => $router];
  return $this->get('renderer')->render($response, "users/show.phtml", $params);
})->setName('user');

$app->post('/users', function ($request, $response) use ($router) {
  $user = $request->getParsedBodyParam('user');
  $users = explode("\n", file_get_contents('users.json'));
  $users = collect($users);
  $users->pop();
  $lastUser = $users->last();
  $lastId = json_decode($lastUser)->id;
  $user['id'] = $lastId + 1;
  $newUser = json_encode($user) . "\n";
  file_put_contents('users.json', $newUser, FILE_APPEND);
  return $response->withRedirect($router->urlFor('newUser'), 302);
});




// END

$app->run();
