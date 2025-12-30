@extends('layouts.admin')
@section('title', 'إضافة خبر جديد')

{{-- يفترض أن Alpine.js متاح في ملف layout/admin.blade.php لاستخدام x-data --}}
@section('content')
    <div class="p-6 md:p-10">

        {{-- ================================================= --}}
        {{-- 1. العنوان (Header) --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-plus-square ml-2" style="color: var(--primary-color);"></i> إنشاء خبر أو مقال جديد
            </h1>
            <p class="text-sm text-gray-500 hidden sm:block">
                إضافة محتوى إخباري باللغتين العربية والإنجليزية.
            </p>
        </div>


        {{-- ================================================= --}}
        {{-- 3. نموذج الإضافة (Form Container) --}}
        {{-- تم وضعه داخل بطاقة بيضاء (Card) --}}
        {{-- ================================================= --}}
        <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data"
            class="card bg-white shadow-xl rounded-xl p-8">
            @csrf

            {{-- تفعيل نظام التبويبات للغات --}}
            <!-- <div x-data="{ activeTab: 'ar' }" class="mb-8"> -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- تبويبات التنقل بين اللغات --}}
                <!-- <div class="border-b border-gray-200 mb-6 flex space-x-4 rtl:space-x-reverse"> -->
                <!-- <button type="button" @click="activeTab = 'ar'"
                                                                    :class="{ 'border-b-4 border-blue-600 text-blue-600 font-bold': activeTab === 'ar', 'text-gray-500 hover:text-gray-700': activeTab !== 'ar' }"
                                                                    class="py-2 px-4 transition duration-150 text-base" style="color: var(--primary-color);">
                                                                    اللغة العربية (AR)
                                                                </button>
                                                                <button type="button" @click="activeTab = 'en'"
                                                                    :class="{ 'border-b-4 border-blue-600 text-blue-600 font-bold': activeTab === 'en', 'text-gray-500 hover:text-gray-700': activeTab !== 'en' }"
                                                                    class="py-2 px-4 transition duration-150 text-base" style="color: var(--primary-color);">
                                                                    اللغة الإنجليزية (EN)
                                                                </button>
                                                            </div> -->

                {{-- ================================================= --}}
                {{-- 3.1. محتوى الخبر (التبويب العربي) --}}
                {{-- ================================================= --}}
                <!-- <div x-show="activeTab === 'ar'" class="space-y-6"> -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. المحتوى
                        باللغة
                        العربية
                    </h3>

                    <div class="form-group">
                        <label for="title_ar" class="block text-sm font-bold text-gray-700 mb-1">عنوان الخبر (عربي)
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>

                    <div class="form-group">
                        <label for="summary_ar" class="block text-sm font-bold text-gray-700 mb-1">ملخص الخبر
                            (عربي)</label>
                        <textarea name="summary_ar" id="summary_ar" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('summary_ar') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="body_ar" class="block text-sm font-bold text-gray-700 mb-1">محتوى الخبر
                            (عربي)</label>
                        <textarea name="body_ar" id="body_ar" rows="8"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-4 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('body_ar') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">💡 يُفضل استخدام محرر نصوص غني (Rich Text Editor) هنا
                            لسهولة
                            التنسيق.</p>
                    </div>
                </div>

                {{-- ================================================= --}}
                {{-- 3.2. محتوى الخبر (التبويب الإنجليزي) --}}
                {{-- ================================================= --}}

                <!-- <div x-show="activeTab === 'en'" style="display: none;" class="space-y-6"> -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. Content in
                        English
                    </h3>

                    <div class="form-group">
                        <label for="title_en" class="block text-sm font-bold text-gray-700 mb-1">News Title (English)
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>

                    <div class="form-group">
                        <label for="summary_en" class="block text-sm font-bold text-gray-700 mb-1">News Summary
                            (English)</label>
                        <textarea name="summary_en" id="summary_en" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('summary_en') }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="body_en" class="block text-sm font-bold text-gray-700 mb-1">News Body
                            (English)</label>
                        <textarea name="body_en" id="body_en" rows="8"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-4 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('body_en') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- نهاية تبويبات اللغات --}}
            <!-- </div> -->

            {{-- ================================================= --}}
            {{-- 4. بيانات التصنيف والصورة والحالة --}}
            {{-- ================================================= --}}
            <h3 class="text-xl font-bold mb-6 border-b pb-3 pt-4" style="color: var(--primary-color);">2. البيانات
                المرفقة
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- حقل التصنيف --}}
                <div class="form-group">
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-1">التصنيف <span
                            class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                        required>
                        <option value="">اختر تصنيفاً</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- حقل رفع الصورة --}}
                <div class="form-group">
                    <label for="image" class="block text-sm font-bold text-gray-700 mb-1">صورة الخبر الرئيسية (Banner
                        Image)</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">الصورة الرئيسية التي تظهر مع الخبر.</p>
                </div>
            </div>

            {{-- حالة التفعيل (مفتاح التبديل الموحد) --}}
            <div class="form-group flex items-center mb-6 pt-4 border-t">
                <label for="status" class="block text-sm font-bold text-gray-700 ml-4">حالة الخبر</label>
                <label class="switch relative inline-block w-14 h-8">
                    <input type="hidden" name="status" value="0"> {{-- قيمة غير محددة (غير منشور) --}}
                    <input type="checkbox" name="status" id="status" value="1" {{ old('status') ? 'checked' : '' }}
                        class="opacity-0 w-0 h-0 peer" style="background-color: var(--primary-color);">
                    <span class="slider round"></span>
                </label>
                <p class="text-xs text-gray-500 mr-2">نشر (مرئي للعامة) / مسودة (غير مرئي).</p>
            </div>


            {{-- ================================================= --}}
            {{-- 5. أزرار الإجراءات (Actions) --}}
            {{-- ================================================= --}}
            <div class="mt-8 pt-6 border-t flex justify-start gap-4">
                {{-- زر الإرسال الأساسي (الحفظ) --}}
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200 shadow-md font-bold"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-save ml-2"></i> حفظ وإضافة الخبر
                </button>

                {{-- زر الإلغاء --}}
                <a href="{{ route('admin.news.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200 shadow-md font-bold">
                    <i class="fas fa-times ml-2"></i> إلغاء والعودة
                </a>
            </div>
        </form>
    </div>
@endsection