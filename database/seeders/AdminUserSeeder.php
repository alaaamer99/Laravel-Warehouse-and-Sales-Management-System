<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SalesRepresentative;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Super Admin User
        User::create([
            'name' => 'المدير العام',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'phone' => '01234567890',
            'address' => 'القاهرة، مصر',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Regular Admin User
        User::create([
            'name' => 'مدير النظام',
            'email' => 'manager@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '01234567891',
            'address' => 'الجيزة، مصر',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Sales Representative User
        $salesUser = User::create([
            'name' => 'أحمد محمد',
            'email' => 'sales@admin.com',
            'password' => Hash::make('password'),
            'role' => 'sales_representative',
            'phone' => '01234567892',
            'address' => 'الإسكندرية، مصر',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Sales Representative profile
        SalesRepresentative::create([
            'user_id' => $salesUser->id,
            'name' => $salesUser->name,
            'address' => $salesUser->address,
            'phone' => $salesUser->phone,
            'balance' => 0,
            'is_active' => true,
        ]);
    }
}
