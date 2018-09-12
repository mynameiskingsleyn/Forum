<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //delete users table records
        DB::table('users')->delete();
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
          'id' => 1,
          'name'=> 'King',
          'email' => 'king@yahoo.com',
          'password'=> bcrypt('password'),
          'remember_token'=> str_random(7),
          'created_at'=>now(),
          'updated_at'=>now()
        ]);

        factory('Forum\User', 4)->create();

        $this->command->info('users table seeded..');
    }
}
