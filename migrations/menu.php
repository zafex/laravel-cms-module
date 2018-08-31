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
        // Schema::dropIfExists('menu');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menu')) {
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
        } else {
            Schema::table('menu', function (Blueprint $table) {
                Schema::hasColumn('menu', 'parent_id') or $table->unsignedBigInteger('parent_id')->default(0)->index();
                Schema::hasColumn('menu', 'privilege_id') or $table->unsignedBigInteger('privilege_id')->default(0)->index();
                Schema::hasColumn('menu', 'type') or $table->string('type')->default('main')->index();
                Schema::hasColumn('menu', 'label') or $table->string('label')->index();
                Schema::hasColumn('menu', 'icon') or $table->string('icon')->index();
                Schema::hasColumn('menu', 'url') or $table->text('url');
                Schema::hasColumn('menu', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('menu', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
