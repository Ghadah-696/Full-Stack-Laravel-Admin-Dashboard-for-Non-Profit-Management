@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">إعدادات الملف الشخصي</h1>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. تحديث المعلومات الشخصية والصورة --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- تضمين الجزء الخاص بتحديث المعلومات (سنقوم بتعديله في النقطة الرابعة) --}}
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- 2. تحديث كلمة المرور --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- تضمين الجزء الخاص بتحديث كلمة المرور --}}
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- 3. حذف الحساب --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    {{-- تضمين الجزء الخاص بحذف الحساب --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection