<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Facades\File; // لإدارة الملفات

class PageController extends Controller
{
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_page', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_page', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_page', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_page', ['only' => ['destroy']]);
    }
    // قائمة الصفحات الرئيسية الثابتة التي يجب أن تظهر في لوحة التحكم
    private $fixedPages = [
        'about-us' => 'من نحن (الرؤية، الرسالة، الأهداف)',
        'contact-us' => 'صفحة اتصل بنا',
        'privacy-policy' => 'سياسة الخصوصية',
        'terms-conditions' => 'الشروط والأحكام',
        // يمكن إضافة المزيد لاحقاً
    ];
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // نمرر القائمة الثابتة إلى العرض لاستخدامها كروابط سريعة
        $fixedPages = $this->fixedPages;

        // 1. نبدأ باستعلام الصفحات الأب (Parent Pages)
        // ونحمل معها الأبناء فوراً (Eager Loading)
        $query = Page::whereNull('parent_id')->with('children');

        // 2. 💡 منطق البحث: إذا كان هناك معيار بحث (q)
        if ($request->has('q') && $request->q != '') {
            $searchTerm = $request->q;

            // ✅ إصلاح الأخطاء: تجميع شروط البحث واستخدام الأعمدة الصحيحة
            $query->where(function ($q) use ($searchTerm) {
                // البحث في عناوين ومحتويات كلتا اللغتين
                $q->where('title_ar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title_en', 'like', '%' . $searchTerm . '%')
                    ->orWhere('body_ar', 'like', '%' . $searchTerm . '%')
                    ->orWhere('body_en', 'like', '%' . $searchTerm . '%')
                    ->orWhere('slug', 'like', '%' . $searchTerm . '%');
            })
                ->orWhereHas('children', function ($q) use ($searchTerm) {
                    $q->where('title_ar', 'like', '%' . $searchTerm . '%')
                        ->orWhere('title_en', 'like', '%' . $searchTerm . '%')
                        ->orWhere('body_ar', 'like', '%' . $searchTerm . '%')
                        ->orWhere('body_en', 'like', '%' . $searchTerm . '%')
                        ->orWhere('slug', 'like', '%' . $searchTerm . '%');
                });

            // ملاحظة هامة: هذا الاستعلام سيظهر الأب الذي يطابق معيار البحث.
            // لكي يتم تصفية الآباء أيضاً بناءً على أبنائهم، يجب استخدام WhereHas، 
            // لكن لتبسيط الاستخدام نعتمد حالياً على تصفية الآباء مباشرةً.
        }

        // 3. تنفيذ الاستعلام وجلب الآباء مع الأبناء بعد تطبيق البحث (إذا وُجد).
        // نستخدم orderBy('order') للترتيب الهرمي.
        $parentPages = $query->orderBy('order')->get();

        // 4. نمرر المتغيرات المطلوبة للعرض فقط.
        return view('admin.pages.index', compact('parentPages', 'fixedPages'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // نحتاج لجلب الصفحات الرئيسية لاستخدامها كآباء
        $parentPages = Page::whereNull('parent_id')->get();
        return view('admin.pages.create', compact('parentPages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. قواعد التحقق (Validation)
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'body_ar' => 'nullable|string',
            'body_en' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id', // يجب أن يكون الأب موجوداً
            'order' => 'nullable|integer',
            'status' => 'nullable',

            // الـ Slug مطلوب للصفحات الجديدة ويجب أن يكون فريدًا
            'slug' => 'required|string|unique:pages,slug|max:255',

            // الـ SEO
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string|max:500',
            // (يمكن إضافة حقول SEO الأخرى هنا)

            // الصورة البارزة (اختيارية)
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072', // 3MB
        ]);
        // 2. معالجة ورفع الصورة البارزة (إن وجدت)
        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $fileName = time() . '-' . $validated['slug'] . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('page_banners');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $file->move($destinationPath, $fileName);
            $validated['banner_image_path'] = $fileName;
        }

        // 3. تعيين الحالة والحفظ
        $validated['status'] = $request->has('status');

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم إنشاء الصفحة بنجاح.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        // جلب الصفحات الرئيسية لاستخدامها كآباء (إذا أردنا تغيير الأب)
        $parentPages = Page::whereNull('parent_id')->get();

        // تمرير بيانات الصفحة الحالية وقائمة الآباء إلى العرض
        return view('admin.pages.edit', compact('page', 'parentPages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        // 1. قواعد التحقق (Validation)
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'body_ar' => 'nullable|string',
            'body_en' => 'nullable|string',
            'parent_id' => 'nullable|exists:pages,id',
            'order' => 'nullable|integer',
            'status' => 'nullable',

            // الـ Slug يجب أن يكون فريدًا باستثناء الصفحة التي نعدلها حالياً
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,

            // الـ SEO
            'meta_title_ar' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string|max:500',
            // (يمكن إضافة حقول SEO الأخرى هنا)

            // الصورة البارزة (اختيارية)
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'delete_banner_image' => 'nullable|boolean', // لمعالجة خيار الحذف الصريح
        ]);

        $data = $validated;

        // 2. معالجة تحديث/رفع الصورة البارزة
        if ($request->hasFile('banner_image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($page->banner_image_path && File::exists(public_path('page_banners/' . $page->banner_image_path))) {
                File::delete(public_path('page_banners/' . $page->banner_image_path));
            }

            // رفع الصورة الجديدة
            $file = $request->file('banner_image');
            $fileName = time() . '-' . $validated['slug'] . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('page_banners'), $fileName);
            $data['banner_image_path'] = $fileName;
        }

        // 3. معالجة خيار الحذف الصريح للصورة (إذا ضغط المستخدم على زر "حذف الصورة")
        if (isset($validated['delete_banner_image']) && $page->banner_image_path) {
            if (File::exists(public_path('page_banners/' . $page->banner_image_path))) {
                File::delete(public_path('page_banners/' . $page->banner_image_path));
            }
            $data['banner_image_path'] = null; // تفريغ المسار في قاعدة البيانات
        }

        // 4. تعيين الحالة
        $data['status'] = $request->has('status') ? 1 : 0;

        // 5. تحديث السجل
        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم تحديث الصفحة بنجاح: ' . $page->title_ar);
    }

    public function toggleStatus(Page $page) // 👈 نستخدم News $news لربط النموذج
    {
        try {
            // عكس قيمة status الحالية
            $page->update([
                'status' => !$page->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة الصفحة بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة الصفحة.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        // منع الحذف إذا كانت الصفحة هي أب لصفحات أخرى
        if ($page->children()->count() > 0) {
            return redirect()->route('admin.pages.index')
                ->with('error', 'لا يمكن حذف الصفحة: لديها صفحات فرعية مرتبطة بها.');
        }

        // حذف الصورة المرتبطة
        if ($page->banner_image_path && File::exists(public_path('page_banners/' . $page->banner_image_path))) {
            File::delete(public_path('page_banners/' . $page->banner_image_path));
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'تم حذف الصفحة بنجاح.');
    }
}
