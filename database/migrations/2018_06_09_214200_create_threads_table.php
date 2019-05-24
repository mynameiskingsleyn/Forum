<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('channel_id');
            $table->unsignedInteger('replies_count')->default('0');
            ;
            $table->string('title');
            $table->text('body');
            $default = substr(md5(uniqid(mt_rand(), true)), 0, 8);
            $table->string('slug')->default($default)->unique();
            $table->unsignedInteger('best_reply_id')->nullable();
            $table->unsignedInteger('visits')->default(0);
            $table->timestamps();

            $table->foreign('best_reply_id')
                  ->references('id')
                  ->on('replies')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
