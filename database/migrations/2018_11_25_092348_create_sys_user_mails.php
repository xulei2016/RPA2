<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysUserMails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_user_mails', function (Blueprint $table) {
            $table->increments('mid');
            $table->integer('uid')->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->tinyInteger('type');
            $table->string('read_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_user_mails');
    }
}
