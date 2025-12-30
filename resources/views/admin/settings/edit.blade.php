@extends('layouts.admin')

@section('page_title', 'الإعدادات العامة')

@section('content')
<style>
/ تصميم النبض للنجاح */
@keyframes pulse-custom {
0%, 100% { opacity: 1; }
50% { opacity: .5; }
}
.animate-pulse-custom {
animation: pulse-custom 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* تطبيق لون الزر الأساسي على زر حفظ الإعدادات */
.btn {
    background-color: var(--primary-color);
    transition: background-color 0.3s;
}
.btn:hover {
    background-color: var(--secondary-color) !important;
}


</style>

<div class="container mx-auto p-4 md:p-10">

{{-- ================================================= --}}
{{-- 1. العنوان والإشعارات --}}
{{-- ================================================= --}}
<div class="flex justify-between items-start mb-8 gap-4">
    <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
        <i class="fas fa-cog ml-2" style="color: var(--primary-color);"></i>
        الإعدادات العامة للنظام
    </h1>
</div>

   <!-- ============================================= -->
        <!-- 7. مراقبة النسخ الاحتياطي التلقائي (القسم المطلوب) -->
        <!-- ============================================= -->
         <h3 class="text-xl font-bold mb-4 border-b pb-2 text-primary-color mt-8"> حالة النسخ الاحتياطي التلقائي</h3>

        <div id="backup-monitor" class="p-6 border border-gray-200 rounded-xl mb-8 shadow-inner bg-gray-50">
            
            <!-- معلومات الجدولة (تظهر دائماً) -->
            <div class="flex justify-between items-right mb-4 pb-4 border-b">
                <p class="text-sm font-semibold text-gray-700">
                    <i class="far fa-calendar-alt ml-2 text-blue-500"></i>
                    النسخة الاحتياطية المجدولة التلقائية: 
                    <span class="text-blue-600">اليوم الساعة 10:00 مساءً</span>
                </p>
                <!-- زر التشغيل اليدوي (للتجربة) -->
                <button id="run-backup-btn" 
                        class="bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-150 shadow-md flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-play mr-2"></i>
                    تشغيل النسخ الآن (تجربة)
                </button>
            </div>
            
            <!-- شريط الحالة الرئيسي (يتم تحديثه بالجافاسكريبت) -->
            <div id="status-bar" class="p-4 rounded-lg flex items-right justify-start font-semibold text-white shadow-md bg-green-300">
                 <i id="status-icon" class="fas fa-check-circle text-xl ml-2"></i>
                <span id="status-message" class="block  text-sm font-medium text-gray-700 text-lg ml-3">تم بنجاح: آخر نسخة احتياطية تمت اليوم 10:00ص.</span>
               
            </div>

            <!-- شريط التقدم (يظهر فقط في حالة الجاري) -->
            <div id="progress-container" class="mt-3 h-2 bg-gray-200 rounded-full overflow-hidden hidden">
                <div id="progress-bar" class="h-full bg-yellow-500 rounded-full" style="width: 0%;"></div>
            </div>

            <!-- تفاصيل النسخة الاحتياطية (تظهر عند النجاح) -->
            <div id="backup-details" class="mt-6 p-4  bg-white border border-gray-100 rounded-lg shadow-sm">
                <p class="text-xs font-medium text-gray-500 mb-2">تفاصيل آخر نسخة احتياطية:</p>
                <div class="grid grid-cols-1 justify-between md:grid-cols-3 gap-4">
                    
                    <!-- الوقت -->
                    <div class="flex items-center justify-right">
                        <i class="far fa-clock text-blue-500"></i>
                         <p class="text-xs font-medium text-gray-500 mb-1">وقت النسخ الاحتياطي:</p>
                        <span id="backup-time" class="text-sm font-bold text-gray-800 mr-2">10:00:00م</span>
                        
                    </div>
                    
                    <!-- الحجم -->
                    <div class="flex items-center justify-right">
                        <i class="fas fa-database text-blue-500"></i>
                        <p class="text-xs font-medium text-gray-500 mb-1">حجم الملف:</p>
                        <span id="backup-size" class="text-sm font-bold text-gray-800 mr-2">450 MB</span>
                        
                    </div>

                    <!-- رابط الملف (المعدل) -->
                        <div class="flex items-center justify-start">
                            <i class="fas fa-download text-indigo-500 ml-2"></i>
                            <p class="text-xs font-medium text-gray-500 ml-1">ملف النسخ الاحتياطي:</p>
                            <!-- هذا هو الرابط الذي تم تعديله ليستخدم المسار النسبي المطلوب -->
                            <a id="backup-file-link" 
                               href="storage/app/backup/charity_2025-12-11-18-29-50.zip" 
                               target="_blank" 
                               class="text-sm font-bold text-blue-600 hover:text-blue-800 transition duration-150 truncate">
                                <span id="backup-file-name">database-2025-12-13.zip</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-xs mt-2 text-gray-500 text-right">
                ملاحظة: رابط التحميل يشير إلى مسار نسبي ضمن بيئة الخادم (`storage/app/backup`) لأغراض العرض التوضيحي وضمان بدء التحميل عند النقر.
            </p>
        </div>
        <!-- نهاية قسم مراقبة النسخ الاحتياطي -->

    <!-- -----------اااا ------------------ -->
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
    class="card bg-white shadow-xl rounded-xl p-8">
    @csrf
    @method('PUT')


    {{-- ============================================= --}}
    {{-- 1. معلومات التواصل الأساسية (تم التحديث) --}}
    {{-- ============================================= --}}
    <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700 mt-8" style="color: var(--primary-color);">1. معلومات التواصل</h3>

   
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- البريد الإلكتروني --}}
        <div class="form-group">
            <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني الأساسي</label>
            <input type="email" name="email" id="email"
                value="{{ old('email', $setting->email ?? '') }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="example@domain.com">
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- رقم الهاتف --}}
        <div class="form-group">
            <label for="phone_number" class="block text-sm font-medium text-gray-700">رقم الهاتف الأساسي</label>
            <input type="text" name="phone_number" id="phone_number"
                value="{{ old('phone_number', $setting->phone_number ?? '') }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="+966 50 123 4567">
            @error('phone_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- الصف الثاني: العناوين --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- 💡 العنوان باللغة العربية (الجديد) --}}
        <div class="form-group">
            <label for="address_ar" class="block text-sm font-medium text-gray-700">العنوان بالتفصيل (عربي)</label>
            <textarea name="address_ar" id="address_ar" rows="2"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="المدينة، الشارع، المبنى">{{ old('address_ar', $setting->address_ar ?? '') }}</textarea>
            @error('address_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- 💡 العنوان باللغة الإنجليزية (الجديد) --}}
        <div class="form-group">
            <label for="address_en" class="block text-sm font-medium text-gray-700">العنوان بالتفصيل (إنجليزي)</label>
            <textarea name="address_en" id="address_en" rows="2"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="City, Street, Building">{{ old('address_en', $setting->address_en ?? '') }}</textarea>
            @error('address_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>


    {{-- ============================================= --}}
    {{-- 2. روابط التواصل الاجتماعي (تم التحديث) --}}
    {{-- ============================================= --}}
    <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700 mt-8" style="color: var(--primary-color);">2. روابط التواصل الاجتماعي</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        {{-- فيسبوك --}}
        <div class="form-group">
            <label for="facebook_url" class="block text-sm font-medium text-gray-700">رابط فيسبوك</label>
            <input type="url" name="facebook_url" id="facebook_url"
                value="{{ old('facebook_url', $setting->facebook_url ?? '') }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="https://facebook.com/yourpage">
            @error('facebook_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- تويتر (X) --}}
        <div class="form-group">
            <label for="twitter_url" class="block text-sm font-medium text-gray-700">رابط تويتر (X)</label>
            <input type="url" name="twitter_url" id="twitter_url"
                value="{{ old('twitter_url', $setting->twitter_url ?? '') }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="https://x.com/yourhandle">
            @error('twitter_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- إنستغرام --}}
        <div class="form-group">
            <label for="instagram_url" class="block text-sm font-medium text-gray-700">رابط إنستغرام</label>
            <input type="url" name="instagram_url" id="instagram_url"
                value="{{ old('instagram_url', $setting->instagram_url ?? '') }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="https://instagram.com/yourhandle">
            @error('instagram_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        {{-- 💡 رابط LinkedIn (الجديد) --}}
        <div class="form-group">
            <label for="linkedin_url" class="block text-sm font-medium text-gray-700">رابط LinkedIn</label>
            <input type="url" name="linkedin_url" id="linkedin_url"
                value="{{ old('linkedin_url', $setting->linkedin_url ?? '') }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="https://linkedin.com/company/yourcompany">
            @error('linkedin_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- 3. العلامة التجارية والصور (لم يتغير) --}}
    {{-- ============================================= --}}
    <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700 mt-8" style="color: var(--primary-color);">3. العلامة التجارية والصور (Branding)</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- الشعار (Logo) --}}
        <div class="form-group">
            <label for="logo" class="block text-sm font-medium text-gray-700">شعار الموقع (Logo)</label>
            @if ($setting->logo_path)
                <p class="text-xs text-gray-500 mb-1">الشعار الحالي:</p>
                <img src="{{ asset('uploads/branding/' . $setting->logo_path) }}" alt="Current Logo"
                    class="max-h-16 w-auto mb-2 border p-1 rounded">
            @endif
            <input type="file" name="logo" id="logo"
                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- الفافيكون (Favicon) --}}
        <div class="form-group">
            <label for="favicon" class="block text-sm font-medium text-gray-700">أيقونة المتصفح (Favicon)</label>
            @if ($setting->favicon_path)
                <p class="text-xs text-gray-500 mb-1">الأيقونة الحالية:</p>
                <img src="{{ asset('uploads/branding/' . $setting->favicon_path) }}" alt="Current Favicon"
                    class="h-8 w-8 mb-2 border p-1 rounded">
            @endif
            <input type="file" name="favicon" id="favicon"
                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            @error('favicon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- صورة OG الافتراضية للمشاركة --}}
        <div class="form-group">
            <label for="default_og_image" class="block text-sm font-medium text-gray-700">صورة المشاركة الافتراضية
                (OG Image)</label>
            @if ($setting->default_og_image_path)
                <p class="text-xs text-gray-500 mb-1">الصورة الحالية:</p>
                <img src="{{ asset('uploads/branding/' . $setting->default_og_image_path) }}" alt="Current OG Image"
                    class="max-h-16 w-auto mb-2 border p-1 rounded">
            @endif
            <input type="file" name="default_og_image" id="default_og_image"
                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none p-3 file:ml-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-500 mt-1">تستخدم عند مشاركة الروابط على الشبكات الاجتماعية.</p>
            @error('default_og_image')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- 4. نصوص التذييل (لم يتغير) --}}
    <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700 mt-8" style="color: var(--primary-color);">4. نصوص التذييل (Footer Text)</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="form-group mb-6">
        <label for="footer_text_ar" class="block text-sm font-medium text-gray-700">نص التذييل (عربي)</label>
        <textarea name="footer_text_ar" id="footer_text_ar" rows="2"
            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('footer_text_ar', $setting->footer_text_ar ?? 'جميع الحقوق محفوظة &copy; ' . date('Y')) }}</textarea>
        @error('footer_text_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="form-group mb-6">
        <label for="footer_text_ar" class="block text-sm font-medium text-gray-700">Footer Text (English)</label>
        <textarea name="footer_text_ar" id="footer_text_ar" rows="2"
            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('footer_text_en', $setting->footer_text_en ?? 'All rights reserved &copy; ' . date('Y')) }}</textarea>
        @error('footer_text_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

</div>
    {{-- ============================================= --}}
    {{-- 5. التكامل والـ SEO المتقدم (لم يتغير) --}}
    {{-- ============================================= --}}
    <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700 mt-8" style="color: var(--primary-color);">5. التكامل والـ SEO المتقدم</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- Google Analytics ID --}}
        <div class="form-group">
            <label for="google_analytics_id" class="block text-sm font-medium text-gray-700">Google Analytics
                ID</label>
            <input type="text" name="google_analytics_id" id="google_analytics_id"
                value="{{ old('google_analytics_id', $setting->google_analytics_id) }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400"
                placeholder="مثال: UA-XXXXX-Y">
            @error('google_analytics_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Google Maps API Key --}}
        <div class="form-group">
            <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700">Google Maps API
                Key</label>
            <input type="text" name="google_maps_api_key" id="google_maps_api_key"
                value="{{ old('google_maps_api_key', $setting->google_maps_api_key) }}"
                class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">
            <p class="text-xs text-gray-500 mt-1">مفتاح خاص لإظهار الخرائط الديناميكية في صفحة اتصل بنا.</p>
            @error('google_maps_api_key')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="form-group mb-6">
        <label for="default_meta_desc_ar" class="block text-sm font-medium text-gray-700">الوصف التعريفي الافتراضي
            (Meta Description - عربي)</label>
        <textarea name="default_meta_desc_ar" id="default_meta_desc_ar" rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('default_meta_desc_ar', $setting->default_meta_desc_ar) }}</textarea>
        <p class="text-xs text-gray-500 mt-1">يُستخدم كـ SEO افتراضي للصفحات التي لا يوجد بها وصف مخصص.</p>
        @error('default_meta_desc_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="form-group mb-6">
        <label for="default_meta_desc_en" class="block text-sm font-medium text-gray-700">
            (Meta Description - English)</label>
        <textarea name="default_meta_desc_en" id="default_meta_desc_en" rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 text-gray-800 focus:border-blue-400 focus:ring-1 focus:ring-blue-400">{{ old('default_meta_desc_en', $setting->default_meta_desc_en) }}</textarea>
        <p class="text-xs text-gray-500 mt-1">يُستخدم كـ SEO افتراضي للصفحات التي لا يوجد بها وصف مخصص.</p>
        @error('default_meta_desc_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    </div>

    {{-- ============================================= --}}
    {{-- 6. التحكم بالميزات والوصول (لم يتغير) --}}
    {{-- ============================================= --}}
    <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700 mt-8" style="color: var(--primary-color);">6. التحكم بالميزات والوصول</h3>

    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-8 sm:space-x-reverse mb-6">
        {{-- 1. وضع الصيانة --}}
        <div class="flex items-center">
            {{-- Hidden input لضمان إرسال قيمة 0 في حال عدم التحديد --}}
            <input type="hidden" name="maintenance_mode" value="0">
            <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1"
                {{ old('maintenance_mode', $setting->maintenance_mode) ? 'checked' : '' }}
                class="h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
            <label for="maintenance_mode" class="ml-3 block text-sm font-medium text-gray-700">
                تفعيل وضع الصيانة (Maintenance Mode)
            </label>
            <span class="text-xs text-red-500 mr-2 rtl:ml-2">(يعرض رسالة "الموقع قيد الصيانة" لجميع الزوار)</span>
        </div>

        {{-- 2. شريط أدوات الوصول (Accessibility) --}}
        <div class="flex items-center">
            {{-- Hidden input لضمان إرسال قيمة 0 في حال عدم التحديد --}}
            <input type="hidden" name="enable_accessibility_bar" value="0">
            <input type="checkbox" name="enable_accessibility_bar" id="enable_accessibility_bar" value="1"
                {{ old('enable_accessibility_bar', $setting->enable_accessibility_bar) ? 'checked' : '' }}
                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
            <label for="enable_accessibility_bar" class="ml-3 block text-sm font-medium text-gray-700">
                تفعيل شريط أدوات الوصول (Accessibility Tools)
            </label>
        </div>
    </div>

    <div class="mt-8">
        <button type="submit"
            class="btn text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
            style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
            حفظ الإعدادات
        </button>
    </div>
</form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const runBackupBtn = document.getElementById('run-backup-btn');
        const statusBar = document.getElementById('status-bar');
        const statusMessage = document.getElementById('status-message');
        const statusIcon = document.getElementById('status-icon');
        const backupDetails = document.getElementById('backup-details');
        const backupTimeElement = document.getElementById('backup-time');
        const backupFileLink = document.getElementById('backup-file-link');
        const backupFileNameElement = document.getElementById('backup-file-name');
        const progressContainer = document.getElementById('progress-container');
        const progressBar = document.getElementById('progress-bar');
        
        const BACKUP_DURATION_MS = 45000; // 45 ثانية

        // وظيفة التحديث إلى حالة "الجاري"
        function setRunningState() {
            statusBar.classList.remove('bg-green-500', 'bg-red-500', 'bg-blue-500');
            statusBar.classList.add('bg-yellow-500');
            statusIcon.className = 'fas fa-spinner animate-spin text-xl';
            statusMessage.textContent = 'جاري عملية النسخ الاحتياطي... الرجاء عدم إغلاق الصفحة.';
            backupDetails.classList.add('hidden');
            progressContainer.classList.remove('hidden');
            progressBar.style.width = '0%';
            runBackupBtn.disabled = true;
        }

        // وظيفة التحديث إلى حالة "النجاح"
        function setSuccessState() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('ar-EG', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit', 
                hour12: true 
            });
            const dateString = now.toISOString().slice(0, 10); // YYYY-MM-DD
            const fileName = `database-${dateString}-${now.getTime()}.zip`;

            // تحديث شريط الحالة
            statusBar.classList.remove('bg-yellow-500', 'bg-red-500');
            statusBar.classList.add('bg-green-600');
            statusIcon.className = 'fas fa-check-circle text-xl';
            statusMessage.textContent = 'نجاح النسخ! اكتملت العملية.';

            // عرض وتحديث التفاصيل
            backupTimeElement.textContent = timeString;
            backupFileNameElement.textContent = fileName;
            backupFileLink.href = `admin/backups/download/${fileName}`; 
            backupDetails.classList.remove('hidden');
            progressContainer.classList.add('hidden');
            runBackupBtn.disabled = false;
        }

        // وظيفة محاكاة النسخ الاحتياطي (المدة 45 ثانية)
        function simulateBackup() {
            setRunningState();
            
            const startTime = Date.now();
            
            const updateProgress = () => {
                const elapsed = Date.now() - startTime;
                let percentage = (elapsed / BACKUP_DURATION_MS) * 100;

                if (percentage >= 100) {
                    clearInterval(interval);
                    setSuccessState();
                } else {
                    // للتأكد من أنها لا تتجاوز 99% حتى يتم الوصول إلى النهاية
                    progressBar.style.width = `${Math.min(99, percentage)}%`;
                }
            };

            const interval = setInterval(updateProgress, 500); // تحديث شريط التقدم كل نصف ثانية
        }

        // عند تحميل الصفحة، نترك حالة آخر نسخة احتياطية كما هي (نجاح مفترض)
        // إذا كان النظام جاهزًا للعمل، سيتم تحديث حالة "الجاري" تلقائيًا عند الوقت المحدد (وهو ما لا يمكن محاكاته هنا).
        // المستخدم يجب أن يضغط على "تشغيل الآن" لرؤية العملية.

        // ربط زر التشغيل اليدوي
        runBackupBtn.addEventListener('click', (e) => {
            e.preventDefault();
            simulateBackup();
        });

    });
</script>
@endsection