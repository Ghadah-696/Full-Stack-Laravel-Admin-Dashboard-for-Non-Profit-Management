<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\ImpactController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminController; // New controller for the dashboard
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
});


// Route::post('/run-backup', function () {
//     try {
//         // تشغيل أمر النسخ الاحتياطي بشكل آمن داخل Laravel
//         Artisan::call('backup:run');

//         // إرجاع استجابة JSON للـ JavaScript عند النجاح
//         return response()->json([
//             'success' => true,
//             'message' => 'تم إنشاء النسخة الاحتياطية بنجاح. يمكنك مراجعة الملفات في التخزين السحابي أو مجلد التخزين المحلي.',
//         ], 200);

//     } catch (\Exception $e) {
//         // إرجاع استجابة خطأ JSON في حالة الفشل
//         // يمكنك تسجيل الخطأ هنا: Log::error($e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'فشل في تشغيل النسخ الاحتياطي. الخطأ: ' . $e->getMessage(),
//         ], 500);
//     }
// })->name('admin.backup.run')->middleware('auth', 'admin');
// Route::post('/run-backup', function () {
//     // تشغيل الأمر الخاص بالنسخ الاحتياطي يدوياً
//     Artisan::call('backup:run');

//     // إرجاع رسالة نجاح للمستخدم
//     return response()->json(['message' => 'تم بدء النسخ الاحتياطي يدوياً بنجاح. تحقق من السجلات.'], 200);
// })->middleware('auth');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // مسار لوحة التحكم الخاصة بالمشروع
    // The dashboard route, now handled by the AdminController
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::put('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])
        ->name('projects.toggle-status');

    Route::put('news/{news}/toggle-status', [NewsController::class, 'toggleStatus'])
        ->name('news.toggle-status');

    Route::put('sliders/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])
        ->name('sliders.toggle-status');

    Route::put('stories/{story}/toggle-status', [StoryController::class, 'toggleStatus'])
        ->name('stories.toggle-status');
    Route::put('impacts/{impact}/toggle-status', [ImpactController::class, 'toggleStatus'])
        ->name('impacts.toggle-status');

    Route::put('documents/{document}/toggle-status', [DocumentController::class, 'toggleStatus'])
        ->name('documents.toggle-status');

    Route::put('partners/{partner}/toggle-status', [PartnerController::class, 'toggleStatus'])
        ->name('partners.toggle-status');

    Route::put('categories/{category}/toggle-status', [categoryController::class, 'toggleStatus'])
        ->name('categories.toggle-status');

    Route::put('categories/{category}/toggle-status', [categoryController::class, 'toggleStatus'])
        ->name('categories.toggle-status');


    // هنا يتم تعريف جميع المسارات الخاصة بلوحة التحكم
    // لاحظ أننا قمنا بإزالة group() المتداخلة
    // يجب أن يكون هذا قبل routes news/projects
    Route::resource('categories', CategoryController::class);
    Route::resource('news', NewsController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('impacts', ImpactController::class);
    Route::resource('pages', PageController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('stories', StoryController::class);
    Route::resource('documents', DocumentController::class);
    Route::resource('partners', PartnerController::class);
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::resource('donations', DonationController::class)->except(['create', 'store', 'edit', 'update']);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class)->names('roles');
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');


    // مسار لتعليم إشعار واحد كمقروء والانتقال إلى رابط الإشعار
    Route::get('notifications/{notification}/read', [NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.read');

    // المسار المخصص لتحديث كلمة مرور مستخدم معين (يستخدم دالة updatePassword)
    Route::patch('users/{user}/password', [UserController::class, 'updatePassword'])
        ->name('users.update-password');
    // Route::get('/backup', [SettingController::class, 'edit'])
    //     ->name('settings');
    Route::post('run-backup', [SettingController::class, 'runBackup'])
        ->name('backup.run');
    // مسارات إدارة الصلاحيات (Permissions)
// هذه المسارات ضرورية لربط الصلاحيات بالدور (Role) بعد إنشائه،
// وهي منفصلة عن عملية تعديل اسم الدور أو وصفه.
    Route::prefix('roles')->name('roles.')->group(function () {
        // 1. مسار عرض صفحة تعديل الصلاحيات لدور معين (GET)
        Route::get('{role}/permissions', [RoleController::class, 'editPermissions'])->name('permissions');

        // 2. مسار حفظ التعديلات على الصلاحيات (PUT/PATCH)
        Route::put('{role}/permissions', [RoleController::class, 'updatePermissions'])->name('update-permissions');
    });



});


require __DIR__ . '/auth.php';