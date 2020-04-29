
<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);
$term;
$app->get('/', function ($request, $response, $args) use (&$term) {
  $term = $request->getQueryParam('term');
  return $response->write(htmlspecialchars($term));
});
$app->run();?>

<form action="/" method="get">
  <input type="tel" value="<?=$term?>" required name="term">
  <input type="submit" value="Кнопка">
</form>
<?php

