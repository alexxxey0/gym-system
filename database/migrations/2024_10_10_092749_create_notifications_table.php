<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->bigIncrements('notification_id');
            $table->string('notification_topic', 100);
            $table->text('notification_text');
            $table->unsignedBigInteger('sender_coach_id')->nullable();
            $table->boolean('from_admin')->default(false);
            $table->unsignedBigInteger('receiver_training_id');
            $table->timestamps();

            $table->foreign('sender_coach_id')->references('coach_id')->on('coaches');
            $table->foreign('receiver_training_id')->references('training_id')->on('group_trainings');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('notifications');
    }
};
