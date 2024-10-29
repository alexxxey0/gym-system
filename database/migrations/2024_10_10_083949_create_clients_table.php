<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('client_id');
            $table->string('name', 30);
            $table->string('surname', 30);
            $table->char('personal_id', 12)->unique();
            $table->string('password', 256);
            $table->string('phone', 20)->unique();
            $table->string('email', 50)->unique();
            $table->unsignedBigInteger('membership_id')->nullable();
            $table->date('membership_until')->nullable();
            $table->string('role', 30);
            $table->timestamps();

            $table->foreign('membership_id')->references('membership_id')->on('memberships');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('clients');
    }
};
