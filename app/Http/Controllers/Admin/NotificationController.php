<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * عرض جميع إشعارات المستخدم مع التصفح. (هذه هي الدالة المطلوبة)
     */
    public function index()
    {
        // 1. جلب جميع إشعارات المستخدم (مقروءة وغير مقروءة) مع التصفح
        $notifications = auth()->user()->notifications()->paginate(20);

        // 2. تعليم جميع الإشعارات غير المقروءة حالياً كمقروءة
        auth()->user()->unreadNotifications->markAsRead();

        // 3. تمرير الإشعارات إلى صفحة العرض
        // تأكدي من أن المسار 'admin.notifications.index' موجود لديكِ
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * تعليم إشعار واحد كمقروء وإعادة توجيه المستخدم إلى الرابط الأصلي.
     */
    public function markAsReadAndRedirect(DatabaseNotification $notification)
    {
        // 1. التأكد أن المستخدم الحالي هو مالك الإشعار لأغراض أمنية
        if (auth()->id() !== $notification->notifiable_id) {
            return redirect()->route('admin.dashboard')->with('error', 'لا تملك صلاحية الوصول لهذا الإشعار.');
        }

        // 2. تعليم الإشعار كمقروء
        $notification->markAsRead();

        // 3. إعادة التوجيه إلى الرابط المخزن في بيانات الإشعار (data)
        if (isset($notification->data['link'])) {
            return redirect()->to($notification->data['link']);
        }

        // في حال عدم وجود رابط
        return redirect()->route('admin.notifications.index');
    }
}