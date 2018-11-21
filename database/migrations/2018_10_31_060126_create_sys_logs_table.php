<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id'); 
            $table->string('controller', 50); 
            $table->string('account', 30); 
            $table->string('action', 30); 
            $table->string('agent'); 
            $table->string('ip', 20); 
            $table->string('simple_desc', 50); 
            $table->string('method', 20); 
            $table->string('path', 50); 
            $table->text('data'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_logs');
    }
}
