<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('canceled_trainings', function (Blueprint $table) {
            $table->bigIncrements('canceled_training_id');
            $table->unsignedBigInteger('training_id');
            $table->date('training_date');
            $table->timestamps();

            $table->foreign('training_id')->references('training_id')->on('group_trainings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canceled_trainings');
    }
};
