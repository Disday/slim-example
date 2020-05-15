<?php

namespace App;

class Validator implements ValidatorInterface
{
    public function validate(array $course)
    {
        // BEGIN (write your solution here)
    $errors = [];
    var_dump($course['title']);
    if ($course['paid'] === '') {
      $errors['paid'] = "Can't be blank";
    }
    var_dump($course['title'] === '');
    if ($course['title'] === '') {
      $errors['title'] = "Can't be blank";
    }
    return $errors;

        // END
    }
}
