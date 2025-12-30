<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\RunBackupJob;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        // يتضمن edit و update
        $this->middleware('permission:edit_setting', ['only' => ['edit', 'update']]);
    }
    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function runBackup(Request $request)
    {
        // يجب أن تكون الطلبات POST محمية من CSRF، لا تقم بتعطيل الحماية في Middleware!

        try {
            // تشغيل أمر النسخ الاحتياطي
            Artisan::call('backup:run', [
                // يمكنك إضافة خيارات هنا، مثل --only-db أو --only-files
            ]);

            // جلب مخرجات الأمر (اختياري)
            $output = Artisan::output();

            // تسجيل النجاح
            Log::info('Manual backup initiated successfully.', ['output' => $output]);

            // إرجاع استجابة JSON للواجهة الأمامية
            return response()->json([
                'success' => true,
                'message' => 'تم تشغيل النسخ الاحتياطي بنجاح. ' . (trim($output) ? 'مخرجات الأمر: ' . trim($output) : 'راجع سجلات النظام للتفاصيل.'),
                'output' => $output,
            ], 200);

        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error('Manual backup failed.', ['error' => $e->getMessage()]);

            // إرجاع استجابة JSON للواجهة الأمامية برمز خطأ 500
            return response()->json([
                'success' => false,
                'message' => 'فشل تشغيل النسخ الاحتياطي: ' . $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // نجلب السجل الأول (أو ننشئ سجلًا فارغًا إذا لم يكن موجودًا)
        $setting = Setting::firstOrCreate([]);
        // جلب الملفات من مجلد backup
        $files = Storage::disk('local')->files('backup');
        rsort($files);

        return view('admin.settings.edit', compact('setting', 'files'));
    }
    // public function runBackup()
    // {
    //     try {
    //         Artisan::call('backup:run');

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'تم إنشاء النسخة الاحتياطية بنجاح',
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // 1. قواعد التحقق (Validation) - تشمل جميع الحقول الجديدة والقديمة
        $validated = $request->validate([
            // التواصل الأساسي
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:50',
            'address_ar' => 'nullable|string|max:255',
            'address_en' => 'nullable|string|max:255',

            // التواصل الاجتماعي (يجب أن تكون روابط URL)
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',

            // الصور
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
            'favicon' => 'nullable|image|mimes:ico,png|max:50',

            // النصوص الإضافية
            'footer_text_ar' => 'nullable|string',
            'footer_text_en' => 'nullable|string',

            // الإعدادات الاحترافية الجديدة
            'google_analytics_id' => 'nullable|string|max:50',
            'google_maps_api_key' => 'nullable|string|max:255',
            'default_meta_desc_ar' => 'nullable|string|max:500',
            'default_meta_desc_en' => 'nullable|string|max:500',

            // الصورة الافتراضية للمشاركة
            'default_og_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',

            // الحقول المنطقية (Boolean)
            'maintenance_mode' => 'nullable|boolean',
            'enable_accessibility_bar' => 'nullable|boolean',
        ]);

        // 2. جلب السجل الحالي أو إنشائه
        $setting = Setting::firstOrCreate([]);
        $data = $validated;

        // 3. إنشاء مسار المجلد (إذا لم يكن موجوداً)
        $brandingPath = public_path('uploads/branding');
        if (!File::isDirectory($brandingPath)) {
            File::makeDirectory($brandingPath, 0777, true, true);
        }

        // 4. معالجة الصور الثلاثة (الشعار، الفافيكون، صورة OG)
        $imagesToProcess = ['logo' => 'logo_path', 'favicon' => 'favicon_path', 'default_og_image' => 'default_og_image_path'];

        foreach ($imagesToProcess as $requestKey => $dbKey) {
            if ($request->hasFile($requestKey)) {
                // حذف القديم
                if ($setting->$dbKey && File::exists($brandingPath . '/' . $setting->$dbKey)) {
                    File::delete($brandingPath . '/' . $setting->$dbKey);
                }
                // رفع الجديد
                $imageName = $requestKey . '-' . time() . '.' . $request->file($requestKey)->extension();
                $request->file($requestKey)->move($brandingPath, $imageName);
                $data[$dbKey] = $imageName;
            }
        }

        // 5. معالجة الحقول المنطقية (Boolean)
        // إذا لم يتم إرسال الحقل (checkbox غير مُحدد)، نعطيه قيمة 0 (false) يدوياً.
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;
        $data['enable_accessibility_bar'] = $request->has('enable_accessibility_bar') ? 1 : 0;

        // 6. تحديث السجل
        $setting->update($data);

        return redirect()->back()->with('success', 'تم تحديث الإعدادات العامة بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */

}
