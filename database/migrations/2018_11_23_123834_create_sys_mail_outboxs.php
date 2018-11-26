<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysMailOutboxs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_mail_outboxs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100)->nullable();
            $table->text('content')->nullable();
            $table->tinyInteger('is_revoke')->default(0);
            $table->string('revoke_time', 25);
            $table->tinyInteger('is_delete')->default(0);
            $table->string('delete_time', 25);
            $table->integer('tid');
            $table->integer('mid');
            $table->text('user');
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
        Schema::dropIfExists('sys_mail_outboxs');
    }
}
