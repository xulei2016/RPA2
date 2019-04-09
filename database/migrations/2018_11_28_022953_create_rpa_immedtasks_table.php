<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpaImmedtasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpa_immedtasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->nullable()->unique();
            $table->string('state', 50);
            $table->text('jsondata');
            $table->integer('tid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rpa_immedtasks');
    }
}
