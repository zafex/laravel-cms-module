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
                $table->string('label')->index();
                $table->text('description');
                $table->timestamps();
            });
        } else {
            Schema::table('menu', function (Blueprint $table) {
                Schema::hasColumn('menu', 'label') or $table->string('label')->index();
                Schema::hasColumn('menu', 'description') or $table->text('description');
                Schema::hasColumn('menu', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('menu', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
