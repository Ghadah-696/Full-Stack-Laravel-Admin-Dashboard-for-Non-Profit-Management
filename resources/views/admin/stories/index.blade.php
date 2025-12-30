@extends('layouts.admin') {{-- تأكد من اسم ملف الـ layout الصحيح --}}
@section('page_title', 'إدارة قصص النجاح والشهادات')

@section('content')
    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-book-open" style="color: var(--primary-color);"></i> إدارة
                قصص النجاح والشهادات
            </h1>
            @can('create_story')
                <a href="{{ route('admin.stories.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إضافة قصة جديدة
                </a>
            @endcan
        </div>
        {{-- ================================================= --}}
        {{-- 3. جدول البيانات --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl overflow-hidden">
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-sm font-semibold">
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                #
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                صورة المستفيد
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                اسم المستفيد (المنصب)
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                مقتطف من القصة
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                الترتيب
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                الحالة
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stories as $story)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($story->image)
                                        <img src="{{ asset('images/stories/' . $story->image) }}" alt="{{ $story->name_ar }} Image"
                                            class="w-16 h-16 object-cover rounded-full">
                                    @else
                                        <span class="text-gray-400">لا يوجد</span>
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="font-semibold">{{ $story->name_ar }}</p>
                                    <p class="text-xs text-gray-500">{{ $story->title_ar ?? 'بدون منصب' }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ Str::limit($story->content_ar, 50) }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $story->order }}
                                </td>
                                {{-- الحالة (Toggle Switch) --}}
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <form action="{{ route('admin.stories.toggle-status', $story) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="switch">
                                            <input type="checkbox" name="status_toggle" onchange="this.form.submit()" {{ $story->status ? 'checked' : '' }}>

                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </td>
                                <!-- <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">

                                                            <form action="{{ route('admin.stories.update', $story) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="hidden" name="status" value="{{ $story->status ? 0 : 1 }}">

                                                                <label class="switch">
                                                                    <input type="checkbox" name="status_toggle" onchange="this.form.submit()" {{ $story->status ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </form>
                                                        </td> -->

                                {{-- الإجراءات --}}
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm space-x-2 space-x-reverse">
                                    <!-- <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse"> -->
                                    {{-- زر التعديل --}}
                                    @can('edit_story')
                                        <a href="{{ route('admin.stories.edit', $story) }}" title="تعديل"
                                            class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    {{-- زر الحذف (يطلق حدث Alpine.js) --}}
                                    @can('delete_story')
                                        <button type="button"
                                            class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger"
                                            data-action="{{ route('admin.stories.destroy', $story->id) }}"
                                            data-title="{{ $story->title_ar }}" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                    <!-- </div> -->
                                </td>

                                <!-- <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm space-x-2 space-x-reverse">
                                                                                    @can('edit_story')
                                                                                        <a href="{{ route('admin.stories.edit', $story) }}"
                                                                                            class="text-blue-600 hover:text-blue-900">
                                                                                            تعديل
                                                                                        </a>
                                                                                    @endcan

                                                                                    @can('delete_story')
                                                                                        <form action="{{ route('admin.stories.destroy', $story->id) }}" method="POST"
                                                                                            class="inline-block">
                                                                                            @csrf
                                                                                            @method('DELETE')
                                                                                            <button type="submit" class="text-red-600 hover:text-red-900 mx-1 delete-btn">
                                                                                                حذف
                                                                                            </button>
                                                                                        </form>
                                                                                    @endcan
                                                                                </td> -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $stories->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection