<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexUserPermission extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('user_permission');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_permission')) {
            Schema::create('user_permission', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('permission_id')->index();
                $table->unsignedBigInteger('object_id')->index();
                $table->timestamps();
                $table->unique(['user_id', 'permission_id', 'object_id']);
            });
        } else {
            Schema::table('user_permission', function (Blueprint $table) {
                Schema::hasColumn('user_permission', 'user_id') or $table->unsignedBigInteger('user_id')->index();
                Schema::hasColumn('user_permission', 'permission_id') or $table->unsignedBigInteger('permission_id')->index();
                Schema::hasColumn('user_permission', 'object_id') or $table->unsignedBigInteger('object_id')->index();
                Schema::hasColumn('user_permission', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('user_permission', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
