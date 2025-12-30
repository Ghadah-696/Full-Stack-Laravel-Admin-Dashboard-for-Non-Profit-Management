<?php

namespace App\Http\Controllers\Admin;
use App\Models\Document;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_document', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_document', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_document', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_document', ['only' => ['destroy']]);
    }
    public function index()
    {
        // جلب جميع الوثائق وترتيبها حسب السنة الأحدث
        $documents = Document::orderBy('year', 'desc')->latest()->paginate(15);

        return view('admin.documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.documents.create');

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
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'type' => 'required|string|max:100', // (مالي، حوكمة، استراتيجي، ...)
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1), // سنة منطقية

            // 💡 التحقق من الملف: يجب أن يكون ملفاً، من نوع PDF أو DOC/DOCX، والحد الأقصى للحجم (مثال: 5MB)
            'document_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5120 KB = 5MB

            'status' => 'nullable',
        ]);
        // 2. معالجة ورفع الملف
        if ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileName = 'report-' . $validated['year'] . '-' . time() . '.' . $file->getClientOriginalExtension();

            // 💡 التحقق والإنشاء التلقائي للمجلد
            $destinationPath = public_path('documents');
            if (!File::isDirectory($destinationPath)) {
                // إنشاء المجلد إذا لم يكن موجوداً
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            // تخزين الملف
            $file->move($destinationPath, $fileName);

            // حفظ مسار الملف في قاعدة البيانات
            $validated['file_path'] = $fileName;
        }
        // 3. تعيين الحالة والحفظ
        $validated['status'] = $request->has('status');

        Document::create($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'تم رفع وتخزين الوثيقة بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.documents.show');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        return view('admin.documents.edit', compact('document'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {


        // 💡 استخدام قاعدة 'sometimes' لمرونة التعديل
        $validated = $request->validate([
            'title_ar' => 'sometimes|required|string|max:255',
            'title_en' => 'sometimes|required|string|max:255',

            // الوصف ليس مطلوباً ويمكن أن يكون فارغاً
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',

            'type' => 'sometimes|required|string|max:100',
            'year' => 'sometimes|required|integer|min:2000|max:' . (date('Y') + 1),

            'document_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'status' => 'nullable',
        ]);

        // 2. معالجة الملف الجديد (إذا تم رفعه)
        if ($request->hasFile('document_file')) {

            // أ. حذف الملف القديم من الخادم
            $oldFilePath = public_path('documents/' . $document->file_path);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }

            // ب. رفع الملف الجديد
            $file = $request->file('document_file');
            $fileName = 'report-' . $validated['year'] . '-' . time() . '.' . $file->getClientOriginalExtension();

            // ضمان إنشاء المجلد إذا لم يكن موجوداً
            $destinationPath = public_path('documents');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $file->move($destinationPath, $fileName);

            // ج. تحديث مسار الملف في المصفوفة
            $validated['file_path'] = $fileName;

        } else {
            // إذا لم يتم رفع ملف جديد، نحافظ على المسار القديم
            $validated['file_path'] = $document->file_path;
        }

        // 3. تعيين الحالة والحفظ
        $validated['status'] = $request->has('status');

        $document->update($validated);

        return redirect()->route('admin.documents.index')
            ->with('success', 'تم تحديث الوثيقة بنجاح.');
    }

    public function toggleStatus(Document $document)
    {
        try {
            // عكس قيمة status الحالية
            $document->update([
                'status' => !$document->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة الوثيقة بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة الوثيقة.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {

        // 1. حذف الملف المرفوع من الخادم
        $filePath = public_path('documents/' . $document->file_path);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // 2. حذف السجل من قاعدة البيانات
        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'تم حذف الوثيقة والملف المرفوع بنجاح.');
    }
}
