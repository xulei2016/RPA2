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
            $table->string('path'); 
            $table->string('method', 50); 
            $table->ipAddress('ip'); 
            $table->text('data'); 
            $table->timestamp('created_at'); 
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
