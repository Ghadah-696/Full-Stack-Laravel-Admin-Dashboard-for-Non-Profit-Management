<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class PasswordChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    // المستخدم الذي تم تغيير كلمة مروره
    public $subjectUser;
    // المشرف الذي قام بالتغيير
    public $adminUser;

    /**
     * إنشاء مثيل إشعار جديد.
     */
    public function __construct(User $subjectUser, User $adminUser)
    {
        $this->subjectUser = $subjectUser;
        $this->adminUser = $adminUser;
    }

    /**
     * تحديد قناة الإرسال (مثلاً، قاعدة البيانات).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * تحويل الإشعار إلى مصفوفة قابلة للحفظ في قاعدة البيانات.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'password_change',
            'subject_user_id' => $this->subjectUser->id,
            'subject_user_name' => $this->subjectUser->name,
            'admin_user_name' => $this->adminUser->name,
            'message' => 'قام المشرف ' . $this->adminUser->name . ' بتغيير كلمة مرور المستخدم ' . $this->subjectUser->name . '.',
        ];
    }
}