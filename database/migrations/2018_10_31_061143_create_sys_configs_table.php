<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label', 100);
            $table->string('item_key', 50);
            $table->text('item_value');
            $table->string('item_group');
            $table->string('type', 50);
            $table->tinyInteger('sort');
            $table->string('tip', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_configs');
    }
}
