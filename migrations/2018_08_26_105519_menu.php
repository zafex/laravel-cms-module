<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Menu extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->default(0)->index();
            $table->unsignedBigInteger('privilege_id')->default(0)->index();
            $table->string('type')->default('main')->index();
            $table->string('label')->index();
            $table->string('icon')->index();
            $table->text('url');
            $table->timestamps();
        });
    }
}
