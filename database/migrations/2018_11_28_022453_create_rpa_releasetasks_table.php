<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpaReleasetasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpa_releasetasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('description', 150);
            $table->timestamp('date');
            $table->string('week', 20);
            $table->text('time')->nullable();
            $table->text('jsondata');
            $table->integer('tid');
            $table->tinyInteger('state');
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
        Schema::dropIfExists('rpa_releasetasks');
    }
}
