<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create demo admin user
        AdminUser::create([
            'admin_name' => 'Admin',
            'admin_email' => 'admin@admin.com',
            'admin_password' => Hash::make('password'),
            'admin_phone' => '+1 (555) 000-0000',
            'admin_role' => 'Super Admin',
            'is_active' => true,
        ]);
    }
}
