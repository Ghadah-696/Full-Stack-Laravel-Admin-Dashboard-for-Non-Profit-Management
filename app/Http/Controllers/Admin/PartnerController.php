<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Partner;
use Illuminate\Support\Facades\File;

class PartnerController extends Controller
{
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_partner', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_partner', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_partner', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_partner', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // جلب جميع الشركاء وترتيبهم حسب الأحدث
        $partners = Partner::latest()->paginate(15);

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. قواعد التحقق (Validation)
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255', // يجب أن يكون رابط موقع صحيح
            'type' => 'nullable|string|max:100',

            // 💡 التحقق من الشعار: يجب أن يكون صورة (Image)، من أنواع شائعة، وبحد أقصى (مثال: 2MB)
            'logo_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048', // 2048 KB = 2MB

            'status' => 'nullable',
        ]);

        // 2. معالجة ورفع الشعار
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');

            // إنشاء اسم فريد للملف
            $fileName = 'partner-' . time() . '.' . $file->getClientOriginalExtension();

            // تحديد المسار وضمان إنشائه
            $destinationPath = public_path('partners');
            if (!File::isDirectory($destinationPath)) {
                // إنشاء المجلد public/partners إذا لم يكن موجوداً
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            // تخزين الملف في مجلد 'partners' داخل public
            $file->move($destinationPath, $fileName);

            // حفظ مسار الملف في قاعدة البيانات
            $validated['logo_path'] = $fileName;
        }

        // 3. تعيين الحالة والحفظ
        $validated['status'] = $request->has('status');

        Partner::create($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'تم تسجيل الشريك ورفع الشعار بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.partners.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        // 1. قواعد التحقق (Validation) - استخدام 'sometimes' للمرونة
        $validated = $request->validate([
            'name_ar' => 'sometimes|required|string|max:255',
            'name_en' => 'sometimes|required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'type' => 'nullable|string|max:100',

            // الشعار اختياري في التعديل
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',

            'status' => 'nullable',
        ]);

        // 2. معالجة الشعار الجديد (إذا تم رفعه)
        if ($request->hasFile('logo_file')) {

            // أ. حذف الشعار القديم من الخادم
            $oldLogoPath = public_path('partners/' . $partner->logo_path);
            if (File::exists($oldLogoPath)) {
                File::delete($oldLogoPath);
            }

            // ب. رفع الشعار الجديد
            $file = $request->file('logo_file');
            $fileName = 'partner-' . time() . '.' . $file->getClientOriginalExtension();

            // ضمان إنشاء المجلد إذا لم يكن موجوداً
            $destinationPath = public_path('partners');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $file->move($destinationPath, $fileName);

            // ج. تحديث مسار الملف في المصفوفة
            $validated['logo_path'] = $fileName;

        } else {
            // إذا لم يتم رفع ملف جديد، نحافظ على المسار القديم في المصفوفة
            $validated['logo_path'] = $partner->logo_path;
        }

        // 3. تعيين الحالة والحفظ
        $validated['status'] = $request->has('status');

        // تحديث السجل
        $partner->update($validated);

        return redirect()->route('admin.partners.index')
            ->with('success', 'تم تحديث بيانات الشريك بنجاح.');
    }

    public function toggleStatus(Partner $partner) // 👈 نستخدم News $news لربط النموذج
    {
        try {
            // عكس قيمة status الحالية
            $partner->update([
                'status' => !$partner->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة الشريك بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة الشريك.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        // 1. حذف الملف المرفوع (الشعار) من الخادم
        $logoPath = public_path('partners/' . $partner->logo_path);

        if (File::exists($logoPath)) {
            File::delete($logoPath);
        }

        // 2. حذف السجل من قاعدة البيانات
        $partner->delete();

        return redirect()->route('admin.partners.index')
            ->with('success', 'تم حذف الشريك والشعار المرفوع بنجاح.');
    }
}
