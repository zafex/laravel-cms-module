<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexWorkflowVerificator extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('workflow_verificator');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('workflow_verificator')) {
            Schema::create('workflow_verificator', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('workflow_step_id')->index();
                $table->unsignedBigInteger('user_id')->default(0)->index();
                $table->text('position');
                $table->integer('status')->default(0)->index();
                $table->timestamps();
            });
        } else {
            Schema::table('workflow_verificator', function (Blueprint $table) {
                Schema::hasColumn('workflow_verificator', 'workflow_step_id') or $table->unsignedBigInteger('workflow_step_id')->index();
                Schema::hasColumn('workflow_verificator', 'user_id') or $table->unsignedBigInteger('user_id')->default(0)->index();
                Schema::hasColumn('workflow_verificator', 'position') or $table->text('position');
                Schema::hasColumn('workflow_verificator', 'status') or $table->integer('status')->default(0)->index();
                Schema::hasColumn('workflow_verificator', 'created_at') or $table->timestamp('created_at')->default(date('Y-m-d H:i:s'));
                Schema::hasColumn('workflow_verificator', 'updated_at') or $table->timestamp('updated_at')->default(date('Y-m-d H:i:s'));
            });
        }
    }
}
