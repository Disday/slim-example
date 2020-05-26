<?php

namespace App;

class Generator
{
    public static function generate($count)
    {
        $range = range(1, $count - 2);
        $numbers = collect($range)->shuffle(1)->toArray();

        $faker = \Faker\Factory::create();
        $faker->seed(1234);
        $users = [];
        for ($i = 0; $i < $count - 2; $i++) {
            $users[] = [
                'id' => $numbers[$i],
                'firstName' => $faker->firstName,
                'lastName' => $faker->lastName,
                'email' => $faker->email
            ];
        }

        $users[] = [
            'id' => 99,
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'email' => $faker->email
        ];

        $users[] = [
            'id' => 100,
            'firstName' => $faker->firstName,
            'lastName' => $faker->lastName,
            'email' => $faker->email
        ];

        return $users;
    }
    public static function generatePosts($count)
    {
        $numbers = range(1, $count);
        shuffle($numbers);

        $faker = \Faker\Factory::create();
        $faker->seed(1);
        $posts = [];
        for ($i = 0; $i < $count; $i++) {
            $posts[] = [
                'id' => $faker->uuid,
                'name' => $faker->text(70),
                'body' => $faker->sentence,
                'slug' => $faker->slug,
                'number' => $i
            ];
        }

        return $posts;
    }

}
