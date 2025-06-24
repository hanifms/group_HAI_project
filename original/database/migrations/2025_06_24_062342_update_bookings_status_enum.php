<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use DB::statement to modify the enum values for MySQL
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'on_hold', 'ongoing', 'completed', 'cancelled') DEFAULT 'pending'");
        } else {
            // For SQLite or other databases that don't support ENUM
            // First create a backup of the current data
            $bookings = DB::table('bookings')->get();

            // Drop the status column
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropColumn('status');
            });

            // Add the status column back with string type
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('status')->default('pending');
            });

            // Restore the data
            foreach ($bookings as $booking) {
                DB::table('bookings')
                    ->where('id', $booking->id)
                    ->update(['status' => $booking->status]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the original enum values
        if (config('database.default') === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'on_hold', 'completed') DEFAULT 'pending'");
        } else {
            // For SQLite or other databases that don't support ENUM
            // No need to change the column type, just validate values in the application code
        }
    }
};
