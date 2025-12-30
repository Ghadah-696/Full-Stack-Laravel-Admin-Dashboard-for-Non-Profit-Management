@extends('layouts.admin')
@section('title', 'إدارة التصنيفات')

{{-- يفترض أن layout/admin.blade.php يحتوي على Alpine.js ومكون Delete Modal --}}
@section('content')
    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-tags ml-2" style="color: var(--primary-color);"></i> إدارة تصنيفات الأخبار
            </h1>
            @can('create_category')
                <a href="{{ route('admin.categories.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إضافة تصنيف جديد
                </a>
            @endcan
        </div>

        {{-- ================================================= --}}
        {{-- 3. جدول البيانات --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-sm font-semibold">
                            <th class="py-3 px-6 text-right">#</th>
                            <th class="py-3 px-6 text-right">الاسم (عربي)</th>
                            <th class="py-3 px-6 text-right">الاسم (إنجليزي)</th>
                            <th class="py-3 px-6 text-center">عدد الأخبار</th>
                            <th class="py-3 px-6 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse($categories as $index => $category)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-300">
                                <td class="py-3 px-6 text-right whitespace-nowrap font-semibold text-gray-800">
                                    {{ $categories->firstItem() + $index }}
                                </td>
                                <td class="py-3 px-6 text-right whitespace-nowrap">
                                    {{ $category->name_ar }}
                                </td>
                                <td class="py-3 px-6 text-left whitespace-nowrap ltr:text-left rtl:text-right">
                                    {{ $category->name_en }}
                                </td>
                                {{-- افترض أن لديك علاقة 'news' على نموذج Category --}}
                                <td class="py-3 px-6 text-center whitespace-nowrap">
                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ $category->news_count ?? 'N/A' }}
                                    </span>
                                </td>

                                {{-- الإجراءات --}}
                                <td class="px-6 py-3 text-center whitespace-nowrap text-sm space-x-3 space-x-reverse">
                                    <!-- <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse"> -->

                                    {{-- زر التعديل --}}
                                    @can('edit_category')
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" title="تعديل"
                                            class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    {{-- زر الحذف (يطلق حدث Alpine.js لفتح النافذة المنبثقة) --}}
                                    @can('delete_category')
                                        <button type="button" title="حذف"
                                            data-action="{{ route('admin.categories.destroy', $category->id) }}"
                                            data-title="{{ $category->name_ar }}"
                                            class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                    <!-- </div> -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 px-6 text-center text-lg text-gray-500">
                                    <i class="fas fa-box-open text-3xl mb-3"></i>
                                    <p>لا توجد تصنيفات مسجلة حتى الآن.</p>
                                    @can('create_category')
                                        <a href="{{ route('admin.categories.create') }}"
                                            class="text-blue-600 hover:underline mt-2 inline-block">ابدأ بإضافة أول تصنيف.</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- التصفح (Pagination) --}}
            @if ($categories->hasPages())
                <div class="p-4 border-t bg-gray-50">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection