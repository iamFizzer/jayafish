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
        // User::factory(10)->create();

        $admin=User::updateOrCreate(['username'=>'admin'],['name'=>'Administrator','email'=>'admin@rrjayafishing.test','password'=>'password','role'=>'admin','is_active'=>true]);
        User::updateOrCreate(['username'=>'karyawan'],['name'=>'Dina Karyawan','email'=>'karyawan@rrjayafishing.test','password'=>'password','role'=>'karyawan','is_active'=>true]);
        User::updateOrCreate(['username'=>'owner'],['name'=>'Pemilik RR Jaya','email'=>'owner@rrjayafishing.test','password'=>'password','role'=>'owner','is_active'=>true]);
        $this->call(ProductCatalogSeeder::class);
    }
}
