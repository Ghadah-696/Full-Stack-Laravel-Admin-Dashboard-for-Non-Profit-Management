@extends('layouts.admin')
@section('page_title', 'إدارة السلايدر')

@section('content')
    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-sliders-h ml-3 " style="color: var(--primary-color);"></i> إدارة
                السلايدر
            </h1>
            @can('create_slider')
                <a href="{{ route('admin.sliders.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إضافة سلايدر جديد
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
                            <th class="py-3 px-6 text-center">الصورة</th>
                            <th class="py-3 px-6 text-right">العنوان (عربي)</th>
                            <th class="py-3 px-6 text-right max-w-xs">الترتيب</th>
                            <th class="py-3 px-6 text-center">الحالة</th>
                            <th class="py-3 px-6 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse ($sliders as $index => $slider)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-300">
                                <!-- <td class="py-4 px-6 text-right whitespace-nowrap font-semibold text-gray-800">
                                                                                                                    {{ $sliders->firstItem() + $index }}
                                                                                                                </td> -->
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    {{ $loop->iteration }}
                                </td>
                                {{-- الصورة --}}
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    <img src="{{ asset('images/' . $slider->image) }}" alt="{{ $slider->title_ar }}"
                                        class="w-16 h-16 object-cover rounded-lg shadow-md mx-auto">
                                </td>
                                {{-- العنوان --}}
                                <td class="py-4 px-6 text-right whitespace-nowrap ">
                                    {{ $slider->title_ar ?? 'لا يوجد عنوان' }}
                                </td>
                                {{-- الترتيب --}}
                                <td class="py-4 px-6 text-right max-w-xs text-wrap break-words">
                                    {{ $slider->order }}
                                </td>
                                {{-- الحالة (Toggle Switch) --}}
                                <td class="py-4 px-6 text-center">
                                    <form action="{{ route('admin.sliders.toggle-status', $slider) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="switch">
                                            <input type="checkbox" name="status_toggle" onchange="this.form.submit()" {{ $slider->status ? 'checked' : '' }}>

                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </td>
                                {{-- الإجراءات --}}
                                <td class="px-6 py-3 text-center whitespace-nowrap text-sm space-x-3 space-x-reverse">
                                    <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                        {{-- زر التعديل --}}
                                        @can('edit_slider')
                                            <a href="{{ route('admin.sliders.edit', $slider->id) }}" title="تعديل"
                                                class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        {{-- زر الحذف (يطلق حدث Alpine.js) --}}
                                        @can('delete_slider')
                                            <button type="button"
                                                class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger"
                                                data-action="{{ route('admin.sliders.destroy', $slider->id) }}"
                                                data-title="{{ $slider->title_ar }}" title="حذف">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-6 text-center text-lg text-gray-500">
                                    <i class="fas fa-box-open text-3xl mb-3"></i>
                                    <p>لا يوجد سلايدر مسجل حتى الآن.</p>
                                    @can('create_slider')
                                        <a href="{{ route('admin.sliders.create') }}"
                                            class="text-blue-600 hover:underline mt-2 inline-block">ابدأ بإضافة أول سلايدر.</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection