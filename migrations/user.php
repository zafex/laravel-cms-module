<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('user');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->string('email')->unique();
                $table->integer('status')->default(0)->index();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        } else {
            Schema::table('user', function (Blueprint $table) {
                Schema::hasColumn('user', 'name') or $table->string('name')->unique();
                Schema::hasColumn('user', 'email') or $table->string('email')->unique();
                Schema::hasColumn('user', 'status') or $table->integer('status')->default(0)->index();
                Schema::hasColumn('user', 'password') or $table->string('password');
                Schema::hasColumn('user', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('user', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
