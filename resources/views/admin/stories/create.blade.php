@extends('layouts.admin')


@section('title', 'إضافة قصة جديد')

@section('content')

    <div class="p-6 md:p-10">

        {{-- ================================================= --}}
        {{-- 1. العنوان (Header) --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            {{-- العنوان الأساسي بتنسيق موحد --}}
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-plus-square ml-2" style="color: var(--primary-color);"></i> إنشاء قصة جديدة
            </h1>
            <a href="{{ route('admin.stories.index') }}"
                class="btn bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-300 shadow-md whitespace-nowrap">
                <i class="fas fa-arrow-right ml-2"></i> العودة لقائمة القصص
            </a>
        </div>

        <form action="{{ route('admin.stories.store') }}" method="POST" enctype="multipart/form-data"
            class="card bg-white shadow-xl rounded-xl p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. المحتوى
                        باللغة
                        العربية
                    </h3>

                    <div class="form-group">
                        <label for="name_ar" class="block text-sm font-medium text-gray-700">الاسم</label>
                        <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>
                    <div class="form-group">
                        <label for="title_ar" class="block text-sm font-medium text-gray-700">العنوان (اختياري)</label>
                        <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>

                    <div class="form-group">
                        <label for="content_ar" class="block text-sm font-medium text-gray-700">المحتوى (اختياري)</label>
                        <textarea name="content_ar" id="content_ar" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('content_ar') }}</textarea>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. Content in
                        English
                    </h3>

                    <div class="form-group">
                        <label for="name_en" class="block text-sm font-medium text-gray-700">الاسم</label>
                        <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>
                    <div class="form-group">
                        <label for="title_en" class="block text-sm font-medium text-gray-700">Title (Optional)</label>
                        <input type="text" name="title_en" id="title_en" value="{{ old('title_en') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>

                    <div class="form-group">
                        <label for="content_en" class="block text-sm font-medium text-gray-700">Content
                            (Optional)</label>
                        <textarea name="content_en" id="content_en" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('content_en') }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="my-6">
            {{-- ================================================= --}}
            {{-- 4. بيانات التصنيف والصورة والحالة --}}
            {{-- ================================================= --}}
            <h3 class="text-xl font-bold mb-6 border-b pb-3 pt-4" style="color: var(--primary-color);">2. البيانات المرفقة
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="form-group">
                    <label for="order" class="block text-sm font-medium text-gray-700">ترتيب الظهور (افتراضي 0)</label>
                    <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                </div>

                <div class="form-group">
                    <label for="image" class="block text-sm font-medium text-gray-700">صورة المستفيد (مطلوبة)</label>
                    <input type="file" name="image" id="image"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            {{-- أزرار الإرسال والإلغاء --}}
            <div class="flex items-center justify-end mt-10 border-t pt-6">
                <a href="{{ route('admin.stories.index') }}"
                    class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
                <button type="submit"
                    class="btn text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
                    style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                    <i class="fas fa-save ml-2"></i> حفظ
                </button>
            </div>
        </form>
    </div>
@endsection