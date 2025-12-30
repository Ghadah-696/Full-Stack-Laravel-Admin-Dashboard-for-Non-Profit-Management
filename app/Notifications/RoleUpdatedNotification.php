<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\User; // استيراد صنف المستخدم

class RoleUpdatedNotification extends Notification
{
    use Queueable;

    protected $targetUser; // المستخدم الذي تم تعديل صلاحياته
    protected $admin;      // المدير الذي قام بالتعديل

    /**
     * إنشاء مثيل إشعار جديد.
     */
    public function __construct(User $targetUser, User $admin)
    {
        $this->targetUser = $targetUser;
        $this->admin = $admin;
    }

    public function via($notifiable)
    {
        return ['database']; // إرسال الإشعار إلى قاعدة البيانات
    }

    /**
     * الحصول على تمثيل الإشعار لقناة قاعدة البيانات.
     */
    public function toDatabase($notifiable)
    {
        // نحصل على اسم الدور الجديد (بافتراض أن المستخدم لديه دور واحد)
        $newRole = $this->targetUser->roles->pluck('name')->first() ?? 'بلا دور';

        return [
            'message' => 'تم تعديل دور المستخدم ' . $this->targetUser->name . ' إلى: ' . $newRole . ' بواسطة ' . $this->admin->name,

            // الرابط المباشر لصفحة تعديل المستخدم للتأكد
            'link' => route('admin.users.edit', ['user' => $this->targetUser->id]),

            'action' => 'user_role_updated',
            'user_id' => $this->targetUser->id,
        ];
    }
}