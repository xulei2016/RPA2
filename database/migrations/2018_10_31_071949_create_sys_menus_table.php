<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default('0'); 
            $table->integer('order')->default('1'); 
            $table->string('unique_name', 50)->unique(); 
            $table->string('title', 50); 
            $table->string('icon', 50);
            $table->string('uri', 50);
            $table->integer('is_use')->default('1');
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
        Schema::dropIfExists('sys_menus');
    }
}
