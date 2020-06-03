<?php

namespace App;

class Validator implements ValidatorInterface
{
  public function validate(array $array)
  {
    // BEGIN (write your solution here)
    $errors = [];
    // var_dump($course['title']);
    if ($array['paid'] === '') {
      $errors['paid'] = "Can't be blank";
    }
    // var_dump($course['title'] === '');
    if ($array['title'] === '') {
      $errors['title'] = "Can't be blank";
    }
    // file_put_contents('log.json', json_encode($array));

    if ($array['nickname'] === '') {
      $errors['nickname'] = "Can't be blank";
    }
    if ($array['name'] === '') {
      $errors['name'] = "Can't be blank";
    }
    if ($array['body'] === '') {
      $errors['body'] = "Can't be blank";
    }
    if ($array['email'] === '') {
      $errors['email'] = "Can't be blank";
    }


    return $errors;

    // END
  }
}
