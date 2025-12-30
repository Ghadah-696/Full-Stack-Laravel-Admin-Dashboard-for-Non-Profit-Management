@extends('layouts.admin')

@section('page_title', 'إدارة الأخبار')
@section('content')

    <div class="p-6 md:p-10">

        {{-- ================================================= --}}
        {{-- 1. العنوان (Header) --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-edit ml-2" style="color: var(--primary-color);"></i> تعديل المشروع: <span
                    class="text-xl font-medium">{{ $project->title_ar }}</span>
            </h1>
            <p class="text-sm text-gray-500 hidden sm:block">
                يمكنك تحديث جميع بيانات المشروع باللغتين العربية والإنجليزية.
            </p>
        </div>
        
        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data"
            class="card bg-white shadow-xl rounded-xl p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. المحتوى
                        باللغة
                        العربية
                    </h3>

                    <div class="form-group">
                        <label for="title_ar" class="block text-sm font-bold text-gray-700 mb-1">عنوان المشروع
                            (عربي)</label>
                        <input type="text" name="title_ar" value="{{ $project->title_ar }}"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">محتوى المشروع
                            (عربي)</label>
                        <textarea name="body_ar"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400">{{ $project->body_ar }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">ملخص المشروع
                            (عربي)</label>
                        <textarea name="summary_ar"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400">{{ $project->summary_ar }}</textarea>
                    </div>
                </div>



                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. Content in
                        English
                    </h3>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">عنوان المشروع
                            (إنجليزي)</label>
                        <input type="text" name="title_en"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400"
                            value="{{ $project->title_en }}">
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">محتوى المشروع
                            (إنجليزي)</label>
                        <textarea name="body_en"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400">{{ $project->body_en }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">ملخص المشروع
                            (إنجليزي)</label>
                        <textarea name="summary_en"
                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400">{{ $project->summary_en }}</textarea>
                    </div>
                </div>
            </div>
            <hr class="my-6">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="form-group">
                    <div class="news_img">
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">صورة المشروع
                            الحالية</label>
                        @if ($project->image)
                            <img src="{{ asset('images/' . $project->image) }}" alt="News Image" width="100">
                        @endif
                        <input type="file" name="image"
                             class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
                <div class="form-group">
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-1"
                        for="summary_ar">التصنيف</label>
                    <select name="category_id" id="category_id"
                         class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $project->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

              
                  {{-- حالة التفعيل (مفتاح التبديل الموحد) --}}
            <div class="form-group flex items-center mb-6 pt-4 border-t">
                <label for="status" class="block text-sm font-bold text-gray-700 ml-4">حالة النشر</label>
                <label class="switch relative inline-block w-14 h-8">
                    <input type="hidden" name="status" value="0"> {{-- قيمة غير محددة (غير منشور) --}}
                    <input type="checkbox" name="status" id="status" value="1" {{ old('status', $project->status) ? 'checked' : '' }}
                        class="opacity-0 w-0 h-0 peer" style="background-color: var(--primary-color);">
                    <span class="slider round"></span>
                </label>
                <p class="text-xs text-gray-500 mr-2">نشر (مرئي للعامة) / مسودة (غير مرئي).</p>
                @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
            </div>
               
                <!-- <div class="form-group">
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar">حالة
                        النشر</label>

                    <input type="hidden" name="status" value="0"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-inner p-3 text-gray-800 focus:border-green-400 focus:ring-1 focus:ring-green-400">

                    <div class="form-check">
                        <input type="checkbox" name="status" id="status" class="form-check-input" value="1" {{ $project->status ? 'checked' : '' }}>
                        <label class="block text-sm font-bold text-gray-700 mb-1" for="summary_ar" for="status">منشور /
                            مفعّل</label>
                    </div>
                    @error('status')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div> -->
               {{-- أزرار الإرسال والإلغاء --}}
               </div>
            <div class="flex items-center justify-end mt-10 border-t pt-6">
                <a href="{{ route('admin.projects.index') }}"
                    class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
                <button type="submit"
                    class="btn text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
                    style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                    <i class="fas fa-save ml-2"></i> تحديث المشروع
                </button>
            </div>
       
        </form>
@endsection