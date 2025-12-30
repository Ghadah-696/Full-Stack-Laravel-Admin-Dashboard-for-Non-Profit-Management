<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_slider', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_slider', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_slider', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_slider', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $sliders = Slider::latest()->paginate(10);
        return view('admin.sliders.index', compact('sliders'));
        // ✅ المصحح: ترتيب تصاعدي حسب حقل 'order'
        // // السلايدرات ذات قيمة order=1 ستظهر قبل order=5
        // $sliders = Slider::orderBy('order', 'asc')->latest()->get()->paginate(10);

        // return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Show the form for creating a create resource.
     */
    public function create()
    {
        return view('admin.sliders.create');
        // return "TEST OK";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string', // 💡 الاسم الجديد
            'description_en' => 'nullable|string', // 💡 الاسم الجديد
            'link' => 'nullable|url|max:255',
            'order' => 'nullable|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // تعيين الحقول المفقودة (إذا لم تستخدم default(true) في الهجرة، يمكنك استخدام هذا)
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }
        try {
            // 1. معالجة الصورة (بدون else)
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('images'), $imageName);
                $validated['image'] = $imageName;
            }

            // 2. التخزين الآمن
            Slider::create($validated);

            return redirect()->route('admin.sliders.index')->with('success', 'تم إضافة السلايد بنجاح!');

        } catch (\Exception $e) {
            // في حال فشل، يتم إظهار الخطأ
            return redirect()->back()->with('error', 'فشل إضافة السلايد: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sliders = Slider::findOrFail($id);
        return view('admin.sliders.show', compact('sliders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));

    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'title_ar' => 'sometimes|nullable|string|max:255',
            'title_en' => 'sometimes|nullable|string|max:255',
            'description_ar' => 'sometimes|nullable|string', // 💡 الاسم الجديد
            'description_en' => 'sometimes|nullable|string', // 💡 الاسم الجديد
            'link' => 'sometimes|nullable|url|max:255',
            'order' => 'sometimes|nullable|integer|min:0',
            'status' => 'nullable', // للحالة
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('image')) {
            // ⚠️ يجب هنا حذف الصورة القديمة قبل تخزين الجديدة
            // ... كود حذف القديمة ...

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        }
        // معالجة الـ status
        $validated['status'] = $request->has('status');

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'تم تحديث السلايد بنجاح');

    }


    public function toggleStatus(Slider $slider)
    {
        try {
            // عكس قيمة status الحالية
            $slider->update([
                'status' => !$slider->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة السلايد بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة السلايد.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sliders = Slider::findOrFail($id);
        if ($sliders->image) {
            unlink(public_path('images/') . $sliders->image);
        }
        $sliders->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'تم حذف السلايد بنجاح!');


    }
}
