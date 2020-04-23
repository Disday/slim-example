<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;

$companies = App\Generator::generate(100);

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, $response) {
  return $response->write('go to the /companies');
});

// BEGIN (write your solution here)
$app->get('/companies', function ($request, $response) use ($companies) {
  $page = $request->getQueryParam('page', 1);
  if ($page <= 0) {
    $page = 1;
  }
  $per = $request->getQueryParam('per', 5);
  if ($per <= 0) {
    $per = 5;
  }
  $offset = ($page - 1) * $per;
  // echo $page, "\n";
  // echo $per, "\n";

  $resultedArray = array_slice($companies, $offset, $per);
  // print_r($resultedArray);
  return $response->write(json_encode($resultedArray));
  // return $response->write(json_encode($companies));
});
// 
// END

$app->run();
