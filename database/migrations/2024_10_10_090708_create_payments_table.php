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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('payment_id');
            $table->unsignedBigInteger('client_id');
            $table->string('payment_method', 30);
            $table->string('payment_purpose', 30);
            $table->string('membership_name', 30);
            $table->string('payment_status', 30);
            $table->timestamps();

            $table->foreign('membership_name')->references('membership_name')->on('memberships');
            $table->foreign('client_id')->references('client_id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
