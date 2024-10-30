<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('client_id');
            $table->string('payment_method', 30);
            $table->string('payment_purpose', 30);
            $table->unsignedBigInteger('membership_id');
            $table->string('payment_status', 30);
            $table->decimal('amount', 6, 2);
            $table->timestamps();
            $table->dateTime('completed_at')->nullable();

            $table->foreign('membership_id')->references('membership_id')->on('memberships');
            $table->foreign('client_id')->references('client_id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
