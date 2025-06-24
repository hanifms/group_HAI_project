<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Database Connection: " . config('database.default') . "\n";
echo "Database Name: " . config('database.connections.' . config('database.default') . '.database') . "\n\n";

// Show the status column details
if (config('database.default') === 'mysql') {
    // MySQL specific query
    $result = DB::select('SHOW COLUMNS FROM bookings WHERE Field = "status"');

    echo "Status Column Details (MySQL):\n";
    print_r($result);

    echo "\nAvailable Values for Status:\n";
    if (!empty($result) && isset($result[0]->Type)) {
        preg_match('/enum\((.*)\)/', $result[0]->Type, $matches);
        if (!empty($matches[1])) {
            $values = str_getcsv($matches[1], ',', "'");
            print_r($values);
        }
    }
} else {
    // SQLite or other database
    $result = DB::select('PRAGMA table_info(bookings)');

    echo "Status Column Details (SQLite):\n";
    $statusColumn = null;
    foreach ($result as $column) {
        if ($column->name === 'status') {
            $statusColumn = $column;
            break;
        }
    }

    if ($statusColumn) {
        print_r($statusColumn);
    } else {
        echo "Status column not found\n";
    }

    // Show some sample values
    echo "\nSample Status Values:\n";
    $statuses = DB::table('bookings')->select('status')->distinct()->get();
    print_r($statuses);
}

echo "\nBooking Model Status Constants:\n";
echo "PENDING: " . App\Models\Booking::STATUS_PENDING . "\n";
echo "CONFIRMED: " . App\Models\Booking::STATUS_CONFIRMED . "\n";
echo "ON_HOLD: " . App\Models\Booking::STATUS_ON_HOLD . "\n";
echo "ONGOING: " . App\Models\Booking::STATUS_ONGOING . "\n";
echo "COMPLETED: " . App\Models\Booking::STATUS_COMPLETED . "\n";
echo "CANCELLED: " . App\Models\Booking::STATUS_CANCELLED . "\n";
