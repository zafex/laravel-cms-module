<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrivilegeRole extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilege_role');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilege_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('privilege_id')->index();
            $table->unsignedInteger('role_id')->index();
            $table->timestamps();
            $table->unique(['privilege_id', 'role_id']);
        });
    }
}
