<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * هذا التابع هو المكان الذي نحدد فيه المهام التي يجب تشغيلها
     * بشكل دوري، مثل النسخ الاحتياطي لقاعدة البيانات.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // تشغيل أمر النسخ الاحتياطي مرة واحدة يومياً في الساعة 01:00 صباحاً.
        $schedule->command('backup:run')->everyMinute();


        // خيارات جدولة أخرى مفيدة:
        // $schedule->command('backup:run')->daily(); // يومياً في منتصف الليل (00:00)
        // $schedule->command('backup:run')->weekly(); // أسبوعياً في منتصف ليلة الأحد
        // $schedule->command('backup:run')->cron('0 3 * * *'); // يومياً في 03:00 صباحاً باستخدام تعبير Cron
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}