<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Impact;
use App\Models\Project;

class ImpactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_impact', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_impact', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_impact', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_impact', ['only' => ['destroy']]);
    }
    public function index()
    {
        // 1. استدعاء البيانات:
        // جلب جميع سجلات الأثر وترتيبها حسب نسبة الإنجاز ثم الأحدث، واستخدام الترقيم
        $impacts = Impact::orderBy('progress_percentage', 'desc')
            ->latest()
            ->paginate(10);

        // 2. تمرير المتغير إلى الـ View:
        return view('admin.impacts.index', compact('impacts'));
        // 💡 ملاحظة: يجب أن يكون اسم المتغير داخل compact هو "impacts" 
        // ليتطابق مع ما تستخدمه في صفحة الـ index


        // $impacts = Project::latest()->paginate(10);
        // return view('admin.impacts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // سنحتاج لتمرير قائمة بجميع المشاريع لاختيار المشروع المرتبط
        $projects = Project::pluck('title_ar', 'id');
        return view('admin.impacts.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Impact $impact)
    {
        // 1. قواعد التحقق (Validation)
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|unique:impacts,project_id', // يجب أن يكون الأثر فريداً لكل مشروع
            'required_amount' => 'required|numeric|min:0',
            'raised_amount' => 'required|numeric|min:0|lte:required_amount', // يجب ألا يتجاوز المبلغ المجموع المطلوب
            'goal_ar' => 'required|string|max:255',
            'goal_en' => 'required|string|max:255',
            'reached_ar' => 'required|string|max:255',
            'reached_en' => 'required|string|max:255',
            'status' => 'nullable',
        ]);

        // 2. منطق الحساب التلقائي لنسبة الإنجاز
        $required = (float) $validated['required_amount'];
        $raised = (float) $validated['raised_amount'];

        if ($required > 0) {
            $progress = ($raised / $required) * 100;
            $validated['progress_percentage'] = min(100, round($progress)); // لا يمكن أن تتجاوز 100%
        } else {
            $validated['progress_percentage'] = 0;
        }

        // 3. تعيين حالة التفعيل
        $validated['status'] = $request->has('status');

        // 4. الحفظ في قاعدة البيانات
        Impact::create($validated);

        return redirect()->route('admin.impacts.index')
            ->with('success', 'تم تسجيل مقياس الأثر بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $impacts = Project::findOrFail($id);
        return view('admin.impacts.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Impact $impact)
    {
        $projects = Project::pluck('title_ar', 'id');

        // تأكد من تمرير كل من $impact و $projects
        return view('admin.impacts.edit', compact('impact', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Impact $impact)
    {
        // قواعد التحقق: يجب أن يكون project_id فريداً باستثناء السجل الحالي
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id|unique:impacts,project_id,' . $impact->id,
            'required_amount' => 'required|numeric|min:0',
            'raised_amount' => 'required|numeric|min:0|lte:required_amount',
            'goal_ar' => 'required|string|max:255',
            'goal_en' => 'required|string|max:255',
            'reached_ar' => 'required|string|max:255',
            'reached_en' => 'required|string|max:255',
            // 'status' => 'nullable',
        ]);

        // 💡 منطق الحساب التلقائي لنسبة الإنجاز (مكرر من دالة store)
        $required = (float) $validated['required_amount'];
        $raised = (float) $validated['raised_amount'];

        if ($required > 0) {
            $progress = ($raised / $required) * 100;
            $validated['progress_percentage'] = min(100, round($progress));
        } else {
            $validated['progress_percentage'] = 0;
        }

        $validated['status'] = $request->has('status');

        // حفظ التعديلات
        $impact->update($validated);

        return redirect()->route('admin.impacts.index')
            ->with('success', 'تم تحديث مقياس الأثر بنجاح.');
    }

    public function toggleStatus(Impact $impact)
    {
        try {
            // عكس قيمة status الحالية
            $impact->update([
                'status' => !$impact->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة الأثر بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة الأثر.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $impacts = Impact::findOrFail($id);
        $impacts->delete();

        return redirect()->route('admin.impacts.index')
            ->with('success', 'تم حذف مقياس الأثر بنجاح.');
    }
}
