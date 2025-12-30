@extends('layouts.admin') {{-- تأكد من اسم ملف الـ layout الصحيح --}}
@section('page_title', 'إدارة الوثائق والتقارير')

@section('content')

    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-folder-open" style="color: var(--primary-color);"></i> إدارة
                الوثائق والتقارير
            </h1>
            @can('create_document')
                <a href="{{ route('admin.documents.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> رفع وثيقة جديدة
                </a>
            @endcan
        </div>

        <div class="card bg-white shadow-xl rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-sm font-semibold">
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                العنوان (عربي)
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                النوع / السنة
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                التحميل والمسار
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="font-semibold">{{ $doc->title_ar }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($doc->description_ar, 40) }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                        {{ $doc->type }}
                                    </span>
                                    <p class="text-sm mt-1">السنة: {{ $doc->year }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($doc->file_path)
                                        <a href="{{ asset('documents/' . $doc->file_path) }}" target="_blank"
                                            class="text-green-600 hover:text-green-900 font-medium flex items-center">
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            تحميل ({{ pathinfo($doc->file_path, PATHINFO_EXTENSION) }})
                                        </a>
                                    @else
                                        <span class="text-red-500">الملف مفقود</span>
                                    @endif
                                </td>
                                <!-- الحالة -->
                                <td>
                                    <form action="{{ route('admin.documents.toggle-status', $doc) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="switch">
                                            <input type="checkbox" name="status_toggle" onchange="this.form.submit()" {{ $doc->status ? 'checked' : '' }}>

                                            <span class="slider round"></span>
                                        </label>

                                    </form>
                                </td>
                                {{-- الإجراءات --}}
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <!-- <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse"> -->
                                    {{-- زر التعديل --}}
                                    @can('edit_document')
                                        <a href="{{ route('admin.documents.edit', $doc) }}" title="تعديل"
                                            class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    {{-- زر الحذف (يطلق حدث Alpine.js) --}}
                                    @can('delete_document')
                                        <button type="button"
                                            class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger"
                                            data-action="{{ route('admin.documents.destroy', $doc->id) }}"
                                            data-title="{{ $doc->title_ar }}" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                    <!-- </div> -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-6 text-center text-lg text-gray-500">
                                    <i class="fas fa-box-open text-3xl mb-3"></i>
                                    <p>لا يوجد تقرير مسجل حتى الآن.</p>
                                    @can('create_documents')
                                        <a href="{{ route('admin.documentss.create') }}"
                                            class="text-blue-600 hover:underline mt-2 inline-block">ابدأ بإضافة أول تقرير.</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if ($documents->hasPages())
                    <div class="p-4">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection