<?php

use App\Validator;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';
session_start();
$container = new Container();
$container->set('renderer', function () {
  return new \Slim\Views\PhpRenderer(__DIR__ . '/../templates');
});
$container->set('flash', function () {
  return new \Slim\Flash\Messages();
});

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$router = $app->getRouteCollector()->getRouteParser();
$repo = new App\PostRepository();


$app->get('/', function ($request, $response) {
  return $this->get('renderer')->render($response, 'index.phtml');
})->setName('index');

$app->get('/users', function ($request, $response) use ($router) {
  $term = $request->getQueryParam('term');
  // $response->write($term);
  $params = [
    'messages' => $this->get('flash')->getMessages(),
    'router' => $router,
    'term' => $term
  ];
  $users = explode("\n", file_get_contents('users.json'));
  // $users = collect($users);
  $params['users'] = $users;
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
    $this->get('flash')->addMessage('error', 'User does not exist');
    // $response = $response->withStatus(404);
    return $response->withRedirect($router->urlFor('users'), 404);
  }
  $params = ['user' => $user, 'router' => $router];
  return $this->get('renderer')->render($response, "users/show.phtml", $params);
})->setName('user');

$app->post('/users', function ($request, $response) use ($router) {
  $user = $request->getParsedBodyParam('user');
  $validator = new Validator();
  $errors = $validator->validate($user);
  $params['errors'] = $errors;
  if (!empty($errors)) {
    return $this->get('renderer')->render($response, "users/new.phtml", $params);
  }
  file_put_contents('log.json', json_encode($errors));

  $users = explode("\n", file_get_contents('users.json'));
  $users = collect($users);
  $users->pop();
  $lastUser = $users->last();
  $lastId = json_decode($lastUser)->id;
  $user['id'] = $lastId + 1;
  $newUser = json_encode($user) . "\n";
  file_put_contents('users.json', $newUser, FILE_APPEND);
  $this->get('flash')->addMessage('success', 'User added');
  return $response->withRedirect($router->urlFor('users'), 302);
});

// BEGIN (write your solution here)
$app->get('/posts', function ($request, $response) use ($repo, $router) {
  $page = $request->getQueryParam('page');
  if ($page < 1) {
    return $response->withRedirect('/posts?page=1');
  }
  $posts = collect($repo->all())->slice(($page - 1) * 5, 5);
  file_put_contents('log.json', json_encode($page));
  $params = [
    'router' => $router,
    'posts' => $posts,
    'page' => $page,
    'messages' => $this->get('flash')->getMessages()
  ];
  return $this->get('renderer')->render($response, 'posts/index.phtml', $params);
})->setName('posts');

$app->get('/posts/new', function ($request, $response) {
  $params = [];
  return $this->get('renderer')->render($response, 'posts/new.phtml', $params);
})->setName('newPost');

$app->post('/posts', function ($request, $response) use ($router, $repo) {
  $post = $request->getParsedBodyParam('post');
  $validator = new Validator();
  $errors = $validator->validate($post);
  if ($errors) {
    $params = [
      'errors' => $errors,
      'post' => $post
    ];
    $response = $response->withStatus(422);
    return $this->get('renderer')->render($response, 'posts/new.phtml', $params);
    // return $response->withRedirect($router->urlFor('newPost'), 422);
  }
  $repo->save($post);
  $this->get('flash')->addMessage('success', 'Post has been created');
  return $response->withRedirect($router->urlFor('posts'));
});


$app->get('/posts/{id}', function ($request, $response, $args) use ($router, $repo) {
  $id = strval($args['id']);
  $post = $repo->find($id);
  if (empty($post)) {
    return $response->withStatus(404)->write('Page not found');
  }
  // file_put_contents('log.json', json_encode($repo->find('asd')));
  $params = [
    'router' => $router,
    'post' => $post
  ];
  return $this->get('renderer')->render($response, "posts/show.phtml", $params);
})->setName('post');


$app->run();
