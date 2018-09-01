<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexPrivilege extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('privilege');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('privilege')) {
            Schema::create('privilege', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->index();
                $table->string('section')->default('role')->index();
                $table->text('description');
                $table->timestamps();
                $table->unique(['name', 'section']);
            });
        } else {
            Schema::table('privilege', function (Blueprint $table) {
                Schema::hasColumn('privilege', 'name') or $table->string('name')->index();
                Schema::hasColumn('privilege', 'section') or $table->string('section')->default('role')->index();
                Schema::hasColumn('privilege', 'description') or $table->text('description');
                Schema::hasColumn('privilege', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('privilege', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
