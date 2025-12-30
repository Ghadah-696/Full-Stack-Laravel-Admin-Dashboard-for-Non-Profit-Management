<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Str; // تصحيح: يجب أن يكون هكذا

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // 💡 دالة البناء لحماية كل الدوال
    public function __construct()
    {
        // حماية دوال العرض والـ Index
        $this->middleware('permission:view_news', ['only' => ['index', 'show']]);

        // حماية دالتي الإنشاء والإضافة
        $this->middleware('permission:create_news', ['only' => ['create', 'store']]);

        // حماية دالتي التعديل والتحديث
        $this->middleware('permission:edit_news', ['only' => ['edit', 'update']]);

        // حماية دالة الحذف
        $this->middleware('permission:delete_news', ['only' => ['destroy']]);
    }
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // جلب كل التصنيفات لاستخدامها في القائمة المنسدلة
        $categories = Category::all();

        return view('admin.news.create', compact('categories'));
        // return view('admin.news.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // if ($request->hasFile('image')) {
        //     dd($request->file('image')->getMimeType());
        // }
        // الخطوة 1: التحقق من صحة البيانات المرسلة من النموذج
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'summary_ar' => 'required|string',
            'summary_en' => 'required|string',
            'body_ar' => 'required|string',
            'body_en' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id', // يجب أن يكون موجوداً في جدول categories
            // 'status' => 'boolean', // يجب أن يكون قيمة منطقية (0 أو 1)
        ]);

        // تحديد قيمة status الافتراضية إذا لم يتم إرسالها (لتصبح مسودة)
        // $data['status'] = $request->has('status'); // يتحقق إذا كان مربع الاختيار محدداً
        try {
            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('images'), $imageName);
                $validated['image'] = $imageName;
            }

            $validated['slug'] = Str::slug($validated['title_en']);

            News::create($validated);

            return redirect()->route('admin.news.index')->with('success', 'تم إضافة الخبر بنجاح!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل إضافة الخبر: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $news = News::findOrFail($id);
        $categories = Category::all();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        // dd($request->hasFile('image'));
        // التحقق من الحقول النصية فقط، وجعلها اختيارية
        $validated = $request->validate([
            'title_ar' => 'sometimes|required|string|max:255',
            'title_en' => 'sometimes|required|string|max:255',
            'summary_ar' => 'sometimes|required',
            'summary_en' => 'sometimes|required',
            'body_ar' => 'sometimes|required',
            'body_en' => 'sometimes|required',
        ]);

        // تحديث الحقول النصية
        $news->update($validated);

        // تحديث الـ Slug فقط إذا تم إرسال حقل العنوان الإنجليزي
        if ($request->has('title_en')) {
            $news->update(['slug' => Str::slug($request->input('title_en'))]);
        }

        // معالجة الصورة فقط إذا تم رفع صورة جديدة
        if ($request->hasFile('image')) {
            // التحقق من صلاحية الصورة الجديدة
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            ]);

            // حذف الصورة القديمة إذا كانت موجودة
            if ($news->image) {
                unlink(public_path('images/' . $news->image));
            }

            // حفظ الصورة الجديدة
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);

            // تحديث حقل الصورة في قاعدة البيانات
            $news->update(['image' => $imageName]);
        }

        return redirect()->route('admin.news.index')->with('success', 'تم تحديث الخبر بنجاح!');
    }

    public function toggleStatus(News $news) // 👈 نستخدم News $news لربط النموذج
    {
        try {
            // عكس قيمة status الحالية
            $news->update([
                'status' => !$news->status
            ]);

            return redirect()->back()->with('success', 'تم تحديث حالة الخبر بنجاح.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'فشل في تحديث حالة الخبر.');
        }
    }
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);
        if ($news->image) {
            unlink(public_path('images/') . $news->image);
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'تم حذف الخبر بنجاح!');
    }
}