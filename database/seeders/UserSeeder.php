<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // 1. إنشاء مستخدم المدير العام (Admin)
        // هذا يتطلب وجود دور 'Admin' في قاعدة البيانات مسبقاً

        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('123456789admin*'),
            ]
        )->assignRole('super-admin');

        // 2. إنشاء مستخدم مدخل البيانات
        // هذا يتطلب وجود دور 'Data Entry' في قاعدة البيانات مسبقاً
        User::firstOrCreate(
            ['email' => 'dataentry@test.com'],
            [
                'name' => 'Data Entry User',
                'password' => bcrypt('12345678dataentry*'),
            ]
        )->assignRole('data-entry');
    }
}
