<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexPrivilegeUser extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('privilege_user');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('privilege_user')) {
            Schema::create('privilege_user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('role_id')->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->timestamps();
                $table->unique(['role_id', 'user_id']);
            });
        } else {
            Schema::table('privilege_user', function (Blueprint $table) {
                Schema::hasColumn('privilege_user', 'role_id') or $table->unsignedBigInteger('role_id')->index();
                Schema::hasColumn('privilege_user', 'user_id') or $table->unsignedBigInteger('user_id')->index();
                Schema::hasColumn('privilege_user', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('privilege_user', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
