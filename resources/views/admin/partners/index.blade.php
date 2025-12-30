@extends('layouts.admin') {{-- تأكد من اسم ملف الـ layout الصحيح --}}
@section('page_title', 'إدارة الشركاء والرعاة')
@section('content')

    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-handshake" style="color: var(--primary-color);"></i> إدارة
                الشركاء والرعاة
            </h1>
            @can('create_partner')
                <a href="{{ route('admin.partners.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إضافة شريك جديد
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
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-sm font-semibold">
                            <!-- <th class="py-3 px-6 text-center w-12">#</th> -->
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                الشعار
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                بيانات الشريك
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200  text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                الرابط والنوع
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
                        @foreach ($partners as $partner)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    @if ($partner->logo_path)
                                        {{-- عرض الشعار بحجم صغير --}}
                                        <img src="{{ asset('partners/' . $partner->logo_path) }}" alt="{{ $partner->name_ar }} Logo"
                                            class="w-16 h-16 object-contain rounded-lg border p-1">
                                    @else
                                        <span class="text-red-500">لا يوجد شعار</span>
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="font-semibold">{{ $partner->name_ar }}</p>
                                    <p class="text-xs text-gray-500">{{ $partner->name_en }}</p>
                                </td>

                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">

                                    {{-- 💡 منطق تحديد اللون باستخدام @if و @elseif مباشرة --}}
                                    @php
                                        $type = $partner->type;
                                        $colorClasses = 'bg-gray-200 text-gray-800'; // القيمة الافتراضية
                                    @endphp

                                    @if ($type == 'راعي ماسي')
                                        @php $colorClasses = 'bg-blue-600 text-white'; @endphp
                                    @elseif ($type == 'شريك استراتيجي')
                                        @php $colorClasses = 'bg-cyan-100 text-cyan-800'; @endphp
                                    @elseif ($type == 'شريك دعم')
                                        @php $colorClasses = 'bg-amber-100 text-amber-800'; @endphp
                                    @endif

                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClasses }}">
                                        {{ $partner->type }}
                                    </span>

                                    {{-- باقي كود عرض الرابط --}}
                                    @if ($partner->website_url)
                                        <a href="{{ $partner->website_url }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-900 font-medium text-sm block mt-1">
                                            زيارة الموقع
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.partners.toggle-status', $partner) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="switch">
                                            <input type="checkbox" name="status_toggle" onchange="this.form.submit()" {{ $partner->status ? 'checked' : '' }}>

                                            <span class="slider round"></span>
                                        </label>

                                    </form>
                                </td>

                                {{-- الإجراءات --}}
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <!-- <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse"> -->
                                    {{-- زر التعديل --}}
                                    @can('edit_partner')
                                        <a href="{{ route('admin.partners.edit', $partner) }}" title="تعديل"
                                            class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan

                                    {{-- زر الحذف (يطلق حدث Alpine.js) --}}
                                    @can('delete_partner')
                                        <button type="button"
                                            class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger"
                                            data-action="{{ route('admin.partners.destroy', $partner->id) }}"
                                            data-title="{{ $partner->title_ar }}" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                    <!-- </div> -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($partners->hasPages())
                    <div class="p-4">
                        {{ $partners->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection