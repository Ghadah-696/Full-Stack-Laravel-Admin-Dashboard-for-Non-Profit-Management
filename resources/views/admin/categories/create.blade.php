@extends('layouts.admin')
@section('title', 'إضافة تصنيف جديد')

@section('content')
    <div class="p-6 md:p-10">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر العودة --}}
        {{-- ================================================= --}}
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-plus-circle ml-2" style="color: var(--primary-color);"></i> إضافة تصنيف جديد
            </h1>
            <a href="{{ route('admin.categories.index') }}"
                class="btn bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-300 shadow-md whitespace-nowrap">
                <i class="fas fa-arrow-right ml-2"></i> العودة للقائمة
            </a>
        </div>

        {{-- ================================================= --}}
        {{-- 3. بطاقة النموذج (Form Card) --}}
        {{-- ================================================= --}}
        <div class="bg-white shadow-xl rounded-xl p-6 md:p-8">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                {{-- حقل الاسم العربي --}}
                <div class="mb-6">
                    <label class="block text-gray-700 text-base font-semibold mb-2" for="name_ar">
                        <i class="fas fa-language ml-1 text-gray-500"></i> اسم التصنيف (عربي)
                        <span class="text-red-500 text-sm">*</span>
                    </label>
                    <input
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 @error('name_ar') border-red-500 @enderror"
                        id="name_ar" type="text" name="name_ar" value="{{ old('name_ar') }}"
                        placeholder="أدخل اسم التصنيف باللغة العربية">
                    @error('name_ar')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- حقل الاسم الإنجليزي --}}
                <div class="mb-6">
                    <label class="block text-gray-700 text-base font-semibold mb-2" for="name_en">
                        <i class="fas fa-language ml-1 text-gray-500"></i> اسم التصنيف (إنجليزي)
                        <span class="text-red-500 text-sm">*</span>
                    </label>
                    <input
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ltr:text-left rtl:text-right @error('name_en') border-red-500 @enderror"
                        id="name_en" type="text" name="name_en" value="{{ old('name_en') }}"
                        placeholder="Enter category name in English">
                    @error('name_en')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <!-- حقل الوصف باللغة العربية -->
                <div>
                    <label for="description_ar" class="block text-sm font-medium text-gray-700">الوصف (العربية)</label>
                    <textarea name="description_ar" id="description_ar" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل الوصف باللغة الإنجليزية -->
                <div>
                    <label for="description_en" class="block text-sm font-medium text-gray-700">الوصف (الإنجليزية)</label>
                    <textarea name="description_en" id="description_en" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حقل النوع (القائمة المنسدلة) -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">نوع التصنيف</label>
                    <select name="type" id="type" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border bg-white">
                        <option value="" disabled selected>-- اختر النوع --</option>
                        <option value="project" {{ old('type') == 'project' ? 'selected' : '' }}>للمشاريع (Project)</option>
                        <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>للأخبار (News)</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- أزرار الإرسال والإلغاء --}}
                <div class="flex items-center justify-end mt-8 border-t pt-6">
                    <a href="{{ route('admin.categories.index') }}"
                        class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150">
                        <i class="fas fa-times ml-2"></i> إلغاء
                    </a>
                    <button type="submit"
                        class="btn bg-green-600 text-white hover:bg-green-700 font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
                        style="background-color: var(--primary-color);">
                        <i class="fas fa-save ml-2"></i> حفظ التصنيف
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection