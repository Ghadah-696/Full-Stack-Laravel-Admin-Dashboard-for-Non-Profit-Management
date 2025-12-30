<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // تصحيح: يجب أن يكون هكذا

class ProjectController extends Controller
{
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_project', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_project', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_project', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_project', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.projects.create', compact('categories'));
        // return view('admin.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all(), $request->hasFile('image'));
        // الخطوة 1: التحقق من صحة البيانات
        $validated = $request->validate(
            [
                'title_ar' => 'required|string|max:255',
                'title_en' => 'required|string|max:255',
                'summary_ar' => 'required|string',
                'summary_en' => 'required|string',
                'body_ar' => 'required|string',
                'body_en' => 'required|string',
                // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // تغيير 'required' إلى 'nullable'
                'category_id' => 'required|exists:categories,id',
                // 'status' => 'nullable|boolean', // ⚠️ تعديل status هنا إلى nullable|boolean
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]
        );

        $validated['slug'] = Str::slug($validated['title_en']);
        $validated['status'] = $request->has('status') ? 1 : 0; // تعيين حالة المشروع (مفعل/غير مفعل)

        try {
            // 1. معالجة الصورة (بدون else)
            // if ($request->hasFile('image')) {
            //     $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            //     $request->image->move(public_path('images'), $imageName);
            //     $validated['image'] = $imageName;
            // }
            if ($request->hasFile('image')) {
                // تحقق من صلاحيات الكتابة في المسار public/images
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('images'), $imageName);
                $validated['image'] = $imageName;
            } else {
                $validated['image'] = null;
            }
            // $validated['status'] = $request->has('status');
            // if (!isset($validated['order'])) {
            //     $validated['order'] = 0;
            // }

            // 2. التخزين الآمن
            Project::create($validated);

            return redirect()->route('admin.projects.index')->with('success', 'تم إضافة المشروع بنجاح!');

        } catch (\Exception $e) {

            return redirect()->back()->withInput()->withErrors([
                'error' => 'فشل الحفظ (للتصحيح): ' . $e->getMessage()
            ]);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $projects = Project::findOrFail($id);
        return view('admin.projects.show', compact('projects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $categories = Category::all(); // أو استخدم where('status', true) لجلب الفعالة فقط

        return view('admin.projects.edit', compact('project', 'categories'));
        // $projects = Project::findOrFail($id);
        // return view('admin.projects.edit', compact('projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        // 1. تحديد الحقول القابلة للتعديل وقواعدها
        $validated = $request->validate([

            // 🔹 الحقول المطلوبة للتعديل (إذا تم إرسالها): نستخدم 'sometimes'
            'title_ar' => 'sometimes|required|string|max:255',
            'title_en' => 'sometimes|required|string|max:255',
            'summary_ar' => 'sometimes|required|string',
            'summary_en' => 'sometimes|required|string',
            'body_ar' => 'sometimes|required|string',
            'body_en' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            // 'status' => 'nullable', // بما أن النموذج لا يحتوي عليه، يمكن حذفه
        ]);
        // // 4. تطبيق منطق الحظر على التواريخ (إذا كان مطلوباً)
        // if ($projects->status === 'completed') {
        //     unset($validated['start_date'], $validated['end_date']);
        // }

        // 2. معالجة الحقول المشتقة (مثل status و slug)
        // نستخدم has() لأن الـ checkbox يرسل قيمة فقط عند التحديد
        $validated['status'] = $request->has('status');

        // 💡 لا تولد الـ SLUG إلا إذا تم تغيير عنوان المشروع باللغة الإنجليزية
        if ($request->has('title_en')) {
            $validated['slug'] = Str::slug($validated['title_en']);
        }

        // 3. معالجة الصورة (إذا تم رفع صورة جديدة)
        if ($request->hasFile('image')) {
            // ⚠️ يجب هنا حذف الصورة القديمة قبل تخزين الجديدة
            // ... كود حذف القديمة ...

            $imageName = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        }

        // 4. التحديث الآمن
        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'تم تحديث المشروع بنجاح.');
    }

    public function toggleStatus(Project $project)
    {
        try {
            // عكس قيمة status الحالية
            $project->update([
                'status' => !$project->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة المشروع بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة المشروع.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $projects = Project::findOrFail($id);
        if ($projects->image) {
            unlink(public_path('images/') . $projects->image);
        }
        $projects->delete();
        return redirect()->route('admin.projects.index')->with('success', 'تم حذف المشروع بنجاح!');

    }
}
