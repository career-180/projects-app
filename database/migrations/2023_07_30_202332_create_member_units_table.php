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
        Schema::create('member_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->index()->constrained()->cascadeOnDelete();
            $table->date('movement_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_units');
    }
};
