<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AuditDetail extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_detail');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('audit_id')->index();
            $table->string('field')->index();
            $table->text('old_value');
            $table->text('new_value');
        });
    }
}
