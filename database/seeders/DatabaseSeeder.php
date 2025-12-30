<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. يجب استدعاء ملف RolesAndPermissionsSeeder أولاً.
        //    هذا يضمن أن الأدوار (مثل Admin و Data Entry) موجودة في قاعدة البيانات.
        $this->call(RolePermissionSeeder::class);

        // 2. يجب استدعاء ملف UserSeeder ثانياً.
        //    هذا يضمن أن المستخدمين (admin@example.com و dataentry@test.com)
        //    يتم إنشاؤهم ويمكن تعيين الأدوار الموجودة لهم بنجاح.
        $this->call(UserSeeder::class);

        // 3. استدعاء أي seeders أخرى غير معتمدة على ما سبق
        $this->call(CategorySeeder::class);
        // $this->call(ProductSeeder::class);

    }
}
