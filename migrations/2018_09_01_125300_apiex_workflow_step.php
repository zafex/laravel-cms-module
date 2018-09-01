<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexWorkflowStep extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('workflow_step');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('workflow_step')) {
            Schema::create('workflow_step', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('workflow_id')->index();
                $table->unsignedBigInteger('role_id')->index();
                $table->integer('status')->default(0)->index();
                $table->timestamps();
            });
        } else {
            Schema::table('workflow_step', function (Blueprint $table) {
                Schema::hasColumn('workflow_step', 'workflow_id') or $table->unsignedBigInteger('workflow_id')->index();
                Schema::hasColumn('workflow_step', 'role_id') or $table->unsignedBigInteger('role_id')->index();
                Schema::hasColumn('workflow_step', 'status') or $table->integer('status')->default(0)->index();
                Schema::hasColumn('workflow_step', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('workflow_step', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
