<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_errors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account', 30); 
            $table->string('class', 30); 
            $table->string('function', 30); 
            $table->string('agent', 200); 
            $table->text('info'); 
            $table->string('ip', 20); 
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
        Schema::dropIfExists('sys_errors');
    }
}
