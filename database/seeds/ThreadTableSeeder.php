<?php

use Illuminate\Database\Seeder;

class ThreadTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = new Forum\User;
        //delete Threds table records
        DB::table('threads')->delete();
        $channels = new Forum\Channel;
        //  dd($users);
        $users->each(function ($user) {
            $channels_id = array(1,2,3);
            $ch_id = array_rand($channels_id, 1);
            factory('Forum\Thread')->create(['user_id'=>$user->id, 'channel_id'=>$channels_id[$ch_id]]);
        });

        $this->command->info('Threads table created');
    }
}
