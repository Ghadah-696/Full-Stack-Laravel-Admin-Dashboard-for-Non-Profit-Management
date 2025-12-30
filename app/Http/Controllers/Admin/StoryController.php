<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Story;

class StoryController extends Controller
{
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_story', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_story', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_story', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_story', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // عرض القصص مرتبة حسب الترتيب (order) ثم الأحدث
        $stories = Story::orderBy('order', 'asc')->latest()->paginate(10);

        return view('admin.stories.index', compact('stories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stories.create');
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
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content_ar' => 'required|string',
            'content_en' => 'required|string',
            'order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. معالجة الصورة (في مجلد stories)
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            // 💡 نستخدم مجلد فرعي جديد للتنظيم: 'images/stories'
            $request->image->move(public_path('images/stories'), $imageName);
            $validated['image'] = $imageName;
        }

        // 3. تعيين الحالة والترتيب الافتراضيين
        $validated['status'] = $request->has('status');
        if (!isset($validated['order'])) {
            $validated['order'] = 0;
        }

        // 4. الحفظ في قاعدة البيانات
        Story::create($validated);

        return redirect()->route('admin.stories.index')->with('success', 'تم إضافة القصة بنجاح!');
        ;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.stories.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Story $story)
    {
        return view('admin.stories.edit', compact('story'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story)
    {
        $validated = $request->validate([
            'name_ar' => 'sometimes|nullable|string|max:255',
            'name_en' => 'sometimes|nullable|string|max:255',
            'title_ar' => 'sometimes|nullable|string|max:255',
            'title_en' => 'sometimes|nullable|string|max:255',
            'content_ar' => 'sometimes|nullable|string', // 💡 الاسم الجديد
            'content_en' => 'sometimes|nullable|string', // 💡 الاسم الجديد
            'order' => 'sometimes|nullable|integer|min:0',
            'status' => 'nullable', // للحالة
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('image')) {
            // ⚠️ يجب هنا حذف الصورة القديمة قبل تخزين الجديدة
            // ... كود حذف القديمة ...

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images/stories/'), $imageName);
            $validated['image'] = $imageName;
        }
        // معالجة الـ status
        $validated['status'] = $request->has('status');

        $story->update($validated);

        return redirect()->route('admin.stories.index')->with('success', 'تم تحديث القصة بنجاح');
    }

    public function toggleStatus(Story $story)
    {
        try {
            // عكس قيمة status الحالية
            $story->update([
                'status' => !$story->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة القصة بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة القصة.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stories = Story::findOrFail($id);
        if ($stories->image) {
            unlink(public_path('images/stories/') . $stories->image);
        }
        $stories->delete();
        return redirect()->route('admin.stories.index')->with('success', 'تم حذف القصة بنجاح بنجاح!');

    }
}
