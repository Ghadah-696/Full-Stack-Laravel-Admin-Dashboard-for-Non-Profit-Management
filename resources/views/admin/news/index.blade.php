@extends('layouts.admin')
@section('page_title', 'إدارة الأخبار')

@section('content')

    <div class="p-6 md:p-10">
        
        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة (Header & Actions) --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-newspaper ml-2" style="color: var(--primary-color);"></i> إدارة الأخبار والمقالات
            </h1>
            
            @can('create_news')
                {{-- زر الإضافة الموحد --}}
                <a href="{{ route('admin.news.create') }}"
                    class="btn bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition duration-200 shadow-md font-semibold"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إضافة خبر جديد
                </a>
            @endcan
        </div>
        
        {{-- ================================================= --}}
        {{-- 2. نموذج البحث (Search Form) --}}
        {{-- * افتراض أن البحث يتم على حقل 'q' ويتم إرساله إلى نفس المسار * --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl p-5 mb-8">
            <h3 class="text-lg font-bold mb-3 text-gray-700">البحث والتصفية</h3>
            <form action="{{ route('admin.news.index') }}" method="GET"
                class="flex flex-wrap items-center gap-4">
                
                <input type="text" name="q" placeholder="ابحث بالعنوان أو الخلاصة..." value="{{ request('q') }}"
                    class="flex-1 min-w-[200px] border border-gray-300 rounded-lg py-2 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:border-transparent"
                    style="focus-ring-color: var(--primary-color-light);">

                <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                    <i class="fas fa-search"></i>
                </button>

                {{-- زر مسح البحث (يظهر فقط إذا كان هناك معيار بحث) --}}
                @if (request()->has('q') && request('q') != '')
                    <a href="{{ route('admin.news.index') }}"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
        
        {{-- ================================================= --}}
        {{-- 3. جدول إدارة الأخبار --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                {{-- رأس الجدول --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الصورة</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">العنوان (عربي)</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase tracking-wider">الخلاصة (عربي)</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                {{-- جسم الجدول --}}
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($news as $item)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            {{-- الصورة --}}
                            <td class="px-6 py-3 text-right whitespace-nowrap">
                                @if($item->image)
                                    <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->title_ar }}"
                                        class="w-16 h-16 object-cover rounded-lg shadow-md mx-auto"
                                        onerror="this.onerror=null; this.src='https://placehold.co/64x64/E5E7EB/4B5563?text=N/A';"
                                        style="min-width: 64px; min-height: 64px;">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-600">
                                        لا صورة
                                    </div>
                                @endif
                            </td>
                            {{-- العنوان --}}
                            <td class="px-6 py-3 text-right text-sm font-semibold text-gray-900">
                                {{ $item->title_ar }}
                            </td>
                            {{-- الخلاصة --}}
                            <td class="px-6 py-3 text-right text-sm text-gray-600 max-w-xs truncate">
                                {{ Str::limit($item->summary_ar, 100) }}
                            </td>
                            {{-- الحالة (مفتاح التبديل) --}}
                            <td class="px-6 py-3 text-center whitespace-nowrap">
                                <form action="{{ route('admin.news.toggle-status', $item) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="switch mx-auto">
                                        <input type="checkbox" name="status_toggle" onchange="this.form.submit()" {{ $item->status ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </form>
                            </td>
                            {{-- الإجراءات --}}
                            <td class="px-6 py-3 text-center whitespace-nowrap text-sm space-x-3 space-x-reverse">
                                @can('edit_news')
                                    <a href="{{ route('admin.news.edit', $item->id) }}"
                                        class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endcan

                                @can('delete_news')
                                    {{-- ✅ تم التعديل: استبدال النموذج بالزر الذي يشغل المودال المركزي --}}
                                    <button type="button" 
                                        class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger" 
                                        data-action="{{ route('admin.news.destroy', $item->id) }}" 
                                        data-title="{{ $item->title_ar }}" 
                                        title="حذف">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-5 whitespace-nowrap text-center text-sm text-gray-500 bg-white">
                                لا توجد أخبار مُضافة حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ================================================= --}}
        {{-- 4. روابط التنقل (Pagination) --}}
        {{-- ================================================= --}}
        <div class="mt-6">
            {{ $news->links() }}
        </div>
    </div>
@endsection