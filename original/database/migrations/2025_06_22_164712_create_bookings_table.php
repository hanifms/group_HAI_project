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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('travel_package_id')->constrained()->onDelete('cascade');
            $table->dateTime('booking_date')->default(now());

            // Use enum for MySQL, string for SQLite
            if (config('database.default') === 'mysql') {
                $table->enum('status', ['pending', 'confirmed', 'on_hold', 'completed', 'ongoing', 'cancelled'])->default('pending');
            } else {
                $table->string('status')->default('pending');
            }

            $table->integer('number_of_travelers')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
