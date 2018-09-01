<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexMenuItem extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('menu_item');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('menu_item')) {
            Schema::create('menu_item', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('menu_id')->index();
                $table->unsignedBigInteger('parent_id')->default(0)->index();
                $table->unsignedBigInteger('privilege_id')->default(0)->index();
                $table->string('label')->index();
                $table->string('description')->index();
                $table->string('icon');
                $table->text('url');
                $table->timestamps();
            });
        } else {
            Schema::table('menu_item', function (Blueprint $table) {
                Schema::hasColumn('menu_item', 'menu_id') or $table->unsignedBigInteger('menu_id')->index();
                Schema::hasColumn('menu_item', 'parent_id') or $table->unsignedBigInteger('parent_id')->default(0)->index();
                Schema::hasColumn('menu_item', 'privilege_id') or $table->unsignedBigInteger('privilege_id')->default(0)->index();
                Schema::hasColumn('menu_item', 'label') or $table->string('label')->index();
                Schema::hasColumn('menu_item', 'description') or $table->string('description')->index();
                Schema::hasColumn('menu_item', 'icon') or $table->string('icon');
                Schema::hasColumn('menu_item', 'url') or $table->text('url');
                Schema::hasColumn('menu_item', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('menu_item', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
