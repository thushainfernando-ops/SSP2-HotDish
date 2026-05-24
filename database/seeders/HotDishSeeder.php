<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotDishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Users
        \App\Models\User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'phone' => '0771234567',
            'role' => 'user',
        ]);

        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@hotdish.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'phone' => '0777654321',
            'role' => 'admin',
        ]);

        // Menu Items
        $menuItems = [
            ['name' => 'Traditional Rice & Curry', 'description' => 'Authentic Sri Lankan rice with 5 curries', 'price' => 850.00, 'category' => 'rice-curry', 'image' => 'assets/vegetable-rice-curry.jpg'],
            ['name' => 'Chicken Rice & Curry', 'description' => 'Spicy chicken curry with rice and sides', 'price' => 950.00, 'category' => 'rice-curry', 'image' => 'assets/rice-and-curry.jpg'],
            ['name' => 'Seafood Rice & Curry', 'description' => 'Ocean fresh seafood with red rice', 'price' => 1200.00, 'category' => 'rice-curry', 'image' => 'assets/seafood-rice-curry.jpg'],
            ['name' => 'Seafood Noodles', 'description' => 'Fresh seafood stir-fried with noodles', 'price' => 1250.00, 'category' => 'noodles', 'image' => 'assets/seafood-noodles-new.jpg'],
            ['name' => 'Mixed Noodles', 'description' => 'Chicken, egg, and vegetable mix noodles', 'price' => 1350.00, 'category' => 'noodles', 'image' => 'assets/mixed-noodles.jpg'],
            ['name' => 'Mixed Fried Rice', 'description' => 'Special fried rice with mixed meats', 'price' => 1400.00, 'category' => 'fried-rice', 'image' => 'assets/mixed-fried-rice.jpg'],
            ['name' => 'Special Fried Rice', 'description' => 'Special fried rice with exotic spices', 'price' => 1450.00, 'category' => 'fried-rice', 'image' => 'assets/chicken-biryani.jpg'],
            ['name' => 'Creamy Chicken Pasta', 'description' => 'Creamy pasta with grilled chicken', 'price' => 1150.00, 'category' => 'pasta', 'image' => 'assets/prawn-curry.jpg'],
        ];

        foreach ($menuItems as $item) {
            \App\Models\MenuItem::create($item);
        }
    }
}
