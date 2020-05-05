
<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use DI\Container;

use function Funct\Object\toArray;
use function Funct\Strings\toLower;

$users = App\Generator::generate(100);

$container = new Container();
$container->set('renderer', function () {
  return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/users/new', function ($request, $response, $args) {
  return $this->get('renderer')->render($response, '/users/new.phtml');
});
$app->post('/users', function ($request, $response) {
  $user = $request->getParsedBodyParam('user');
  $users = explode("\n", file_get_contents('1.txt'));
  $users = collect($users);
  $users->pop();
  $lastUser = $users->last();
  $lastId = json_decode($lastUser)->id;
  $user['id'] = $lastId + 1;
  $newUser = json_encode($user) . "\n";
  file_put_contents('1.txt', $newUser , FILE_APPEND);
  return $response->withRedirect('/users/new', 302);
});
$app->get('/users', function ($request, $response) {
  $params['file'] = file_get_contents('1.txt');
  return $this->get('renderer')->render($response, '/users/index.phtml', $params);
});

/*
$app->get('/users', function ($request, $response) use ($users) {
  $term = $request->getQueryParam('term');
  if ($term != null) {
    $users = array_filter($users, function ($user) use ($term) {
      return strpos(strtolower($user['firstName']), $term) === 0 ? true : false; 
    });
  }
  $params = compact('users', 'term');
  return $this->get('renderer')->render($response, 'users/index.phtml', $params);
});*/



$app->run(); ?>




