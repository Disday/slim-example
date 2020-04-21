<?php
// Подключение автозагрузки через composer
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;


// $faker = \Faker\Factory::create();
// $faker->seed(1234);

// $domains = [];
// for ($i = 0; $i < 10; $i++) {
//     $domains[] = $faker->domainName;
// }

// $phones = [];
// for ($i = 0; $i < 10; $i++) {
//     $phones[] = $faker->phoneNumber;
// }

$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$x='5';
$app->get('/', function ($request, $response) {
    return $response->write('go to the /phones or /domains');
});
// BEGIN (write your solution here)
$app->get('/phones', function ($request, $response) use ($x) {
  return $response->write($x);
});


// END

$app->run();
