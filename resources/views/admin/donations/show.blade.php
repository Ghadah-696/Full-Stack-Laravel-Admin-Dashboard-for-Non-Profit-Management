@extends('layouts.admin')
@section('page_title', 'تفاصيل التبرع: ' . $donation->id)
<!-- @section('title', 'تفاصيل التبرع: ' . $donation->id) -->

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">تفاصيل التبرع رقم: {{ $donation->id }}</h1>
            <a href="{{ route('admin.donations.index') }}"
                class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-arrow-right fa-sm text-white-50"></i> العودة إلى القائمة
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">المعلومات الأساسية للتبرع</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">المبلغ والعملة</th>
                            <td>**{{ number_format($donation->amount, 2) }} {{ $donation->currency }}**</td>
                        </tr>
                        <tr>
                            <th>حالة التبرع</th>
                            <td>
                                @php
                                    $statusClass = $donation->status === 'completed' ? 'badge-success' : ($donation->status === 'pending' ? 'badge-warning' : 'badge-danger');
                                @endphp
                                <span class="badge {{ $statusClass }} p-2">
                                    {{ $donation->status }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>طريقة الدفع</th>
                            <td>{{ $donation->payment_method }}</td>
                        </tr>
                        <tr>
                            <th>اسم المتبرع (للضيف)</th>
                            <td>{{ $donation->donor_name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <th>المستخدم المسجل (إذا وجد)</th>
                            {{-- يجب تحديث المتحكم لضمان جلب علاقة user --}}
                            <td>{{ $donation->user->name ?? 'غير مسجل' }} (ID: {{ $donation->user_id ?? 'N/A' }})</td>
                        </tr>
                        <tr>
                            <th>تاريخ ووقت الإنشاء</th>
                            <td>{{ $donation->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>آخر تحديث</th>
                            <td>{{ $donation->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-info">تتبع التدقيق والإدارة</h6>
            </div>
            <div class="card-body">
                {{-- للتأكد من عرض هذه المعلومات، يجب تحديث المتحكم لجلب علاقات creator و updater --}}
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 25%;">أنشئ بواسطة</th>
                            <td>{{ $donation->creator->name ?? 'نظام آلي/غير متاح' }} (ID:
                                {{ $donation->created_by ?? 'N/A' }})
                            </td>
                        </tr>
                        <tr>
                            <th>عدّل بواسطة</th>
                            <td>{{ $donation->updater->name ?? 'نظام آلي/غير متاح' }} (ID:
                                {{ $donation->updated_by ?? 'N/A' }})
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



<!-- 
@extends('layouts.admin') {{-- تأكد من اسم ملف الـ layout الصحيح --}}

@section('content')

    <h2>تفاصيل التبرع رقم: {{ $donation->id }}</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>اسم المتبرع:</strong> {{ $donation->donor_name }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $donation->donor_email ?? 'غير متوفر' }}</p>
            <p><strong>المبلغ:</strong> {{ $donation->amount }} {{ $donation->currency }}</p>
            <p><strong>طريقة الدفع:</strong> {{ $donation->payment_method }}</p>
            <p><strong>تاريخ التبرع:</strong> {{ $donation->created_at->format('Y-m-d H:i') }}</p>

            <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary mt-3">العودة للقائمة</a>
        </div>
    </div>

@endsection -->