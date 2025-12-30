<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User; // تأكد من أن هذا هو مسار نموذج المستخدم الخاص بك
use Spatie\Permission\Traits\HasRoles;

class GetUserRoles extends Command
{
    /**
     * اسم الأمر الذي سيتم استدعاؤه في سطر الأوامر.
     *
     * @var string
     */
    protected $signature = 'user:roles {identifier}';

    /**
     * وصف الأمر.
     *
     * @var string
     */
    protected $description = 'يعرض جميع الأدوار الممنوحة لمستخدم معين بواسطة ID أو بريد إلكتروني.';

    /**
     * تنفيذ الأمر.
     */
    public function handle()
    {
        $identifier = $this->argument('identifier');

        $this->info("جاري البحث عن المستخدم: {$identifier}...");

        // محاولة البحث بالـ ID
        if (is_numeric($identifier)) {
            $user = User::find($identifier);
        }
        // محاولة البحث بالبريد الإلكتروني
        else {
            $user = User::where('email', $identifier)->first();
        }

        if (!$user) {
            $this->error("خطأ: لم يتم العثور على مستخدم بالمعرف: {$identifier}");
            return 1;
        }

        $this->info("تم العثور على المستخدم: " . $user->name . " (ID: " . $user->id . ")");

        // استخدام وظيفة Spatie لجلب الأدوار
        $roles = $user->getRoleNames();

        if ($roles->isEmpty()) {
            $this->warn('هذا المستخدم لا يمتلك أي أدوار حاليًا.');
            return 0;
        }

        $this->comment('الأدوار الحالية للمستخدم:');
        $this->table(
            ['الدور'],
            $roles->map(fn($role) => [$role])->toArray()
        );

        return 0;
    }
}