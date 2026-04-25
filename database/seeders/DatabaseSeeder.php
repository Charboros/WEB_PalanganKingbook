<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567890',
        ]);

        // User
        User::create([
            'name' => 'Regular User',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '089876543210',
        ]);

        // Field Types
        $futsal = \App\Models\FieldType::create(['name' => 'Futsal']);
        $badminton = \App\Models\FieldType::create(['name' => 'Badminton']);
        $basket = \App\Models\FieldType::create(['name' => 'Basket']);

        // Fields
        \App\Models\Field::create([
            'field_type_id' => $futsal->id,
            'name' => 'Futsal 1 (Vinyl)',
            'price_offpeak' => 100000,
            'price_peak' => 150000,
            'description' => 'Lapangan Futsal Vinyl Standar Nasional',
            'is_active' => true,
        ]);

        \App\Models\Field::create([
            'field_type_id' => $futsal->id,
            'name' => 'Futsal 2 (Sintetis)',
            'price_offpeak' => 120000,
            'price_peak' => 180000,
            'description' => 'Lapangan Futsal Rumput Sintetis',
            'is_active' => true,
        ]);

        \App\Models\Field::create([
            'field_type_id' => $badminton->id,
            'name' => 'Badminton 1',
            'price_offpeak' => 40000,
            'price_peak' => 60000,
            'description' => 'Lapangan Badminton Karpet',
            'is_active' => true,
        ]);

        \App\Models\Field::create([
            'field_type_id' => $basket->id,
            'name' => 'Basket Utama',
            'price_offpeak' => 150000,
            'price_peak' => 225000,
            'description' => 'Lapangan Basket Indoor Standar FIBA',
            'is_active' => true,
        ]);
    }
}
