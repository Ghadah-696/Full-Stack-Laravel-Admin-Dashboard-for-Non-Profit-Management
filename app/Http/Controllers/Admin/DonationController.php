<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    // دالة البناء لحماية المتحكم بالصلاحية
    public function __construct()
    {
        $this->middleware('permission:view_donation', ['only' => ['index', 'show']]);
    }

    /**
     * عرض قائمة بسجلات التبرعات.
     */
    public function index()
    {
        // جلب سجلات التبرعات مع ترقيم الصفحات
        $donations = Donation::latest()->paginate(20);

        return view('admin.donations.index', compact('donations'));
    }

    /**
     * عرض تفاصيل سجل تبرع واحد.
     */

    public function show(Donation $donation)
    {
        // 💡 التعديل هنا: جلب العلاقات مسبقًا لتحسين الأداء
        $donation->load(['creator', 'updater', 'user']);

        return view('admin.donations.show', compact('donation'));
    }

    // (يمكنك إضافة دالة للحذف - destroy - هنا إذا كنتِ تريدين إمكانية حذف سجل تبرع)
    // (يمكنك إضافة دالة للتصدير - export - هنا إذا كنتِ تريدين تصدير البيانات إلى Excel/CSV)

    // الدوال الأخرى (create, store, edit, update, destroy) لن تُضاف هنا لأننا استخدمنا except في المسارات
}
// 💡 دالة البناء لحماية كل الدوال
// public function __construct()
// {
//     // نحتاج لصلاحية العرض (View) فقط لسجلات التبرعات
//     $this->middleware('permission:view_donation', ['only' => ['index', 'show']]);

//     // قد تحتاجين لصلاحية تصدير البيانات لاحقاً
//     // $this->middleware('permission:export_donation', ['only' => ['export']]);
// }
// /**
//  * Display a listing of the resource.
//  */

// public function show(Donation $donation)
// {
//     //
// }

// /**
//  * Show the form for editing the specified resource.
//  */


// /**
//  * Update the specified resource in storage.
//  */


// /**
//  * Remove the specified resource from storage.
//  */
// public function destroy(Donation $donation)
// {
//     //
// }

