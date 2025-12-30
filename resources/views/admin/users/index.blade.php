@extends('layouts.admin')
@section('page_title', 'إدارة المستخدمين')


@section('content')
    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-users" style="color: var(--primary-color);"></i> إدارة
                المستخدمين
            </h1>
            @can('create_document')
                <a href="{{ route('admin.users.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إضافة مستخدم جديد
                </a>
            @endcan
        </div>


        <div class="card bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-sm font-semibold">
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">
                                الاسم
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">
                                البريد الإلكتروني
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">
                                الدور
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-5 py-5 border-b-2 border-gray-200 bg-white font-semibold text-xs text-right">
                                    {{ $user->name }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    {{ $user->email }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                    {{-- 💡 (نحتاج لتطبيق نظام الأدوار لاحقاً لعرض الدور الحقيقي) --}}
                                    @if ($user->id === 1)
                                        <span class="text-red-600 font-semibold">مسؤول رئيسي</span>
                                    @else
                                        مستخدم
                                    @endif
                                </td>
                                {{-- الإجراءات --}}
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <!-- <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse"> -->
                                    {{-- زر التعديل --}}
                                    @can('edit_user')
                                        <a href="{{ route('admin.users.edit', $user->id) }}" title="تعديل"
                                            class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    {{-- زر الحذف (يطلق حدث Alpine.js) --}}
                                    @can('delete_user')
                                        <button type="button"
                                            class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger"
                                            data-action="{{ route('admin.users.destroy', $user->id) }}"
                                            data-title="{{ $user->title_ar }}" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection