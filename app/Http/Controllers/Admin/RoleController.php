<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * دالة البناء لتطبيق الحماية عبر الـ Middleware.
     * تحدد الصلاحيات المطلوبة لكل مجموعة من الدوال.
     */
    public function __construct()
    {
        // يتطلب الوصول إلى هذه الدوال الصلاحية المحددة
        $this->middleware('permission:view_roles', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_roles', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_roles', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_roles', ['only' => ['destroy']]);
    }
    public function index()
    {
        // جلب جميع الأدوار مع عدد المستخدمين المرتبطين بكل دور
        $roles = Role::withCount('users')->get();

        // ملاحظة: صفحة admin.roles.index لم يتم تصميمها بعد
        return view('admin.roles.index', compact('roles'));
    }


    /**
     * عرض نموذج تعديل الصلاحيات لدور محدد.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function editPermissions(Role $role)
    {
        // 1. جلب جميع الصلاحيات من جدول 'permissions'
        // (هذا هو السطر الذي كنا نؤجله!)
        $allPermissions = Permission::all()->groupBy('module'); // مثال: تجميعها حسب الوحدة

        // 2. جلب الصلاحيات الحالية المرتبطة بهذا الدور
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        // 3. تمرير البيانات الحقيقية إلى واجهة المستخدم
        return view('admin.roles.permissions', compact('role', 'allPermissions', 'rolePermissions'));
    }


    /**
     * تحديث (مزامنة) صلاحيات الدور المحدد.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePermissions(Request $request, Role $role)
    {
        // if ($role->id === 1) {

        //     abort(403, 'لا يمكن تعديل صلاحيات دور النظام الأساسي (المدير العام).');
        // }
        if ($role->id === 1) {
            // نقوم بإعادة التوجيه إلى الخلف مع رسالة خطأ مُصممة جيداً
            // هذه الرسالة ستظهر في الواجهة الأمامية كرسالة تنبيه
            return redirect()->route('admin.roles.index')->with('error', '
                ⛔️ عملية غير مسموح بها. لا يمكن تعديل صلاحيات دورالمدير العام (Super Admin)
                لضمان استقرار النظام وأمانه.
            ');
        }

        // 1. استخراج الصلاحيات المُرسلة.
        // إذا لم يتم تحديد أي صلاحية، ستكون القيمة مصفوفة فارغة،
        // وهذا يضمن إزالة جميع الصلاحيات من الدور باستخدام syncPermissions.
        $permissions = $request->input('permissions', []);

        // 2. التأكد من أن الصلاحيات هي مصفوفة قبل المزامنة.
        if (!is_array($permissions)) {
            $permissions = [];
        }

        // 3. استخدام دالة syncPermissions لمزامنة الصلاحيات.
        // هذه هي أفضل طريقة: تزيل الصلاحيات التي لم تعد محددة وتضيف الجديدة.
        try {
            $role->syncPermissions($permissions);

            return redirect()->route('admin.roles.index')
                ->with('success', 'تم تحديث صلاحيات الدور "' . $role->name . '" بنجاح.');

        } catch (\Exception $e) {
            // في حالة حدوث خطأ (مثل محاولة ربط صلاحية غير موجودة)، يتم إرجاع رسالة خطأ.
            return back()->with('error', 'حدث خطأ أثناء تحديث الصلاحيات. يرجى التأكد من صحة أسماء الصلاحيات.');
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. إجراء التحقق (يضمن أن الـ IDs صالحة وموجودة)
        $validatedData = $request->validate(
            [
                'name' => ['required', 'string', 'unique:roles,name', 'not_regex:/^[A-Z]/'],
                'permissions' => 'nullable|array',
                'permissions.*' => 'exists:permissions,id',
            ],
            [
                'name.unique' => 'هذا الدور موجود بالفعل، يرجى اختيار اسم مختلف.',
                'name.not_regex' => 'يجب أن يبدأ اسم الدور بحرف صغير (Convention).',
            ]
        );

        // 2. إنشاء الدور
        $role = Role::create(['name' => $validatedData['name']]);

        // 3. ربط الصلاحيات
        if (!empty($validatedData['permissions'])) {

            // 4. الخطوة الحاسمة: تحويل مصفوفة الـ IDs إلى كائنات Permission Models
            // يتم جلب جميع كائنات الصلاحيات التي تطابق الـ IDs المرسلة
            $permissions = Permission::whereIn('id', $validatedData['permissions'])->get();

            // 5. ربط الصلاحيات بالدور (Spatie تقبل كائنات الـ Models)
            $role->givePermissionTo($permissions);
        }

        // إرجاع استجابة
        return redirect()->route('admin.roles.index')->with('success', 'تم إنشاء الدور بنجاح.');
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
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        // جلب أسماء الصلاحيات الممنوحة حالياً لهذا الدور
        $rolePermissions = $role->permissions->pluck('name')->all();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // 1. حاجز الحماية (Guardrail)
        // لا تسمح بتعديل أو حذف الدور الأساسي
        if ($role->name === 'super-admin') {
            return redirect()->route('admin.roles.index')->with('error', '
            لا يمكن تعديل أو تغيير صلاحيات الدور الأساسي للمدير العام (Super Admin).
            ');
        }
        // 1. إجراء التحقق (باستخدام Rule::unique لاستثناء الدور الحالي)
        $validatedData = $request->validate([
            'name' => ['required', 'string', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.unique' => 'هذا الدور موجود بالفعل، يرجى اختيار اسم مختلف.',
            'name.not_regex' => 'يجب أن يبدأ اسم الدور بحرف صغير (Convention).',

        ]);

        // 2. تحديث اسم الدور
        $role->update(['name' => $validatedData['name']]);

        // 3. مزامنة الصلاحيات الجديدة
        $permissionsToSync = [];

        if (!empty($validatedData['permissions'])) {

            // 4. الخطوة الحاسمة: تحويل مصفوفة الـ IDs إلى كائنات Permission Models
            $permissionsToSync = Permission::whereIn('id', $validatedData['permissions'])->get();
        }

        // 5. استخدام syncPermissions لمزامنة الصلاحيات (يتم إزالة القديمة وإضافة الجديدة)
        // إذا كانت $permissionsToSync فارغة (لم يتم تحديد أي شيء)، فسيتم إزالة جميع الصلاحيات من الدور.
        $role->syncPermissions($permissionsToSync);

        return redirect()->route('admin.roles.index')->with('success', 'تم تحديث الدور بنجاح.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // التأكد من أن الدور ليس 'admin' أو أي دور حرج آخر قبل الحذف
        if ($role->name === 'super-admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'لا يمكن حذف دور "super-admin" الأساسي.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'تم حذف الدور بنجاح!');
    }
}
