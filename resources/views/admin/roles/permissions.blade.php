@extends('layouts.admin')
@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">تعديل الصلاحيات للدور: <span
                        style="color: var(--primary-color);">{{ $role->name }}</span></h1> {{-- رسائل التنبيه
                (Success/Error) --}}
                <!-- @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif -->
                <form method="POST" action="{{ route('admin.roles.update-permissions', $role) }}">
                    @csrf
                    {{-- استخدام طريقة PUT لتحديث المورد --}}
                    @method('PUT')

                    {{-- حقل لإظهار اسم الدور بشكل غير قابل للتعديل --}}
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">اسم الدور</label>
                        <input type="text" value="{{ $role->name }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 p-2 cursor-not-allowed"
                            disabled>
                    </div>

                    {{-- تكرار على مجموعات الصلاحيات (Modules) --}}
                    @foreach ($allPermissions as $module => $permissions)
                        <div class="border border-gray-200 rounded-lg mb-8 p-4 bg-gray-50">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">
                                وحدة: {{ $module ?: 'صلاحيات عامة' }}
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($permissions as $permission)
                                    <div
                                        class="flex items-center p-3 border rounded-lg bg-white hover:bg-blue-50 transition duration-150 ease-in-out">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            id="permission-{{ $permission->id }}" {{-- تحديد مربع الاختيار إذا كان الدور يمتلك هذه
                                            الصلاحية --}} @checked(in_array($permission->name, $rolePermissions))
                                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                            style="color: var(--primary-hover-color);">
                                        <label for="permission-{{ $permission->id }}"
                                            class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                                            {{-- عرض اسم الصلاحية بطريقة منظمة (مثلاً: create-users) --}}
                                            {{ $permission->name }}
                                            @if($permission->guard_name)
                                                <span class="text-xs text-gray-400">({{ $permission->guard_name }})</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    {{-- زر الحفظ --}}
                    <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end">
                        <a href="{{ route('admin.roles.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-3">
                            إلغاء والعودة
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                            حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
        </div>
</div>@endsection