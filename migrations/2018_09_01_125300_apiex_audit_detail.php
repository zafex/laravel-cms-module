<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ApiexAuditDetail extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('audit_detail');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('audit_detail')) {
            Schema::create('audit_detail', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('audit_id')->index();
                $table->string('field')->index();
                $table->text('old_value');
                $table->text('new_value');
            });
        } else {
            Schema::table('audit_detail', function (Blueprint $table) {
                Schema::hasColumn('audit_detail', 'audit_id') or $table->unsignedBigInteger('audit_id')->index();
                Schema::hasColumn('audit_detail', 'field') or $table->string('field')->index();
                Schema::hasColumn('audit_detail', 'old_value') or $table->text('old_value');
                Schema::hasColumn('audit_detail', 'new_value') or $table->text('new_value');
            });
        }
    }
}
