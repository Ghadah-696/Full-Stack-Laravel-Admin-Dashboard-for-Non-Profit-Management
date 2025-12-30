<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // =======================================================
        // 1. تعريف View Composer لقالب الشريط العلوي (أو أي قالب ثابت)
        // =======================================================
        // يفترض أن قالب الشريط العلوي لديك هو 'layouts.partials.navbar'
        // قم بتعديل هذا المسار ليناسب مسار ملف الشريط العلوي في مشروعك.
        View::composer('layouts.admin', function ($view) {

            // تحقق من أن المستخدم الحالي مسجل الدخول
            if (auth()->check()) {
                // الحصول على آخر 5 إشعارات غير مقروءة
                $unreadNotifications = auth()->user()
                    ->unreadNotifications()
                    ->limit(5)
                    ->get();

                // الحصول على العدد الإجمالي للإشعارات غير المقروءة
                $unreadCount = auth()->user()->unreadNotifications()->count();

                // تمرير المتغيرات إلى الـ View
                $view->with([
                    'unreadNotifications' => $unreadNotifications,
                    'unreadCount' => $unreadCount,
                ]);
            } else {
                // في حال عدم تسجيل الدخول، نرسل قيم فارغة لتجنب الأخطاء
                $view->with([
                    'unreadNotifications' => collect(),
                    'unreadCount' => 0,
                ]);
            }
        });
    }

    /**
     * تسجيل أي خدمات تطبيق.
     *
     * @return void
     */
}
