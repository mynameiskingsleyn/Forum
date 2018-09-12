<?php

use Illuminate\Database\Seeder;

class ReplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $threads = new Forum\Thread;
        $threads->each(function ($thread) {
            factory('Forum\Reply', 3)->create(['user_id'=>rand(1, 5),'thread_id'=>rand(1, 5)]);
        });
        $this->command->info('Replies table seeded..');
    }
}
