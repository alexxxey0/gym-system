<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('client_training', function (Blueprint $table) {
            $table->bigIncrements('client_training_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('training_id');
            $table->timestamps();

            $table->foreign('client_id')->references('client_id')->on('clients');
            $table->foreign('training_id')->references('training_id')->on('group_trainings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('client_training');
    }
};
