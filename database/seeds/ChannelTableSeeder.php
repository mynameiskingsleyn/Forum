<?php

use Illuminate\Database\Seeder;

class ChannelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // delete channels records

        DB::table('channels')->delete();
        $channels = array(
          array('id'=>1,'name'=>'PHP','slug'=>'php','created_at'=>now(),'updated_at'=>now()),
          array('id'=>2,'name'=>'SQL','slug'=>'sql','created_at'=>now(),'updated_at'=>now()),
          array('id'=>3,'name'=>'DB','slug'=>'db','created_at'=>now(),'updated_at'=>now()),
            array('id'=>4,'name'=>'FUN','slug'=>'fun','created_at'=>now(),'updated_at'=>now())
       );
        //DB::table('channels')->delete();
        // $this->call(UsersTableSeeder::class);
        DB::table('channels')->insert($channels);

        $this->command->info("channels table seeded");
    }
}
