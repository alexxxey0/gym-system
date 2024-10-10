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
        Schema::create('group_trainings', function (Blueprint $table) {
            $table->bigIncrements('training_id');
            $table->string('name', 100);
            $table->text('description');
            $table->unsignedBigInteger('coach_id');
            $table->json('schedule');
            $table->tinyInteger('clients_signed_up', unsigned: true);
            $table->tinyInteger('max_clients', unsigned: true);
            $table->string('path_to_image', 100)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('coach_id')->references('coach_id')->on('coaches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_trainings');
    }
};
