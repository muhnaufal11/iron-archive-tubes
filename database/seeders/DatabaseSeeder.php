<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Nation;
use App\Models\Category;
use App\Models\User;
use App\Models\Vehicle;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // --- Akun default (dipakai untuk login manual & pengujian Selenium) ---
        $admin = User::firstOrCreate(
            ['email' => 'admin@ironarchive.test'],
            ['name' => 'Komandan Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'user@ironarchive.test'],
            ['name' => 'Prajurit Biasa', 'password' => Hash::make('password'), 'role' => 'user']
        );

        // --- Master data Negara ---
        $nations = [];
        foreach (['Nazi Germany', 'Soviet Union (USSR)', 'United States (USA)', 'Empire of Japan'] as $n) {
            $nations[$n] = Nation::firstOrCreate(['name' => $n]);
        }

        // --- Master data Kategori ---
        $categories = [];
        foreach (['Heavy Tank', 'Medium Tank', 'Fighter Aircraft', 'Bomber'] as $c) {
            $categories[$c] = Category::firstOrCreate(['name' => $c]);
        }

        // --- Contoh kendaraan (agar halaman index & Selenium ada datanya) ---
        Vehicle::firstOrCreate(
            ['name' => 'Tiger I'],
            [
                'nation_id' => $nations['Nazi Germany']->id,
                'category_id' => $categories['Heavy Tank']->id,
                'production_year' => 1942,
                'quantity' => 1347,
                'battles' => 'Kursk',
                'description' => 'Tank berat Jerman yang ikonik pada Perang Dunia II.',
            ]
        );

        Vehicle::firstOrCreate(
            ['name' => 'T-34'],
            [
                'nation_id' => $nations['Soviet Union (USSR)']->id,
                'category_id' => $categories['Medium Tank']->id,
                'production_year' => 1940,
                'quantity' => 84000,
                'battles' => 'Stalingrad',
                'description' => 'Tank medium Soviet yang diproduksi massal.',
            ]
        );
    }
}
