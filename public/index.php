<?php

use App\Validator;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';

$repo = new App\Repository();

$container = new Container();
$container->set('renderer', function () {
    return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
    return $this->get('renderer')->render($response, 'index.phtml');
});

$app->get('/courses', function ($request, $response) use ($repo) {
    $params = [
        'courses' => $repo->all()
    ];
    return $this->get('renderer')->render($response, 'courses/index.phtml', $params);
});

// BEGIN (write your solution here)
$app->get('/courses/new', function ($request, $response) {
  return $this->get('renderer')->render($response, 'courses/new.phtml');
});
$app->post('/courses', function ($request, $response) use ($repo) {
  $course = $request->getParsedBodyParam('course');
  $validator = new App\Validator();
  $errors = $validator->validate($course);
  if (empty($errors)){
    $repo->save($course);
    return $response->withRedirect('/courses', 302);
  }
  $params = ['errors' => $errors, 'course' => $course];  
  $response = $response->withStatus(422);
  return $this->get('renderer')->render($response, 'courses/new.phtml', $params);
});
// END

$app->run();
