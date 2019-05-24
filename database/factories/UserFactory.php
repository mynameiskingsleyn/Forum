<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Forum\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'confirmed'=>true
    ];
});
$factory->state(Forum\User::class, 'uncomfirmed', function () {
    return[
    'confirmed' =>false
  ];
});

$factory->define(Forum\Thread::class, function ($faker) {
    $title = $faker->sentence;
    return [
        'user_id' => function () {
            return factory('Forum\User')->create()->id;
        },
        'channel_id'=> function () {
            return factory('Forum\Channel')->create()->id;
        },
        'title' => $title,
        'body' =>  $faker->paragraph,
        'visits'=> 0,
        'slug'=>str_slug($title)
   ];
});
// $factory->define(Forum\Thread::class, function ($attr) {
//     return $attr;
// });
$factory->define(Forum\Reply::class, function ($faker) {
    return [
        'thread_id' => function () {
            return factory('Forum\Thread')->create()->id;
        },
        'user_id'=> function () {
            return factory('Forum\User')->create()->id;
        },
        'body' => $faker->paragraph
    ];
});
$factory->define(Forum\Channel::class, function ($faker) {
    $name = $faker->word;
    return [
    'name' => $name,
    'slug' => strtolower($name)
  ];
});
$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function ($faker) {
    return [
      'id'=> \Ramsey\Uuid\Uuid::uuid4()->toString(),
      'type'=>'Forum\Notification\ThreadWasUpdated',
      'notifiable_id' => function () {
          return auth()->id() ?:factory('Forum\User')->create()->id;
      },
      'notifiable_type' => 'Forum\User',
      'data'=>['foo' => 'bar yall!!']
  ];
});
