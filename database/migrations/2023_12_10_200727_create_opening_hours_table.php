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
        Schema::create('OpeningHours', function (Blueprint $table) {
            $table->unsignedInteger('day_of_week');
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->primary('day_of_week');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('OpeningHours');
    }
};
