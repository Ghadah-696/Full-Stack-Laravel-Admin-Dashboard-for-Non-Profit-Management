@extends('layouts.admin')
<!-- @section('page_title', 'إدارة الأخبار') -->

@section('title', 'تسجيل شريك أو راعٍ جديد')

@section('content')
    <div class="p-6 md:p-10">
        {{-- ================================================= --}}
        {{-- 1. العنوان (Header) --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            {{-- العنوان الأساسي بتنسيق موحد --}}
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-plus-square ml-2" style="color: var(--primary-color);"></i> تسجيل شريك أو راعٍ جديد
            </h1>
            <a href="{{ route('admin.partners.index') }}"
                class="btn bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-300 shadow-md whitespace-nowrap">
                <i class="fas fa-arrow-right ml-2"></i> العودة لقائمة الشركاء
            </a>
        </div>

        <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data"
            class="card bg-white shadow-xl rounded-xl p-8">
            @csrf

            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700" style="color: var(--primary-color);">1. بيانات
                الشريك الأساسية</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <div class="form-group">
                    <label for="name_ar" class="block text-sm font-medium text-gray-700">اسم الشريك (عربي) <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                        placeholder="مثال: شركة النور للاتصالات">
                </div>

                <div class="form-group">
                    <label for="name_en" class="block text-sm font-medium text-gray-700">Partner Name (English) <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                        placeholder="Example: Al-Nour Telecom Company">
                </div>

                <div class="form-group">
                    <label for="website_url" class="block text-sm font-medium text-gray-700">رابط الموقع الإلكتروني
                        (اختياري)</label>
                    <input type="url" name="website_url" id="website_url" value="{{ old('website_url') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                        placeholder="مثال: https://www.alnoor-telecom.com">
                </div>

                <div class="form-group">
                    <label for="type" class="block text-sm font-medium text-gray-700">نوع الشراكة (اختياري)</label>
                    <select name="type" id="type"
                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
                        <option value="شريك استراتيجي" {{ old('type') == 'شريك استراتيجي' ? 'selected' : '' }}>شريك استراتيجي
                        </option>
                        <option value="راعي ماسي" {{ old('type') == 'راعي ماسي' ? 'selected' : '' }}>راعي ماسي</option>
                        <option value="شريك دعم" {{ old('type') == 'شريك دعم' ? 'selected' : '' }}>شريك دعم</option>
                        <option value="شريك" {{ old('type', 'شريك') == 'شريك' ? 'selected' : '' }}>شريك</option>
                    </select>
                </div>

            </div>

            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700" style="color: var(--primary-color);">2. شعار
                الشريك</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="form-group md:col-span-2">
                    <label for="logo_file" class="block text-sm font-medium text-gray-700">تحميل شعار الشريك <span
                            class="text-red-500">*</span></label>
                    <input type="file" name="logo_file" id="logo_file"
                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">يُفضل رفع شعار بخلفية شفافة (PNG) وحجم لا يتجاوز 2 ميجابايت.</p>
                </div>
            </div>

            <div class="mt-4">
                <div class="form-group flex items-center">
                    <label for="status" class="block text-sm font-medium text-gray-700 ml-3">تفعيل الشريك (للعرض على
                        الموقع)</label>
                    <label class="switch">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
            {{-- أزرار الإرسال والإلغاء --}}
            <div class="flex items-center justify-end mt-10 border-t pt-6">
                <a href="{{ route('admin.partners.index') }}"
                    class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
                <button type="submit"
                    class="btn text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
                    style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                    <i class="fas fa-save ml-2"></i>حفظ بيانات الشريك
                </button>
            </div>
        </form>
    </div>
@endsection