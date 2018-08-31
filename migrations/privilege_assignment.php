<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrivilegeAssignment extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('privilege_assignment');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('privilege_assignment')) {
            Schema::create('privilege_assignment', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('role_id')->index();
                $table->unsignedBigInteger('permission_id')->index();
                $table->timestamps();
                $table->unique(['role_id', 'permission_id']);
            });
        } else {
            Schema::table('privilege_assignment', function (Blueprint $table) {
                Schema::hasColumn('privilege_assignment', 'role_id') or $table->unsignedBigInteger('role_id')->index();
                Schema::hasColumn('privilege_assignment', 'permission_id') or $table->unsignedBigInteger('permission_id')->index();
                Schema::hasColumn('privilege_assignment', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('privilege_assignment', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
