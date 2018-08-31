<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserInfo extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('user_info');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_info')) {
            Schema::create('user_info', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->string('section')->index();
                $table->text('value');
                $table->timestamps();
                $table->unique(['user_id', 'section']);
            });
        } else {
            Schema::table('user_info', function (Blueprint $table) {
                Schema::hasColumn('user_info', 'user_id') or $table->unsignedBigInteger('user_id')->index();
                Schema::hasColumn('user_info', 'section') or $table->string('section')->index();
                Schema::hasColumn('user_info', 'value') or $table->text('value');
                Schema::hasColumn('user_info', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('user_info', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
