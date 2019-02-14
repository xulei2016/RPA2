<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysApiIps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_api_ips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('api','50')->nullable();
            $table->string('url','100')->nullable();
            $table->string('method','10')->nullable();
            $table->string('black_list');
            $table->string('white_list');
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
        Schema::dropIfExists('sys_api_ips');
    }
}
