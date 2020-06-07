<?php

// namespace Apps;

class Users
{
   public $users;

   public static function getUsers()
   {
      // $users = explode("\n", file_get_contents('users.json'));
      $users = file('users.json', FILE_IGNORE_NEW_LINES);
      return collect($users);
   }
   public static function findUser($id)
   {
      return self::getUsers()->map(function ($item, $key) {
         return json_decode($item);
      })->firstWhere('id', $id);
   }
   public static function saveUsers($users, string $file)
   {
      file_put_contents($file, '');
      foreach ($users as $user) :
         file_put_contents($file, $user . "\n", FILE_APPEND);
      endforeach;
   }
}
