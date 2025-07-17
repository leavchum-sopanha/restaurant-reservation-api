<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create sample customers
        $customers = [
            [
                'name' => 'John Doe',
                'phone' => '+1234567890',
                'email' => 'john.doe@example.com',
            ],
            [
                'name' => 'Jane Smith',
                'phone' => '+1234567891',
                'email' => 'jane.smith@example.com',
            ],
            [
                'name' => 'Bob Johnson',
                'phone' => '+1234567892',
                'email' => 'bob.johnson@example.com',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create sample tables
        $tables = [
            ['number' => 1, 'capacity' => 2],
            ['number' => 2, 'capacity' => 4],
            ['number' => 3, 'capacity' => 6],
            ['number' => 4, 'capacity' => 8],
            ['number' => 5, 'capacity' => 2],
            ['number' => 6, 'capacity' => 4],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }

        // Create sample reservations
        $reservations = [
            [
                'customer_id' => 1,
                'table_id' => 1,
                'date_time' => now()->addDays(1)->setTime(19, 0),
                'note' => 'Anniversary dinner',
            ],
            [
                'customer_id' => 2,
                'table_id' => 2,
                'date_time' => now()->addDays(2)->setTime(20, 0),
                'note' => 'Business meeting',
            ],
            [
                'customer_id' => 3,
                'table_id' => 3,
                'date_time' => now()->addDays(3)->setTime(18, 30),
                'note' => 'Family dinner',
            ],
        ];

        foreach ($reservations as $reservation) {
            Reservation::create($reservation);
        }
    }
}

