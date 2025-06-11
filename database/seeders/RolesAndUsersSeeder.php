<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Import model Role dari Spatie
use App\Models\User;                 // Import model User Anda

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'tim_dapur']);
        Role::create(['name' => 'tim_packing']);
        Role::create(['name' => 'driver']);

        // 2. Buat User Contoh
        
        // Buat User Admin
        $adminUser = User::factory()->create([
            'name' => 'Admin Tirta',
            'email' => 'admin@tirtacatering.com',
            'password' => bcrypt('password'), // Ganti 'password' dengan password yang aman
        ]);
        // Berikan peran 'admin' ke user tersebut
        $adminUser->assignRole('admin');


        // Buat User Tim Dapur
        $dapurUser = User::factory()->create([
            'name' => 'Tim Dapur 1',
            'email' => 'dapur@tirtacatering.com',
            'password' => bcrypt('password'), // Ganti 'password' dengan password yang aman
        ]);
        // Berikan peran 'tim_dapur' ke user tersebut
        $dapurUser->assignRole('tim_dapur');

        // Anda bisa membuat user contoh lain untuk tim_packing dan driver dengan cara yang sama jika perlu
    }
}