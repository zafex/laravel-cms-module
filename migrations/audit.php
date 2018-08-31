<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Audit extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('audit');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('audit')) {
            Schema::create('audit', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('referer')->index();
                $table->string('model')->index();
                $table->unsignedBigInteger('model_id')->index();
                $table->string('action')->index();
                $table->text('browser');
                $table->binary('request');
                $table->timestamps();
            });
        } else {
            Schema::table('audit', function (Blueprint $table) {
                Schema::hasColumn('audit', 'user_id') or $table->unsignedBigInteger('user_id')->index();
                Schema::hasColumn('audit', 'referer') or $table->string('referer')->index();
                Schema::hasColumn('audit', 'model') or $table->string('model')->index();
                Schema::hasColumn('audit', 'model_id') or $table->unsignedBigInteger('model_id')->index();
                Schema::hasColumn('audit', 'action') or $table->string('action')->index();
                Schema::hasColumn('audit', 'browser') or $table->text('browser');
                Schema::hasColumn('audit', 'request') or $table->binary('request');
                Schema::hasColumn('audit', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('audit', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
