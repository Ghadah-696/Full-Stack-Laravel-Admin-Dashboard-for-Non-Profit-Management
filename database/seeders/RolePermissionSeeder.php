<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar; // لنسيان الكاش

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 0. إعادة تعيين كاش الصلاحيات (ضروري لمكتبة Spatie)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. تحديد قائمة الصلاحيات
        $moduleDefinitions = [
            'user' => ['view', 'create', 'edit', 'delete'],
            // لاحظ: صلاحية 'manage_permissions' أصبحت صلاحية فردية بدلاً من أن تكون ضمن وحدة 'roles'
            'roles' => ['view', 'create', 'edit', 'delete'],
            'category' => ['view', 'create', 'edit', 'delete'],
            'document' => ['view', 'create', 'edit', 'delete'],
            'donation' => ['view'],
            'impact' => ['view', 'create', 'edit', 'delete'],
            'news' => ['view', 'create', 'edit', 'delete'],
            'page' => ['view', 'create', 'edit', 'delete'],
            'partner' => ['view', 'create', 'edit', 'delete'],
            'project' => ['view', 'create', 'edit', 'delete'],
            'slider' => ['view', 'create', 'edit', 'delete'],
            'story' => ['view', 'create', 'edit', 'delete'],
            'setting' => ['edit'],
        ];

        // 2. توليد قائمة أسماء الصلاحيات الكاملة
        $allPermissions = ['manage_permissions']; // صلاحية عامة واحدة

        foreach ($moduleDefinitions as $module => $actions) {
            foreach ($actions as $action) {
                $allPermissions[] = "{$action}_{$module}";
            }
        }

        // 3. إنشاء جميع الصلاحيات (نستخدم firstOrCreate لضمان عدم التكرار)
        foreach (array_unique($allPermissions) as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // =================================================================
        // 4. إنشاء الأدوار وتعيين الصلاحيات
        // =================================================================

        // جلب جميع الصلاحيات التي تم إنشاؤها للتو
        $permissionsList = Permission::all();

        // أ. الدور الرئيسي (Super Admin): يحصل على جميع الصلاحيات
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions($permissionsList);
        $this->command->info('تم إنشاء دور "super-admin" ومنحه جميع الصلاحيات.');


        // ب. دور مدخل البيانات (Data Entry)
        $dataEntryRole = Role::firstOrCreate(['name' => 'data-entry']);
        $dataEntryRole->syncPermissions([
            'create_project',
            'edit_project',
            'view_project',
            'edit_setting',
        ]);
        $this->command->info('تم إنشاء دور "data-entry" ومنحه صلاحيات محدودة.');
    }
    //     // 0. إعادة تعيين كاش الصلاحيات (ضروري لمكتبة Spatie)
    //     app()[PermissionRegistrar::class]->forgetCachedPermissions();


    //     // أ. الصلاحيات العامة الوحيدة (إدارة منظومة الصلاحيات)
    //     $globalPermissions = [
    //         'manage_permissions',
    //     ];
    //     $moduleDefinitions = [
    //         'user' => ['view', 'create', 'edit', 'delete'],
    //         // وحدة الأدوار: تتضمن صلاحية خاصة (manage_permissions)
    //         // هذه الصلاحية تتحكم بالوصول إلى ملف permission.blade.php
    //         'roles' => ['view', 'create', 'edit', 'delete', 'manage_permissions'],
    //         // وحدات CRUD كاملة مع صلاحيات إضافية
    //         'category' => ['view', 'create', 'edit', 'delete'],
    //         'document' => ['view', 'create', 'edit', 'delete'],
    //         'donation' => ['view'],
    //         'impact' => ['view', 'create', 'edit', 'delete'],
    //         'news' => ['view', 'create', 'edit', 'delete'],
    //         'page' => ['view', 'create', 'edit', 'delete'],
    //         'partner' => ['view', 'create', 'edit', 'delete'],
    //         'project' => ['view', 'create', 'edit', 'delete'],
    //         'slider' => ['view', 'create', 'edit', 'delete'],
    //         'story' => ['view', 'create', 'edit', 'delete'],

    //         // وحدة الإعدادات: لديها عملية تعديل فقط
    //         'setting' => ['edit'],

    //         // وحدة التبرعات: لديها عرض قائمة (index) وعرض فردي (view) فقط
    //         // في Laravel: 'index' corresponds to 'view' (to see the list)
    //         // and 'view' corresponds to 'view' or sometimes 'view' again if not detailed.
    //         // سنستخدم 'view' لقائمة التبرعات و 'view' لعرض تبرع واحد بتفاصيله.

    //     ];

    //     $allPermissions = [];
    //     // أ. إضافة الصلاحيات العامة أولاً (ستكون فقط manage_permissions)
    //     $allPermissions = array_merge($allPermissions, $globalPermissions);


    //     // ب. توليد الصلاحيات الخاصة بالوحدات
    //     foreach ($moduleDefinitions as $module => $actions) {
    //         foreach ($actions as $action) {

    //             $allPermissions[] = "{$action}_{$module}";
    //         }
    //     }
    //     // =================================================================
    //     // 3. إنشاء جميع الصلاحيات في قاعدة البيانات
    //     // =================================================================
    //     foreach ($allPermissions as $permissionName) {
    //         Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
    //     }

    //     // 3. مسح الجداول وإعادة الإنشاء لضمان النظافة (للتطوير)
    //     DB::table('role_has_permissions')->delete();
    //     DB::table('model_has_roles')->delete();
    //     Permission::query()->delete(); // استخدم query()->delete() بدلاً من truncate() لأنه يعمل بشكل أفضل مع MySQL


    //     // 4. إنشاء سجلات الصلاحيات في قاعدة البيانات
    //     foreach (array_unique($allPermissions) as $permission) {
    //         Permission::firstOrCreate(['name' => $permission]);
    //     }

    //     // 5. إنشاء الأدوار وتعيين الصلاحيات

    //     $permissionsList = Permission::all();

    //     // أ. الدور الرئيسي (Super Admin): يحصل على جميع الصلاحيات
    //     $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
    //     $superAdminRole->syncPermissions($permissionsList);

    //     // ب. دور مدير المتجر (Manager)
    //     $dataEntryRole = Role::firstOrCreate(['name' => 'data-entry']);
    //     $dataEntryRole->givePermissionTo([
    //         +
    //         'create_projects',
    //         'edit_projects',
    //         'edit_settings',
    //     ]);
    // }
}