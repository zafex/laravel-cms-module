<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexWorkflow extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('workflow');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('workflow')) {
            Schema::create('workflow', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->index();
                $table->integer('status')->default(0)->index();
                $table->timestamps();
            });
        } else {
            Schema::table('workflow', function (Blueprint $table) {
                Schema::hasColumn('workflow', 'name') or $table->string('name')->index();
                Schema::hasColumn('workflow', 'status') or $table->integer('status')->default(0)->index();
                Schema::hasColumn('workflow', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('workflow', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
