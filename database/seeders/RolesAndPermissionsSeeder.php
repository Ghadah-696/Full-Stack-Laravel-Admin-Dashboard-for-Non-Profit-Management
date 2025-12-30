<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مسح الكاش قبل البدء
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. تعريف الوحدات (الموديلات) - تأكدي من شمول جميع وحداتك
        $modules = ['partner', 'page', 'setting', 'category', 'news', 'project', 'impact', 'slider', 'story', 'document', 'donation', 'user', 'roles'];

        // 2. إنشاء الصلاحيات (CRUD) لكل وحدة
        foreach ($modules as $module) {
            Permission::firstOrCreate(['name' => 'view_' . $module]);
            Permission::firstOrCreate(['name' => 'create_' . $module]);
            Permission::firstOrCreate(['name' => 'edit_' . $module]);
            Permission::firstOrCreate(['name' => 'delete_' . $module]);
        }

        // صلاحية خاصة بالإعدادات فقط
        Permission::firstOrCreate(['name' => 'edit_setting']);
        Permission::firstOrCreate(['name' => 'manage_roles']);
        Permission::firstOrCreate(['name' => 'manage_users']);
        // 1. تحديد قائمة الصلاحيات
        $permissions = [
            // صلاحيات المستخدمين والأدوار
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',

            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',

            // صلاحية الإعدادات
            'edit_setting',

            // أضيفي هنا أي صلاحيات أخرى (مثل: view_products, create_products, view_reports)
        ];
        // 2. إنشاء الصلاحيات
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        // 3. إنشاء الأدوار
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleDataEntry = Role::firstOrCreate(['name' => 'Data Entry']);
        // $roleDataEntry = Role::firstOrCreate(['name' => 'Data Entry']);

        // 4. ربط الصلاحيات بالأدوار
        $roleAdmin->givePermissionTo(Permission::all()); // المدير العام يأخذ كل الصلاحيات
        // 💡 تعيين صلاحيات مدخل البيانات
        $roleDataEntry->givePermissionTo([
            // صلاحيات الأخبار
            'view_news',
            'create_news',
            'edit_news',

            // صلاحيات المشاريع
            'view_project',
            'create_project',
            'edit_project',

            // صلاحية العرض فقط لبعض الوحدات
            'view_category',
            'view_impact',
            'view_document',
        ]);

        // 5. تعيين دور المدير العام للمستخدم الأول
        $user = User::first();
        if ($user) {
            $user->assignRole($roleAdmin);
        }

    }
}
