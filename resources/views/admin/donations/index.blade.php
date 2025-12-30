@extends('layouts.admin')

@section('page_title', 'إدارة التبرعات')

@section('content')
    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان (Donations List) --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-hand-holding-usd ml-2" style="color: var(--primary-color);"></i>
                إدارة التبرعات
            </h1>
            {{-- زر الإضافة (عادةً لا نحتاج زر إضافة يدوي في شاشة التبرعات، ولكن يمكن إضافته إذا لزم الأمر) --}}
            {{-- @can('create_donation')
            <a href="{{ route('admin.donations.create') }}"
                class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                style="background-color: var(--primary-color);">
                <i class="fas fa-plus ml-2"></i> إضافة تبرع يدوي
            </a>
            @endcan --}}
        </div>

        {{-- ================================================= --}}
        {{-- 2. جدول البيانات --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl overflow-hidden">
            @if ($donations->isEmpty())
                <div class="py-10 px-6 text-center text-lg text-gray-500">
                    <i class="fas fa-box-open text-3xl mb-3"></i>
                    <p>لا توجد تبرعات مسجلة حتى الآن.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-sm font-semibold">
                                <th class="py-3 px-6 text-right">#</th>
                                <th class="py-3 px-6 text-right">المتبرع/المستخدِم</th>
                                <th class="py-3 px-6 text-right">المبلغ</th>
                                <th class="py-3 px-6 text-right">طريقة الدفع</th>
                                <th class="py-3 px-6 text-center">الحالة</th>
                                <th class="py-3 px-6 text-right hidden md:table-cell">أنشئ بواسطة</th>
                                <th class="py-3 px-6 text-right">تاريخ التبرع</th>
                                <th class="py-3 px-6 text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700 text-sm font-light">
                            @foreach ($donations as $index => $donation)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-300">
                                    {{-- # --}}
                                    <td class="py-4 px-6 text-right whitespace-nowrap font-semibold text-gray-800">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- المتبرع/المستخدِم --}}
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        @if($donation->user)
                                            <div class="font-semibold text-blue-600">{{ $donation->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $donation->user->email }}</div>
                                        @else
                                            <span class="font-semibold">{{ $donation->donor_name ?? 'ضيف/مجهول' }}</span>
                                            <span class="text-xs text-gray-500 block">غير مسجل</span>
                                        @endif
                                    </td>

                                    {{-- المبلغ --}}
                                    <td class="py-4 px-6 text-right whitespace-nowrap text-lg font-bold text-green-700">
                                        {{ number_format($donation->amount, 2) }} {{ $donation->currency }}
                                    </td>

                                    {{-- طريقة الدفع --}}
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        {{ $donation->payment_method }}
                                    </td>

                                    {{-- الحالة (Tailwind Badge) --}}
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        @php
                                            $statusClass = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'refunded' => 'bg-blue-100 text-blue-800',
                                            ][$donation->status] ?? 'bg-gray-100 text-gray-800';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            <i class="fas fa-circle text-xs mr-2 rtl:ml-2"></i>
                                            {{ $donation->status }}
                                        </span>
                                    </td>

                                    {{-- أنشئ بواسطة --}}
                                    <td class="py-4 px-6 text-right whitespace-nowrap hidden md:table-cell">
                                        <div class="text-gray-700">{{ $donation->creator->name ?? 'نظام/غير متاح' }}</div>
                                        <div class="text-xs text-gray-500">{{ $donation->creator->email ?? '' }}</div>
                                    </td>

                                    {{-- تاريخ التبرع --}}
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        {{ $donation->created_at->format('Y-m-d') }}
                                    </td>

                                    {{-- الإجراءات --}}
                                    <td class="px-6 py-3 text-center whitespace-nowrap text-sm space-x-3 space-x-reverse">
                                        <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                            {{-- زر العرض --}}
                                            @can('view_donation')
                                                <a href="{{ route('admin.donations.show', $donation) }}" title="عرض التفاصيل"
                                                    class="text-green-600 hover:text-green-800 transition duration-150 p-2 rounded-full hover:bg-green-50">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan

                                            {{-- زر الحذف (يمكن إضافته لاحقاً إذا كان مطلوباً حذف التبرعات) --}}
                                            {{-- @can('delete_donation')
                                            <button type="button"
                                                class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger"
                                                data-action="{{ route('admin.donations.destroy', $donation->id) }}"
                                                data-title="التبرع رقم {{ $donation->id }}" title="حذف">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                            @endcan --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ترقيم الصفحات --}}
                <div class="p-4 border-t bg-gray-50">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>
    </div>


@endsection