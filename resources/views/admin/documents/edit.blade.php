@extends('layouts.admin')

@section('page_title', 'تعديل الوثيقة:')
@section('content')

    <div class="p-6 md:p-10">
        {{-- ================================================= --}}
        {{-- 1. العنوان --}}
        {{-- ================================================= --}}

        <!-- <div class="mb-6"> -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-edit ml-2" style="color: var(--primary-color);"></i> تعديل الوثيقة: <span
                    class="text-xl font-medium">{{ $document->title_ar}}</span>
            </h1>
            <a href="{{ route('admin.documents.index') }}"
                class="btn bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-300 shadow-md whitespace-nowrap">
                <i class="fas fa-arrow-right ml-2"></i> العودة لقائمة الوثائق
            </a>
        </div>

        <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 shadow-md rounded-lg">
            @csrf
            @method('PUT')

            <h3 class="text-xl font-bold mb-6 border-b pb-3 pt-4" style="color: var(--primary-color);">1. بيانات الوثيقة
                والملف
            </h3>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div class="form-group">
                    <label for="type" class="block text-sm font-medium text-gray-700">نوع الوثيقة <span
                            class="text-red-500">*</span></label>
                    <select name="type" id="type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="مالي" {{ old('type', $document->type) == 'مالي' ? 'selected' : '' }}>تقرير مالي
                        </option>
                        <option value="حوكمة" {{ old('type', $document->type) == 'حوكمة' ? 'selected' : '' }}>تقرير حوكمة
                        </option>
                        <option value="استراتيجي" {{ old('type', $document->type) == 'استراتيجي' ? 'selected' : '' }}>خطة
                            استراتيجية</option>
                        <option value="أخرى" {{ old('type', $document->type) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="year" class="block text-sm font-medium text-gray-700">سنة الإصدار </label>
                    <input type="number" name="year" id="year" value="{{ old('year', $document->year) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" placeholder="مثال: 2024">
                </div>

                <div class="form-group">
                    <label for="document_file" class="block text-sm font-medium text-gray-700">تعديل الملف (PDF,
                        DOCX)</label>
                    <input type="file" name="document_file" id="document_file"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                    @if ($document->file_path)
                        <p class="text-xs text-blue-600 mt-1">
                            الملف الحالي:
                            <a href="{{ asset('documents/' . $document->file_path) }}" target="_blank" class="hover:underline">
                                {{ $document->file_path }}
                            </a>
                        </p>
                    @endif
                    <p class="text-xs text-gray-500 mt-1">اترك الحقل فارغاً للاحتفاظ بالملف الحالي. الحد الأقصى: 5
                        ميجابايت.
                    </p>
                </div>

                <!-- <div class="form-group md:col-span-3 flex flex-col justify-end">
                        <label for="status" class="block text-sm font-medium text-gray-700">نشر الوثيقة (تفعيل)</label>
                        <label class="switch mt-2">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" name="status" id="status" value="1" {{ old('status', $document->status) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div> -->
            </div>

            <h3 class="text-xl font-bold mb-6 border-b pb-3 pt-4" style="color: var(--primary-color);">2. العناوين والأوصاف
                (ثنائي اللغة)</h3>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-4">
                    <h4 class="text-lg font-medium border-b pb-1" style="color: var(--primary-hover-color);">العربية</h4>

                    <div class="form-group">
                        <label for="title_ar" class="block text-sm font-medium text-gray-700">العنوان (عربي) </label>
                        <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar', $document->title_ar) }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="form-group">
                        <label for="description_ar" class="block text-sm font-medium text-gray-700">الوصف (عربي)</label>
                        <textarea name="description_ar" id="description_ar" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ old('description_ar', $document->description_ar) }}</textarea>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-medium border-b pb-1" style="color: var(--primary-hover-color);">English</h4>

                    <div class="form-group">
                        <label for="title_en" class="block text-sm font-medium text-gray-700">Title (English) </label>
                        <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $document->title_en) }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div class="form-group">
                        <label for="description_en" class="block text-sm font-medium text-gray-700">Description
                            (English)</label>
                        <textarea name="description_en" id="description_en" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ old('description_en', $document->description_en) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- أزرار الإرسال والإلغاء --}}
            <div class="flex items-center justify-end mt-10 border-t pt-6">
                <a href="{{ route('admin.documents.index') }}"
                    class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
                <button type="submit"
                    class="btn text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
                    style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                    <i class="fas fa-save ml-2"></i>حفظ وتحديث الوثيقة
                </button>
            </div>
        </form>
    </div>
@endsection