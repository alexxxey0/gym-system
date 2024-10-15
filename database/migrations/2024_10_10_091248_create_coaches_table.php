<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('coaches', function (Blueprint $table) {
            $table->bigIncrements('coach_id');
            $table->string('name', 30);
            $table->string('surname', 30);
            $table->char('personal_id', 12)->unique();
            $table->string('password', 256);
            $table->string('phone', 20)->unique();
            $table->string('email', 50)->unique();
            $table->text('personal_description')->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->string('contact_email', 50)->nullable();
            $table->string('path_to_image', 100)->nullable();
            $table->string('role', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('coaches');
    }
};
