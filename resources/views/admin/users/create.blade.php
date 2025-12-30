@extends('layouts.admin')

@section('page_title', 'إنشاء مستخدم جديد')

@section('content')
    <div class="container mx-auto p-4 md:p-10">
        {{-- ================================================= --}}
        {{-- 1. العنوان وزر العودة --}}
        {{-- ================================================= --}}
        <div class="flex justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-user-plus ml-3 text-xl" style="color: var(--primary-color);"></i>
                إنشاء مستخدم جديد
            </h1>
            <a href="{{ route('admin.users.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md">
                <i class="fas fa-arrow-right mr-2 rtl:ml-2"></i>
                العودة إلى قائمة المستخدمين
            </a>
        </div>

        {{-- ================================================= --}}
        {{-- 2. نموذج إنشاء المستخدم --}}
        {{-- ================================================= --}}
        <div class="bg-white p-6 shadow-xl rounded-xl max-w-2xl mx-auto">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                {{-- الاسم --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل:</label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- البريد الإلكتروني --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني:</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- كلمة المرور --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور:</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- تأكيد كلمة المرور --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة
                            المرور:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500">
                        {{-- لا حاجة لعرض @error هنا حيث أن الخطأ سيظهر تحت حقل 'password' --}}
                    </div>
                </div>

                {{-- تعيين الدور (هذا هو الحقل الذي يقوم بتعيين الصلاحيات) --}}
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">تعيين الدور:</label>
                    <select name="role" id="role" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring-blue-500 appearance-none bg-white">
                        <option value="">-- اختر دور المستخدم --</option>
                        {{-- المنطق: يتم التكرار على مصفوفة الأدوار الممررة وعرضها --}}
                        @if (isset($roles))
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- أزرار الإرسال والإلغاء --}}
                <div class="flex items-center justify-end mt-10 border-t pt-6">
                    <a href="{{ route('admin.users.index') }}"
                        class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                        <i class="fas fa-times ml-2"></i> إلغاء
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md  transition duration-300 shadow-md inline-flex items-center font-bold"
                        style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                        <i class="fas fa-save ml-2 rtl:mr-2"></i>
                        حفظ المستخدم
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection