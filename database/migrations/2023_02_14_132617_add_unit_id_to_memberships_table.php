<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->foreignId('unit_id')
                ->nullable()
                ->index()
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('financial_branch_id')
                ->nullable()
                ->index()
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('memberships', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
            $table->dropForeign(['financial_branch_id']);
            $table->dropColumn('financial_branch_id');
        });
    }
};
