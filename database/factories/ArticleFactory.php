<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'title' => $faker -> company(),
        'user_id'       => rand(1,20),
        'desn' => $faker -> realText(20),        
        'pic' => '/front/images/img_'.rand(1,4).'.jpg',
        'body' => $faker -> realText(200),

    ];
});
