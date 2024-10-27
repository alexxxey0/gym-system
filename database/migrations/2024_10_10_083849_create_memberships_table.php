<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('memberships', function (Blueprint $table) {
            $table->bigIncrements('membership_id');
            $table->string('membership_name', 30)->unique();
            $table->decimal('price', 6, 2);
            $table->boolean('group_trainings_included');
            $table->time('entry_from_workdays')->default('08:00:00');
            $table->time('entry_until_workdays')->default('22:00:00');
            $table->time('entry_from_weekends')->default('09:00:00');
            $table->time('entry_until_weekends')->default('20:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('memberships');
    }
};
