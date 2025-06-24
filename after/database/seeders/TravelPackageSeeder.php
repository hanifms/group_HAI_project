<?php

namespace Database\Seeders;

use App\Models\TravelPackage;
use Illuminate\Database\Seeder;

class TravelPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $travelPackages = [
            [
                'name' => 'Exciting Korea Adventure',
                'description' => 'Experience the rich culture and beautiful landscapes of South Korea. Visit Seoul, Busan, and Jeju Island.',
                'destination' => 'Korea',
                'price' => 2500.00,
                'start_date' => '2025-09-01',
                'end_date' => '2025-09-10',
                'available_slots' => 20,
            ],
            [
                'name' => 'Japan Cherry Blossom Tour',
                'description' => 'Witness the breathtaking cherry blossoms in Japan while exploring Tokyo, Kyoto, and Osaka.',
                'destination' => 'Japan',
                'price' => 3000.00,
                'start_date' => '2025-07-15',
                'end_date' => '2025-07-25',
                'available_slots' => 15,
            ],
            [
                'name' => 'China Heritage Tour',
                'description' => 'Explore the ancient wonders of China, including the Great Wall, Forbidden City, and Terracotta Army.',
                'destination' => 'China',
                'price' => 2800.00,
                'start_date' => '2025-08-10',
                'end_date' => '2025-08-20',
                'available_slots' => 18,
            ],
            [
                'name' => 'Malaysia Tropical Getaway',
                'description' => 'Enjoy the tropical paradise of Malaysia with visits to Kuala Lumpur, Penang, and Langkawi.',
                'destination' => 'Malaysia',
                'price' => 1800.00,
                'start_date' => '2025-10-05',
                'end_date' => '2025-10-12',
                'available_slots' => 25,
            ],
            [
                'name' => 'Singapore City Explorer',
                'description' => 'Discover the modern marvels and cultural diversity of Singapore.',
                'destination' => 'Singapore',
                'price' => 1500.00,
                'start_date' => '2025-11-01',
                'end_date' => '2025-11-05',
                'available_slots' => 30,
            ],
            [
                'name' => 'Brunei Cultural Experience',
                'description' => 'Immerse yourself in the rich culture and history of Brunei.',
                'destination' => 'Brunei',
                'price' => 1200.00,
                'start_date' => '2025-12-10',
                'end_date' => '2025-12-15',
                'available_slots' => 15,
            ],
            [
                'name' => 'Australian Outback Adventure',
                'description' => 'Experience the unique landscapes and wildlife of Australia.',
                'destination' => 'Australia',
                'price' => 3500.00,
                'start_date' => '2026-01-05',
                'end_date' => '2026-01-15',
                'available_slots' => 12,
            ],
        ];

        foreach ($travelPackages as $package) {
            TravelPackage::create($package);
        }
    }
}
