@extends('layouts.admin')


@section('title', 'إضافة مشروع جديد')

@section('content')
    <div class="p-6 md:p-10">
        {{-- ================================================= --}}
        {{-- 1. العنوان وزر العودة --}}
        {{-- ================================================= --}}
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-project-diagram ml-2" style="color: var(--primary-color);"></i> إضافة مشروع جديد
            </h1>
            <a href="{{ route('admin.projects.index') }}"
                class="btn bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-300 shadow-md whitespace-nowrap">
                <i class="fas fa-arrow-right ml-2"></i> العودة لقائمة المشاريع
            </a>
        </div>

        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data"
            class="card bg-white shadow-xl rounded-xl p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. المحتوى
                        باللغة
                        العربية
                    </h3>

                    <div class="form-group">
                        <label for="title_ar" class="block text-sm font-bold text-gray-700 mb-1"> عنوان المشروع (عربي)
                            <span class="text-red-500">*</span><label>
                                <input id="title_ar" type="text" name="title_ar" value="{{ old('title_ar') }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>



                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">ملخص المشروع(عربي)
                            <span class="text-red-500">*</span></label>
                        <textarea id="summary_ar" name="summary_ar" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('summary_ar') }}</textarea>
                    </div>



                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="body_ar">محتوى المشروع (عربي)
                            <span class="text-red-500">*</span></label>
                        <textarea
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                            id="body_ar" name="body_ar" rows="6">{{ old('body_ar') }}</textarea>
                    </div>


                </div>
                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. Content in
                        English
                    </h3>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="title_en">عنوان المشروع
                            (English)
                            <span class="text-red-500">*</span></label>
                        <input
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                            id="title_en" type="text" name="title_en" value="{{ old('title_en') }}">
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_en">ملخص المشروع
                            (English)
                            <span class="text-red-500">*</span></label>
                        <textarea
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                            id="summary_en" name="summary_en" rows="3">{{ old('summary_en') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="body_en">محتوى المشروع
                            (English)
                            <span class="text-red-500">*</span></label>
                        <textarea
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                            id="body_en" name="body_en" rows="6">{{ old('body_en') }}</textarea>
                    </div>
                </div>
            </div>
            {{-- ================================================= --}}
            {{-- 4. بيانات التصنيف والصورة والحالة --}}
            {{-- ================================================= --}}
            <h3 class="text-xl font-bold mb-6 border-b pb-3 pt-4" style="color: var(--primary-color);">2. البيانات
                المرفقة
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- حقل التصنيف --}}
                <div class="form-group">
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-1">التصنيف<span
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
                    @error('category_id') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>
                {{-- حقل رفع الصورة --}}
                <div class="form-group">
                    <label class="block text-sm font-bold text-gray-700 mb-1">صورة المشروع</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>
            {{-- حالة التفعيل (مفتاح التبديل الموحد) --}}
            <!-- <div class="form-group">
                                                            <label class="block text-sm font-bold text-gray-700 mb-1">حالة المشروع</label>
                                                            <div class="flex items-center">
                                                                <input type="checkbox" name="status" id="status" value="1" class="mr-2" {{ old('status') ? 'checked' : '' }}>
                                                                <label for="status">نشر (اجعله مرئياً للعامة)</label>
                                                            </div>
                                                            @error('status') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                                                        </div> -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- حالة التفعيل (مفتاح التبديل الموحد) --}}
                <div class="form-group ">
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-1">حالة المشروع</label>
                    <label class="switch relative inline-block w-14 h-8">
                        {{-- حقل مخفي لضمان إرسال قيمة 0 إذا لم يتم تحديد الخيار --}}
                        <input type="hidden" name="status" value="0">
                        {{-- قيمة محددة (منشور) --}}
                        <input type="checkbox" name="status" id="status" value="1" {{ old('status') ? 'checked' : '' }}
                            class="opacity-0 w-0 h-0 peer" style="background-color: var(--primary-color);">
                        <span class="slider round"></span>
                    </label>
                    <p class="text-xs text-gray-500 mr-2">نشر (مرئي للعامة) / مسودة (غير مرئي).</p>
                    @error('status') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
                {{-- تاريخ البدء --}}
                <div class="form-group">
                    <label for="start_date" class="block text-sm font-bold text-gray-700 mb-1">تاريخ البدء</label>
                    <input type="date" name="start_date" id="start_date"
                        value="{{ old('start_date', now()->toDateString()) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400">
                    @error('start_date') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- تاريخ الانتهاء --}}
                <div class="form-group">
                    <label for="end_date" class="block text-sm font-bold text-gray-700 mb-1">تاريخ الانتهاء المتوقع</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400">
                    @error('end_date') <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- <div class="flex items-center justify-end mt-4">
                                                    <a href="{{ route('admin.projects.index') }}"
                                                        class="btn text-gray-700 bg-gray-300 hover:bg-gray-400 font-bold py-2 px-4 rounded mr-2">
                                                        إلغاء
                                                    </a>
                                                    <button type="submit" class="btn btn-primary">
                                                        إضافة
                                                    </button>
                                                </div> -->
            {{-- أزرار الإرسال والإلغاء --}}
            <div class="flex items-center justify-end mt-10 border-t pt-6">
                <a href="{{ route('admin.projects.index') }}"
                    class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
                <button type="submit"
                    class="btn text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
                    style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                    <i class="fas fa-save ml-2"></i> حفظ المشروع
                </button>
            </div>
        </form>
    </div>
@endsection