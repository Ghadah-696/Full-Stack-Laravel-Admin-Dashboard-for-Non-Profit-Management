@extends('layouts.admin')
@section('page_title', 'إدارة صلاحيات النظام (الأدوار)')

@section('content')
    {{-- ملاحظة هامة:
    يجب أن يتم تمرير متغير $roles من الـ Controller ليتمكن @forelse من عرضه --}}
    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div class="flex items-center">

                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                        <i class="fas fa-users-cog  ml-3" style="color: var(--primary-color);"></i>
                        إدارة الأدوار والصلاحيات
                    </h1>
                    <p class="text-gray-600 mt-1">تعريف الأدوار وتوزيع الصلاحيات على وحدات المنظمة الخيرية.</p>
                </div>
            </div>

            {{-- زر إنشاء دور جديد --}}
            @can('create_roles')
                <a href="{{ route('admin.roles.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i>
                    <span>إنشاء دور جديد</span>
                </a>
            @endcan
        </div>

        {{-- ================================================= --}}
        {{-- 3. جدول البيانات --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 uppercase text-sm font-semibold">
                            <th class="py-3 px-6 text-center w-12">#</th>
                            <th class="py-3 px-6 text-right w-1/4">اسم الدور</th>
                            <th class="py-3 px-6 text-right w-1/3">ملاحظة توضيحية للمشرفين</th>
                            <th class="py-3 px-6 text-center w-20">Guard</th>
                            <th class="py-3 px-6 text-center w-20">عدد المستخدمين</th>
                            <th class="py-3 px-6 text-center w-1/5">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light divide-y divide-gray-100">

                        {{-- هنا يتم استخدام المتغير \$roles الذي يجب تمريره من الـ Controller --}}
                        @forelse($roles as $role)
                            <tr class="hover:bg-gray-50 transition duration-300">
                                {{-- رقم التسلسل --}}
                                <td class="py-4 px-6 text-center whitespace-nowrap font-semibold text-gray-800">
                                    {{ $loop->iteration }}
                                </td>

                                {{-- اسم الدور --}}
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    <span class="font-medium text-lg">{{ $role->name }}</span>
                                    {{-- افتراض أن الحقل slug موجود لعرض الاسم البرمجي --}}
                                    <div class="text-xs text-gray-400 mt-1">البرمجي: {{ $role->slug }}</div>

                                    {{-- محاكاة لإشارة الدور النظامي (Super Admin / Registered Donor) --}}
                                    @if(in_array($role->slug, ['super_admin', 'registered_donor']))
                                        <span
                                            class="mr-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">نظامي</span>
                                    @endif
                                </td>

                                {{-- الملاحظة التوضيحية (الوصف) --}}
                                {{-- افتراض أن حقل الوصف هو description --}}
                                <td class="py-4 px-6 text-right text-gray-500 max-w-sm truncate"
                                    title="{{ $role->description ?? 'لا يوجد وصف.' }}">
                                    {{ $role->description ?? 'لا يوجد وصف.' }}
                                </td>

                                {{-- Guard Name --}}
                                {{-- افتراض أن الحقل guard_name موجود --}}
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    @if(($role->guard_name ?? 'web') == 'web')
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800">
                                            لوحة التحكم
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-purple-100 text-purple-800">
                                            واجهة أمامية (API)
                                        </span>
                                    @endif
                                </td>

                                {{-- عدد المستخدمين --}}
                                {{-- افتراض أن الحقل users_count موجود --}}
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                                                                                                                            {{ ($role->users_count ?? 0) > 0 ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $role->users_count ?? 0 }} مستخدم
                                    </span>
                                </td>

                                {{-- الإجراءات --}}
                                <td class="px-6 py-3 text-center whitespace-nowrap text-sm">
                                    <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">

                                        {{-- زر الصلاحيات --}}
                                        <a href="{{ route('admin.roles.permissions', $role->id) }}" title="تعديل الصلاحيات"
                                            class="text-green-600 hover:text-green-800 transition duration-150 p-2 rounded-full hover:bg-green-50">
                                            <i class="fas fa-user-shield"></i>
                                        </a>

                                        {{-- زر التعديل --}}
                                        @can('edit_roles')
                                            {{-- لا يمكن تعديل الأدوار النظامية --}}
                                            @if(!in_array($role->slug, ['super_admin', 'registered_donor']))
                                                <a href="{{ route('admin.roles.edit', $role->id) }}" title="تعديل"
                                                    class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <i class="fas fa-lock text-gray-300 p-2" title="لا يمكن تعديل دور نظامي"></i>
                                            @endif
                                        @endcan

                                        {{-- زر الحذف --}}
                                        @can('delete_roles')
                                            {{-- لا يمكن حذف الأدوار النظامية --}}
                                            @if(!in_array($role->slug, ['super_admin', 'registered_donor']))
                                                <button type="button"
                                                    class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger"
                                                    data-action="{{ route('admin.roles.destroy', $role->id) }}"
                                                    data-title="{{ $role->name }}" title="حذف">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            @else
                                                <i class="fas fa-ban text-gray-300 p-2" title="لا يمكن حذف دور نظامي"></i>
                                            @endif
                                        @endcan
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-6 text-center text-lg text-gray-500">
                                    <i class="fas fa-key text-4xl mb-3 block"></i>
                                    <p class="font-bold">لا توجد أدوار مسجلة حتى الآن.</p>
                                    @can('create_roles')
                                        <a href="{{ route('admin.roles.create') }}"
                                            class="text-blue-600 hover:underline mt-4 inline-block font-medium">ابدأ بإنشاء أول دور
                                            لديك.</a>
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