@extends('layouts.admin')

@section('content')
    <!-- <div class="container mx-auto p-4"> -->
    <div class="p-6 md:p-10">
        <!-- <div class="mb-6"> -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <!-- <h2 class="text-2xl font-semibold">تعديل الصفحة الثابتة: {{ $page->title_ar }}</h2> -->
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-plus-square ml-2" style="color: var(--primary-color);"></i>تعديل الصفحة الثابتة
            </h1>
            <p class="text-gray-600">يمكنك تحديث المحتوى، بيانات SEO، وهيكلية الصفحة.</p>
        </div>


        <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data"
            class="card bg-white shadow-xl rounded-xl p-8">
            @csrf
            @method('PUT') {{-- الإشارة إلى دالة Update في المتحكم --}}

            {{-- ============================================= --}}
            <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. المحتوى الأساسي و SEO
            </h3>
            {{-- ============================================= --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- حقل Slug (العنوان الثابت) --}}
                <div class="form-group md:col-span-2">
                    <label for="slug" class="block text-sm font-bold text-gray-700 mb-1">الرابط الثابت (Slug) <span
                            class="text-red-500">*</span></label>
                    {{-- تعبئة القيمة الحالية --}}
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    <p class="text-xs text-red-500 mt-1">تعديل الـ Slug قد يكسر الروابط القديمة!</p>
                </div>

                {{-- العنوان (عربي) --}}
                <div class="form-group">
                    <label for="title_ar" class="block text-sm font-bold text-gray-700 mb-1">عنوان الصفحة (عربي) <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar', $page->title_ar) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                </div>

                {{-- العنوان (إنجليزي) --}}
                <div class="form-group">
                    <label for="title_en" class="block text-sm font-bold text-gray-700 mb-1">Page Title (English) <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $page->title_en) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                </div>
            </div>

            {{-- محتوى الصفحة --}}
            <div class="form-group mb-6">
                <label for="body_ar" class="block text-sm font-bold text-gray-700 mb-1">المحتوى (عربي)</label>
                <textarea name="body_ar" id="body_ar" rows="6"
                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('body_ar', $page->body_ar) }}</textarea>
            </div>
            <div class="form-group mb-6">
                <label for="body_en" class="block text-sm font-bold text-gray-700 mb-1">Body (English)</label>
                <textarea name="body_en" id="body_en" rows="6"
                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-4 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('body_en', $page->body_en) }}</textarea>
            </div>

            {{-- حقول SEO (Meta) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 border-t pt-6">
                <div class="form-group">
                    <label for="meta_title_ar" class="block text-sm font-bold text-gray-700 mb-1">Meta Title (عربي - لنتائج
                        البحث)</label>
                    <input type="text" name="meta_title_ar" id="meta_title_ar"
                        value="{{ old('meta_title_ar', $page->meta_title_ar) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                </div>

                <div class="form-group">
                    <label for="meta_description_ar" class="block text-sm font-bold text-gray-700 mb-1">Meta Description
                        (عربي
                        - لوصف البحث)</label>
                    <textarea name="meta_description_ar" id="meta_description_ar" rows="2"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('meta_description_ar', $page->meta_description_ar) }}</textarea>
                </div>
                {{-- تكملة حقول SEO هنا --}}
            </div>


            {{-- ============================================= --}}
            <h3 class="text-xl font-bold mb-6 border-b pb-3 pt-4" style="color: var(--primary-color);">2. الهيكلية والترتيب
            </h3>
            {{-- ============================================= --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- اختيار الصفحة الأب --}}
                <div class="form-group">
                    <label for="parent_id" class="block text-sm font-bold text-gray-700 mb-1">تنتمي للصفحة الأم
                        (اختياري)</label>
                    <select name="parent_id" id="parent_id"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                        <option value="">-- صفحة رئيسية (ليس لها أب) --</option>
                        @foreach ($parentPages as $parent)
                            {{-- التأكد من عدم إمكانية اختيار الصفحة نفسها كأب --}}
                            @if ($parent->id != $page->id)
                                <option value="{{ $parent->id }}" {{ old('parent_id', $page->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->title_ar }} ({{ $parent->slug }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- ترتيب الظهور --}}
                <div class="form-group">
                    <label for="order" class="block text-sm font-bold text-gray-700 mb-1">ترتيب الظهور (Order)</label>
                    <input type="number" name="order" id="order" value="{{ old('order', $page->order) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                </div>
            </div>

            {{-- ============================================= --}}
            <h3 class="text-xl font-bold mb-6 border-b pb-3 pt-4" style="color: var(--primary-color);">3. الصورة والحالة
            </h3>

            {{-- ============================================= --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                {{-- الصورة الحالية وخيار الحذف/الرفع --}}
                <div class="form-group">
                    @if ($page->banner_image_path)
                        <label for="banner_image" class="block text-sm font-bold text-gray-700 mb-1">الصورة البارزة (Banner
                            Image)</label>
                        <img src="{{ asset('page_banners/' . $page->banner_image_path) }}" alt="Banner Image"
                            class="w-full max-h-48 object-cover mb-3 rounded-lg border">

                        <div class="flex items-center mb-3">
                            <input type="checkbox" name="delete_banner_image" id="delete_banner_image" value="1"
                                class="h-4 w-4 text-red-600 border-gray-300 rounded">
                            <label for="delete_banner_image" class="ml-2 block text-sm text-red-600">حذف الصورة الحالية</label>
                        </div>
                    @endif

                    <label for="banner_image" class="block text-sm font-bold text-gray-700 mb-1">رفع صورة جديدة</label>
                    <input type="file" name="banner_image" id="banner_image"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                {{-- حالة التفعيل والزر المخصص --}}
                <div class="form-group flex flex-col items-start justify-start mt-2">
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-2">حالة الصفحة</label>
                    <label class="switch relative inline-block w-14 h-8">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                            class="opacity-0 w-0 h-0 peer" style="background-color: var(--primary-color);">
                        <span
                            class="slider round absolute cursor-pointer top-0 left-0 right-0 bottom-0 bg-gray-300 transition-colors duration-300 rounded-full before:absolute before:content-[''] before:h-6 before:w-6 before:left-1 before:bottom-1 before:bg-white before:transition-transform before:duration-300 before:rounded-full peer-checked:bg-blue-rgb(56 182 255) peer-checked:translate-x-6"></span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1">مفعلة (منشورة) أو غير مفعلة (مسودة).</p>
                </div>
            </div>


            <div class="mt-8 pt-6 border-t flex justify-start gap-4">
                <button type="submit"
                    class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200 shadow-md font-bold"
                    style="background-color: var(--primary-color);">
                    تحديث الصفحة
                </button>
                <a href="{{ route('admin.pages.index') }}"
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200 shadow-md font-bold">
                    <i class="fas fa-times ml-2"></i> إلغاء والعودة
                </a>
            </div>
        </form>
    </div>
@endsection