<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpaTaskcollectionssTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpa_taskcollectionss', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('time'); 
            $table->string('name'); 
            $table->string('bewrite'); 
            $table->string('filepath'); 
            $table->integer('failtimes'); 
            $table->string('failtimes', 20); 
            $table->text('jsondata', 20); 
            $table->integer('tid'); 
            $table->text('content'); 
            $table->text('SMS'); 
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
        Schema::dropIfExists('rpa_taskcollectionss');
    }
}
