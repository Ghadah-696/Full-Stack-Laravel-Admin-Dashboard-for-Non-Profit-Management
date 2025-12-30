<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str; // تصحيح: يجب أن يكون هكذا

class CategoryController extends Controller
{
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_category', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_category', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_category', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_category', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // يتم جلب جميع التصنيفات
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'type' => 'required|string|max:100',

        ]);

        try {
            $validated['slug'] = Str::slug($validated['name_en']);

            Category::create($validated);

            return redirect()->route('admin.categories.index')->with('success', 'تم إضافة التصنيف بنجاح!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل إضافة التصنيف: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // $categories = Category::findOrFail($id);
        // return view('admin.categories.show', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::findOrFail($id);
        return view('admin.categories.edit', compact('categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'type' => 'required|string|max:100',
        ]);

        // 1. إعادة تسمية المتغير لاستخدام صيغة الجمع في العمليات الداخلية
        $categories = $category;
        $categories->update($validated);

        // تحديث الـ Slug فقط إذا تم إرسال حقل العنوان الإنجليزي
        if ($request->has('name_en')) {
            $categories->update(['slug' => Str::slug($request->input('name_en'))]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'تم تحديث التصنيف بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categories = Category::findOrFail($id);
        $categories->delete();
        return redirect()->route('admin.categories.index')->with('success', 'تم حذف التصنيف بنجاح!');
    }
}
