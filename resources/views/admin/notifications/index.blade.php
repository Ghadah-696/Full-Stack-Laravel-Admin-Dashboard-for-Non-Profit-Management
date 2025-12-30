@extends('layouts.admin')@section('page_title', 'جميع الإشعارات')@section('content')<div class="container mx-auto px-4"><h1 class="text-3xl font-bold mb-8 text-gray-800">مركز الإشعارات</h1><div class="bg-white rounded-xl shadow-lg overflow-hidden p-6">
    
    @forelse ($notifications as $notification)
        @php
            // تحديد الكلاسات بناءً على حالة الإشعار
            $isRead = !is_null($notification->read_at);
            $bgColor = $isRead ? 'bg-gray-50' : 'bg-indigo-50/50';
            $textColor = $isRead ? 'text-gray-600' : 'text-gray-900';
        @endphp

        <div class="{{ $bgColor }} p-4 rounded-lg mb-3 border border-gray-100 hover:shadow-sm transition duration-150">
            <div class="flex justify-between items-start">
                
                <div class="flex items-start">
                    <!-- أيقونة التنبيه -->
                    <i class="fas fa-info-circle text-xl {{ $isRead ? 'text-gray-400' : 'text-indigo-600' }} ml-4 mt-1"></i>
                    
                    <!-- المحتوى -->
                    <div>
                        <p class="text-sm font-semibold {{ $textColor }}">
                            {{ $notification->data['message'] ?? 'إشعار نظام' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="far fa-clock ml-1"></i>
                            منذ {{ $notification->created_at->diffForHumans() }}
                        </p>
                        
                        @if ($notification->data['link'] ?? null)
                            <!-- زر الذهاب للحدث إذا كان موجوداً -->
                            <a href="{{ $notification->data['link'] }}" 
                               class="text-xs text-indigo-500 hover:text-indigo-700 font-medium mt-2 inline-block transition duration-150">
                                <i class="fas fa-arrow-left ml-1"></i> عرض التفاصيل
                            </a>
                        @endif
                    </div>
                </div>
                
                @if (!$isRead)
                    <span class="text-xs text-indigo-600 bg-indigo-100 px-2 py-0.5 rounded-full font-medium self-start">جديد</span>
                @endif
            </div>
        </div>

    @empty
        <div class="text-center py-10 text-gray-500">
            <i class="fas fa-inbox text-5xl mb-3"></i>
            <p class="text-lg">لا توجد إشعارات حاليًا.</p>
        </div>
    @endforelse

    <!-- روابط التصفح -->
    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
</div>@endsection