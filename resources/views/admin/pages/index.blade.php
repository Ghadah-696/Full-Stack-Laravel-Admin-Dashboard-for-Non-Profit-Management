@extends('layouts.admin')
@section('page_title', 'إدارة الصفحات الثابتة')

@section('content')
    <div class="p-6 md:p-10"> {{-- استخدام p-6/p-10 بدلاً من container mx-auto p-4 لتجنب أي تعارض في العرض --}}
        
        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة (Header & Actions) --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-file-alt ml-2" style="color: var(--primary-color);"></i> إدارة الصفحات الثابتة وهيكلها
            </h1>
            
            @can('create_page')
                {{-- زر الإضافة الموحد --}}
                <a href="{{ route('admin.pages.create') }}"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-200 shadow-md font-semibold"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إنشاء صفحة فرعية جديدة
                </a>
            @endcan
        </div>
        
        {{-- ================================================= --}}
        {{-- 2. نموذج البحث (Search Form) --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl p-5 mb-8">
            <h3 class="text-lg font-bold mb-3 text-gray-700">البحث والتصفية</h3>
            <form action="{{ route('admin.pages.index') }}" method="GET"
                class="flex flex-wrap items-center gap-4">
                
                <input type="text" name="q" placeholder="ابحث بالعنوان أو الرابط أو المحتوى..." value="{{ request('q') }}"
                    class="flex-1 min-w-[200px] border border-gray-300 rounded-lg py-2 px-4 text-gray-700 focus:outline-none focus:ring-2"
                    style="focus-ring-color: var(--primary-color-light);">

                <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                    <i class="fas fa-search"></i>
                </button>

                {{-- زر مسح البحث (يظهر فقط إذا كان هناك معيار بحث) --}}
                @if (request()->has('q') && request('q') != '')
                    <a href="{{ route('admin.pages.index') }}"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
        
        {{-- ================================================= --}}
        {{-- 3. قائمة الصفحات الرئيسية الثابتة (Card Grid) --}}
        {{-- ================================================= --}}
        <div class="mb-10">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 border-b pb-2" style="color: var(--secondary-color);">الصفحات الثابتة الأساسية</h2>
            
            {{-- تم تعديل الشبكة لزيادة عرض البطاقات وتحسين الوضوح --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($fixedPages as $slug => $title)
                    @php
                        $page = $parentPages->where('slug', $slug)->first();
                        $statusClass = $page ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500';
                        $statusText = $page ? 'مفعلة وجاهزة للتعديل' : 'غير مُنشأة بعد';
                    @endphp
                    
                    {{-- بطاقة الصفحة الثابتة --}}
                    <div class="card bg-white p-6 shadow-xl rounded-xl transition duration-300 hover:shadow-2xl {{ $statusClass }}">
                        {{-- تم رفع حجم الخط ليكون أكثر وضوحاً --}}
                        <p class="font-extrabold text-xl text-gray-900 mb-1">{{ $title }}</p>
                        {{-- تم رفع حجم الخط --}}
                        <p class="text-sm text-gray-500 mb-3 font-mono">الرابط: /{{ $slug }}</p>

                        @if ($page)
                            {{-- إذا كانت موجودة، نتيح التعديل المباشر --}}
                            @can('edit_page')
                                <a href="{{ route('admin.pages.edit', $page) }}" 
                                    class="text-sm font-bold hover:underline mt-2 inline-block"
                                    style="color: var(--primary-color);">
                                    <i class="fas fa-edit ml-1"></i> تعديل المحتوى ({{ $statusText }})
                                </a>
                            @endcan
                        @else
                            {{-- إذا لم تكن موجودة، نتيح إنشائها --}}
                            @can('create_page')
                                <a href="{{ route('admin.pages.create', ['slug' => $slug, 'title_ar' => $title]) }}"
                                    class="text-sm text-red-600 hover:underline font-bold mt-2 inline-block">
                                    <i class="fas fa-wrench ml-1"></i> إنشاء المحتوى الآن ({{ $statusText }})
                                </a>
                            @endcan
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ============================================== --}}
        {{-- 4. جدول إدارة الصفحات الأبناء (Hierarchical Table) --}}
        {{-- ============================================== --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800 border-b pb-2" style="color: var(--secondary-color);">هيكلية الصفحات الداخلية (الأبناء)</h2>

            <div class="card bg-white shadow-xl rounded-xl overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    {{-- رأس الجدول --}}
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">اسم الصفحة</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الصفحة الأم</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">الترييب</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    {{-- جسم الجدول --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($parentPages as $parent)
                            {{-- الصفحات الرئيسية (الآباء) --}}
                            <tr class="bg-gray-100 font-semibold hover:bg-gray-200 transition duration-150">
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900" style="color: var(--secondary-color);">
                                    <i class="fas fa-folder-open text-base ml-2" style="color: var(--primary-color);"></i> {{ $parent->title_ar }} (رئيسي)
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-500">--</td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-center text-gray-700">{{ $parent->order }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-center">
                                    <span class="px-3 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        ثابت
                                    </span>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-center text-sm space-x-2 space-x-reverse">
                                    @can('edit_page')
                                        <a href="{{ route('admin.pages.edit', $parent) }}"
                                            class="text-blue-600 hover:text-blue-900 transition duration-150" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>

                            {{-- الصفحات الفرعية (الأبناء) --}}
                            @foreach ($parent->children as $child)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-800 pr-12">
                                        <i class="fas fa-indent text-gray-400 ml-2"></i> {{ $child->title_ar }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-600">
                                        {{ $parent->title_ar }}
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-center text-gray-500">{{ $child->order }}</td>
                                    <td class="px-6 py-3 whitespace-nowrap text-sm text-center">
                                        <span class="px-3 inline-flex text-xs leading-5 font-semibold rounded-full {{ $child->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $child->status ? 'منشور' : 'مسودة' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap text-center text-sm space-x-3 space-x-reverse">
                                        @can('edit_page')
                                            <a href="{{ route('admin.pages.edit', $child) }}" 
                                                class="text-blue-600 hover:text-blue-900 transition duration-150" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('delete_page')
                                            {{-- ✅ تم التعديل: استبدال النموذج بالزر الذي يشغل المودال المركزي --}}
                                            <button type="button" 
                                                class="text-red-600 hover:text-red-900 transition duration-150 js-delete-trigger" 
                                                data-action="{{ route('admin.pages.destroy', $child) }}" 
                                                data-title="{{ $child->title_ar }}" 
                                                title="حذف">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endcan
                                        
                                        <!-- @can('delete_page')
                                            {{-- تم استبدال `confirm()` بنموذج وهمي للامتثال لقواعد المنصة --}}
                                            <form action="{{ route('admin.pages.destroy', $child) }}" method="POST" class="inline-block delete-form" data-page-title="{{ $child->title_ar }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-150 delete-btn" title="حذف">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan -->
                                    </td>
                                </tr>
                            @endforeach

                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-5 whitespace-nowrap text-center text-sm text-gray-500 bg-white">
                                    لا توجد صفحات فرعية أو رئيسية مُنشأة بعد.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
@endsection