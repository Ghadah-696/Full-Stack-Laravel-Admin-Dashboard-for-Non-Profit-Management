<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use App\Notifications\NewUserRegistered;

// الإشعارات
use App\Notifications\RoleUpdatedNotification;
use App\Notifications\PasswordChangedNotification;

class UserController extends Controller
{
    /**
     * دالة البناء لتطبيق الحماية عبر الـ Middleware.
     * تحدد الصلاحيات المطلوبة لكل مجموعة من الدوال.
     */
    public function __construct()
    {
        // يتطلب الوصول إلى هذه الدوال الصلاحية المحددة
        $this->middleware('permission:view_user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_user', ['only' => ['destroy']]);
    }
    /**
     * دالة مساعدة: جلب جميع المشرفين العموميين (لإرسال الإشعارات).
     * يجب تعديل هذه الدالة لتناسب طريقة إدارة الصلاحيات لديكِ.
     */
    protected function getSuperAdmins()
    {
        // نفترض هنا استخدام حزمة Spatie permissions أو أي طريقة للتحقق من الدور
        // يمكن تعديلها لجلب المستخدمين الذين لديهم الدور 'super-admin'
        return User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->get();
    }

    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 💡 يمكن هنا جلب الأدوار (Roles) إذا كان لديك نظام أدوار مُثبّت
        $roles = Role::all();

        return view('admin.users.create', compact('roles')); // إرسال المستخدم إلى واجهة إنشاء
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات (Validation)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()   // 💡 القيد الجديد: يجب أن تحتوي على حرف أبجدي واحد على الأقل
                    ->numbers()   // يجب أن تحتوي على رقم واحد على الأقل
                    ->symbols()   // يجب أن تحتوي على رمز واحد على الأقل
            ],
            // 💡 التحقق من الدور: يجب أن يكون مطلوباً وموجوداً في جدول الأدوار
            'role' => 'required|string|exists:roles,name',
        ]);

        // 2. إنشاء المستخدم الجديد
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // 3. تعيين الدور للمستخدم الجديد باستخدام Spatie
        $user->assignRole($validatedData['role']);
        // 1. ابحث عن المدير العام (المستخدم ذو الـ ID=1 أو صاحب دور المدير العام)
        $adminUser = User::find(1);

        // 2. إرسال الإشعار
        if ($adminUser) {
            $adminUser->notify(new NewUserRegistered($user));
        }

        // 4. إعادة التوجيه
        return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم بنجاح وتعيين دور ' . $validatedData['role'] . ' له.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        // جلب الدور الحالي للمستخدم
        $currentRole = $user->roles->pluck('name')->first();

        return view('admin.users.edit', compact('user', 'roles', 'currentRole'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $warningMessage = null;

        // 1. قواعد التحقق (Validation Rules) للبيانات والدور فقط
        $validationRules = [
            'name' => 'required|string|max:255',
            // التحقق من تكرار البريد الإلكتروني وتجاهل ID المستخدم الحالي
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|string|exists:roles,name', // استخدمنا 'role' كما في الكود الأصلي الخاص بكِ
        ];

        $validatedData = $request->validate($validationRules);

        // 2. تطبيق منطق الحماية: إذا كان المستخدم هو المدير العام (ID 1)
        if ($user->id === 1) {
            // أ. منع تغيير الدور: إذا كان الدور المُرسل لا يساوي 'super-admin'
            if ($validatedData['role'] !== 'super-admin') {

                // إلغاء محاولة تغيير الدور وإرجاع رسالة تحذير
                unset($validatedData['role']);
                $warningMessage = '⚠️ تم تحديث البيانات الشخصية (الاسم/البريد)، ولكن لا يمكن تغيير دور المدير العام للنظام (ID 1).';
            }
        }

        // 3. تحديث بيانات المستخدم
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
        ]);

        // 4. تحديث الدور: يتم تحديث الدور فقط إذا لم يتم إلغاؤه في خطوة الحماية
        if (isset($validatedData['role'])) {
            // تحديث الدور
            $user->syncRoles([]);
            $user->assignRole($validatedData['role']);

            // إرسال إشعار تغيير الدور (يتم إرساله فقط عند تغيير الدور بنجاح)
            $adminWhoMadeChange = auth()->user();
            foreach ($this->getSuperAdmins() as $receiver) {
                if ($receiver->id !== $adminWhoMadeChange->id) {
                    $receiver->notify(new RoleUpdatedNotification($user, $adminWhoMadeChange));
                }
            }
        }

        // 5. إرجاع رسالة النجاح (مع رسالة التحذير في حال تم منع تغيير الدور)
        $message = $warningMessage ?? '✅ تم تحديث بيانات المستخدم ' . $user->name . ' بنجاح.';

        // نستخدم back() بدلاً من index() لإبقاء المسؤول في صفحة التعديل لرؤية التأثير
        return back()->with(isset($warningMessage) ? 'warning' : 'success', $message);
    }

    /**
     * دالة مخصصة لتحديث كلمة مرور المستخدم فقط.
     */
    public function updatePassword(Request $request, User $user)
    {
        // قواعد التحقق (Validation Rules) لكلمة المرور فقط
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                // قواعد القوة من الكود الأصلي الخاص بكِ
                Password::min(8)->letters()->numbers()->symbols(),
            ],
        ]);

        // تحديث كلمة المرور
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        // إرسال إشعار تغيير كلمة المرور
        $adminWhoMadeChange = auth()->user();
        foreach ($this->getSuperAdmins() as $receiver) {
            if ($receiver->id !== $adminWhoMadeChange->id) {
                $receiver->notify(new PasswordChangedNotification($user, $adminWhoMadeChange));
            }
        }

        return back()->with('success', '✅ تم تحديث كلمة مرور المستخدم ' . $user->name . ' بنجاح وإشعار المشرفين.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return back()->with('error', '🚫 لا يمكن حذف المستخدم الرئيسي (ID 1) للنظام.');
        }

        // لا يمكن للمستخدم حذف نفسه أيضًا
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', '✅ تم حذف المستخدم بنجاح.');
        // تحقق الأمان: منع المستخدم من حذف نفسه والمسؤول الرئيسي
        // if (auth()->user()->id === $user->id || $user->id === 1) {
        //     return back()->with('error', 'لا يمكن حذف هذا الحساب (حسابك الخاص أو المسؤول الرئيسي).');
        // }

        // $user->delete();

        // return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح.');

    }
}
