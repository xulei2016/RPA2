<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpaMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpa_maintenances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->string('bewrite');
            $table->string('filepath', 100);
            $table->integer('failtimes');
            $table->integer('timeout');
            $table->tinyInteger('isfp')->default(0)->nullable();
            $table->text('emailreceiver');
            $table->string('PhoneNum');
            $table->string('messageSet', 50);
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
        Schema::dropIfExists('rpa_maintenances');
    }
}
