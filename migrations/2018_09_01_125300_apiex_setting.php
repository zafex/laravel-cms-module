<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexSetting extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('setting');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('setting')) {
            Schema::create('setting', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('section')->unique();
                $table->binary('value');
                $table->timestamps();
            });
        } else {
            Schema::table('setting', function (Blueprint $table) {
                Schema::hasColumn('setting', 'section') or $table->string('section')->unique();
                Schema::hasColumn('setting', 'value') or $table->binary('value');
                Schema::hasColumn('setting', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('setting', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
