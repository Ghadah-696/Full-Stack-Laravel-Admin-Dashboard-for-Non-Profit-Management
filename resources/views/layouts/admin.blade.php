<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page_title', 'لوحة التحكم')</title>

    {{-- 💡 استيراد الخط (Tajawal) والأيقونات (FontAwesome) --}}
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    {{-- 💡 سنستخدم Alpine.js في هذه النسخة لتسهيل تفاعلات الواجهة --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- استيراد Tailwind (يمكن استبداله بـ @vite عند استخدام Laravel Mix/Vite) --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
    <!-- @yield('styles') -->

</head>

<body x-data="{ sidebarOpen: true }">


    @php
        // تحديد الدور والأيقونة
        $user = auth()->user();
        $roleName = $user && ($user->hasRole('manager') || $user->hasRole('super-admin')) ? 'لوحة تحكم المدير' : 'لوحة تحكم مدخل البيانات';
        $iconClass = $user && ($user->hasRole('manager') || $user->hasRole('super-admin')) ? 'fas fa-shield-alt' : 'fas fa-keyboard';
    @endphp

    {{-- 1. الشريط الجانبي الداكن (Sidebar) --}}
    <div x-show="sidebarOpen" :class="{ 'transform translate-x-full md:translate-x-0': !sidebarOpen }"
        class="main-sidebar">

        {{-- شعار لوحة التحكم (Header) --}}
        <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center py-5 bg-opacity-10 shadow-sm"
            style="background-color: var(--primary-color);">
            <i class="{{ $iconClass }} text-2xl text-white ml-3"></i>
            <span class="text-xl font-extrabold tracking-wider text-white">{{ $roleName }}</span>
        </a>

        {{-- روابط القائمة --}}
        <nav class="mt-4 px-3 space-y-1">

            {{-- الرئيسية --}}
            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>الرئيسية</span>
            </a>

            {{-- الصفحات الثابتة (رابط مباشر) --}}
            @canany(['view_page', 'create_page', 'edit_page', 'delete_page'])
                <a href="{{ route('admin.pages.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.pages.index') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>الصفحات الثابتة</span>
                </a>
            @endcanany

            {{-- ------------------------------------------------ --}}
            {{-- 1. إدارة المحتوى الإخباري (قائمة منسدلة) --}}
            @php
                $isNewsActive = Request::routeIs(['admin.news.*', 'admin.categories.*']);
            @endphp
            <div x-data="{ open: {{ $isNewsActive ? 'true' : 'false' }} }">
                <a href="#" @click.prevent="open = !open"
                    class="sidebar-link justify-between {{ $isNewsActive ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-newspaper"></i>
                        <span>إدارة المحتوى الإخباري</span>
                    </div>
                    <i class="fas fa-chevron-left text-xs transition-transform duration-300"
                        :class="{ 'transform rotate-90': open }"></i>
                </a>
                <div x-show="open" x-collapse.duration.300ms class="submenu">
                    @canany(['view_news', 'create_news', 'edit_news', 'delete_news'])
                        <a href="{{ route('admin.news.index') }}">الأخبار</a>
                    @endcanany
                    @canany(['view_category', 'create_category', 'edit_category', 'delete_category'])
                        <a href="{{ route('admin.categories.index') }}">التصنيفات</a>
                    @endcanany
                </div>
            </div>

            {{-- 2. إدارة المشاريع والأثر (قائمة منسدلة) --}}
            @php
                $isProjectsActive = Request::routeIs(['admin.projects.*', 'admin.impacts.*']);
            @endphp
            <div x-data="{ open: {{ $isProjectsActive ? 'true' : 'false' }} }">
                <a href="#" @click.prevent="open = !open"
                    class="sidebar-link justify-between {{ $isProjectsActive ? 'active' : '' }}">
                    <div class="flex items-center">
                        <i class="fas fa-tasks"></i>
                        <span>إدارة المشاريع والأثر</span>
                    </div>
                    <i class="fas fa-chevron-left text-xs transition-transform duration-300"
                        :class="{ 'transform rotate-90': open }"></i>
                </a>
                <div x-show="open" x-collapse.duration.300ms class="submenu">
                    @canany(['view_project', 'create_project', 'edit_project', 'delete_project'])
                        <a href="{{ route('admin.projects.index') }}">المشاريع</a>
                    @endcanany
                    @canany(['view_impact', 'create_impact', 'edit_impact', 'delete_impact'])
                        <a href="{{ route('admin.impacts.index') }}">مقاييس الأثر</a>
                    @endcanany
                </div>
            </div>
            {{-- ------------------------------------------------ --}}

            {{-- السلايدر --}}
            @canany(['view_slider', 'create_slider', 'edit_slider', 'delete_slider'])
                <a href="{{ route('admin.sliders.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.sliders.index') ? 'active' : '' }}">
                    <i class="fas fa-sliders-h"></i>
                    <span>السلايدر</span>
                </a>
            @endcanany

            {{-- القصص --}}
            @canany(['view_story', 'create_story', 'edit_story', 'delete_story'])
                <a href="{{ route('admin.stories.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.stories.index') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span>القصص</span>
                </a>
            @endcanany

            {{-- الوثائق والتقارير --}}
            @canany(['view_document', 'create_document', 'edit_document', 'delete_document'])
                <a href="{{ route('admin.documents.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.documents.index') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i>
                    <span>الوثائق والتقارير</span>
                </a>
            @endcanany

            {{-- الشركاء والداعمون --}}
            @canany(['view_partner', 'create_partner', 'edit_partner', 'delete_partner'])
                <a href="{{ route('admin.partners.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.partners.index') ? 'active' : '' }}">
                    <i class="fas fa-handshake"></i>
                    <span>الشركاء والداعمون</span>
                </a>
            @endcanany

            <hr class="border-gray-700 my-2">

            <!-- {{-- إدارة التبرعات --}}
            @can('view_donation')
                <a href="{{ route('admin.donations.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.donations.index') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>قائمة التبرعات</span>
                </a>
            @endcan -->


            @canany(['view_roles', 'create_roles', 'edit_roles', 'delete_roles'])
                <a href="{{ route('admin.roles.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.roles.index') ? 'active' : '' }}">
                    {{-- ملاحظة: استخدمت * في RouteIs للشمولية --}}
                    <i class="fas fa-users-cog"></i>
                    <span>إدارة الأدوار والصلاحيات</span>
                </a>
            @endcanany


            {{-- إدارة المستخدمين --}}
            @canany(['view_user', 'create_user', 'edit_user', 'delete_user'])
                <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link {{ Request::routeIs('admin.users.index') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>إدارة المستخدمين</span>
                </a>
            @endcanany

            {{-- الإعدادات العامة --}}
            @can('edit_setting')
                <a href="{{ route('admin.settings.edit') }}"
                    class="sidebar-link {{ Request::routeIs('admin.settings.edit') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i>
                    <span>الإعدادات العامة</span>
                </a>
            @endcan

            <hr class="border-gray-700 my-2">

            {{-- تسجيل الخروج (مباشر) --}}
            <form method="POST" action="{{ route('logout') }}" class="py-2">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="sidebar-link hover:bg-red-700 text-red-400 hover:text-white">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </form>

        </nav>

    </div>

    {{-- 2. المحتوى الرئيسي (Main Content) --}}
    <div class="main-content" :class="{ 'mr-0': !sidebarOpen, 'mr-280': sidebarOpen }">

        {{-- الشريط العلوي (Navbar/Header) --}}
        <header class="bg-white shadow-md p-4 flex justify-between items-center z-40">

            <div class="flex items-center">
                {{-- زر فتح/إغلاق القائمة الجانبية (لشاشات العرض الكبيرة) --}}
                <button class="text-gray-500 hover:text-primary-color ml-4" @click="sidebarOpen = !sidebarOpen">
                    <i class="fas fa-bars text-xl" :class="{ 'text-primary-color': sidebarOpen }"></i>
                </button>

                <!-- <span class="text-xl font-bold text-gray-800">@yield('page_title', 'لوحة التحكم')</span> -->
                <span class="text-xl font-bold text-gray-800"> <img src="{{ asset('/images/charitylogo.png') }}"
                        alt="شعار الموقع" class="w-14 h-14 object-cover rounded-lg "
                        style="min-width: 64px; min-height: 64px;">
                </span>
            </div>

            {{-- مربع البحث الشامل (Global Search) --}}
            <div class="hidden md:block w-full max-w-sm mx-8">
                <div class="relative">
                    <input type="text" placeholder="البحث الشامل..."
                        class="w-full border-gray-300 rounded-lg pr-10 pl-4 py-2 text-sm focus:border-primary-color focus:ring-primary-color">
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="flex items-center space-x-4 space-x-reverse">

                <!-- {{-- 🔔 جرس الإشعارات --}}
                <button class="text-gray-500 hover:text-primary-color relative p-1 rounded-full">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500 border border-white"></span>
                </button> -->

                {{-- 🔔 جرس الإشعارات - تم التعديل هنا --}}
                <div x-data="{ notificationsOpen: false }" @click.away="notificationsOpen = false" class="relative">

                    {{-- زر الإشعارات (الجرس) --}}
                    <button @click="notificationsOpen = !notificationsOpen"
                        class="text-gray-500 hover:text-primary-color relative p-1 rounded-full focus:outline-none">
                        <i class="fas fa-bell text-xl"></i>

                        {{-- عرض عدد الإشعارات غير المقروءة (قادمة من View Composer) --}}
                        @if (isset($unreadCount) && $unreadCount > 0)
                            <span
                                class="absolute top-0 right-0 inline-flex items-center justify-center 
                                                                         px-2 py-1 text-xs font-bold leading-none text-red-100 
                                                                         transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full border border-white">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        @else
                            {{-- المؤشر الأحمر الصغير الافتراضي إذا لم يكن هناك عدد --}}
                            <span class="absolute top-0 right-0 h-2 w-2 rounded-full hidden"></span>
                        @endif
                    </button>

                    {{-- القائمة المنسدلة للإشعارات --}}
                    <div x-show="notificationsOpen" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute mt-2 w-80 rounded-md shadow-xl py-1 bg-white ring-1 ring-black ring-opacity-5 z-50 origin-top-right right-0"
                        style="display: none;"> {{-- (Alpine.js للتحكم في العرض) --}}

                        <div class="py-1">
                            <p class="px-4 pt-2 pb-1 text-sm font-semibold text-gray-700 border-b">
                                الإشعارات ({{ $unreadCount ?? 0 }} غير مقروء)
                            </p>

                            <!-- عرض قائمة الإشعارات (تستخدم $unreadNotifications) -->
                            @if (isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                @foreach ($unreadNotifications as $notification)
                                    {{-- يجب أن يكون رابط الإشعار هو المسار لتصنيفه كمقروء --}}
                                    <a href="{{ route('admin.notifications.read', $notification->id) }}"
                                        class="block px-4 py-3 hover:bg-gray-50 {{ $notification->read_at ? 'text-gray-500' : 'bg-indigo-50/50' }} border-b transition duration-150">
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $notification->data['message'] ?? 'إشعار جديد' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            <i class="far fa-clock ml-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </a>
                                @endforeach

                                {{-- رابط عرض جميع الإشعارات --}}
                                <a href="{{ route('admin.notifications.index') }}"
                                    class="block text-center text-indigo-600 py-2 border-t text-sm hover:bg-indigo-50">
                                    <i class="fas fa-list-ul ml-1"></i> عرض جميع الإشعارات
                                </a>

                            @else
                                <p class="px-4 py-3 text-sm text-gray-500 text-center">لا توجد إشعارات غير مقروءة.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- 👤 قائمة الملف الشخصي والصورة --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative">
                    <button @click="open = !open"
                        class="flex items-center p-1 rounded-full text-sm font-medium text-gray-700 hover:text-primary-color focus:outline-none">

                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/default-profile.png') }}"
                            alt="{{ Auth::user()->name }}" class="h-9 w-9 rounded-full object-cover ml-2">

                        <div>
                            <span class="block text-sm font-semibold text-right">{{ Auth::user()->name }}</span>
                            <span
                                class="block text-xs text-gray-500 text-right">{{ Auth::user()->getRoleNames()->first() ?? 'لا يوجد دور' }}</span>
                        </div>

                        {{-- أيقونة السهم المنسدل --}}
                        <svg class="mr-1 h-4 w-4 text-gray-400 transition-transform duration-200"
                            :class="{ 'transform rotate-180': open }" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- القائمة المنسدلة (Dropdown) --}}
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute mt-2 w-48 rounded-md shadow-xl py-1 bg-white ring-1 ring-black ring-opacity-5 z-50 origin-top-right right-0">

                        <a href="{{ route('profile.edit') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog ml-2"></i> إعدادات الملف الشخصي
                        </a>

                        <div class="border-t border-gray-100 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt ml-2"></i> تسجيل الخروج
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </header>
        {{-- ============================================= --}}
        {{-- 1. المودال المركزي لتأكيد الحذف (في نهاية الجسم) --}}
        {{-- ============================================= --}}
        <div class="container mx-auto px-4 pt-6">

            <!-- تنبيه النجاح (Success) -->
            @if (session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => { show = false; }, 5000)" x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:leave="transition ease-in duration-300"
                    class="bg-green-50 border-r-4 border-green-600 text-green-800 p-4 rounded-lg shadow-lg mb-6 flex items-start space-x-3 space-x-reverse"
                    role="alert">

                    <div class="flex-shrink-0 pt-0.5"><i class="fas fa-check-circle text-green-500 text-lg"></i></div>
                    <div class="flex-grow">
                        <p class="font-bold text-sm">عملية ناجحة!</p>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- 2. تنبيه الخطأ (Error/Guardrail) -->
            <!-- تنبيه الخطأ (Error) -->
            @if (session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => { show = false; }, 8000)" x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:leave="transition ease-in duration-300"
                    class="bg-red-50 border-r-4 border-red-600 text-red-800 p-4 rounded-lg shadow-lg mb-6 flex items-start space-x-3 space-x-reverse"
                    role="alert">

                    <div class="flex-shrink-0 pt-0.5"><i class="fas fa-exclamation-circle text-red-500 text-lg"></i></div>
                    <div class="flex-grow">
                        <p class="font-bold text-sm">خطأ في الإجراء!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- 3. تنبيه أخطاء التحقق القياسية (Validation Errors) -->
            <!-- تنبيه أخطاء التحقق القياسية (Validation Errors) -->
            @if ($errors->any())
                <div class="bg-yellow-50 border-r-4 border-yellow-600 text-yellow-800 p-4 rounded-lg shadow-lg mb-6"
                    role="alert">
                    <div class="flex items-start space-x-3 space-x-reverse">
                        <div class="flex-shrink-0 pt-0.5"><i class="fas fa-info-circle text-yellow-500 text-lg"></i></div>
                        <div class="flex-grow">
                            <p class="font-bold text-sm mb-1">يرجى مراجعة الأخطاء التالية:</p>
                            <ul class="list-disc list-inside text-sm mt-2 space-y-1 pr-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif


        </div>


        {{-- منطقة المحتوى --}}
        <main class="p-6">
            @yield('content')
        </main>
    </div>
    <!-- ======================================================= -->
    <!-- الموضع B: مودال تأكيد الحذف (Delete Confirmation Modal) -->
    <!-- تم تعديل الهيكل ليتناسب مع منطق JS النقي (إضافة IDs و Transition Classes) -->
    <!-- ======================================================= -->
    <div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-900/60 items-center justify-center z-[1000] hidden"
        role="dialog" aria-modal="true">

        <!-- محتوى المودال -->
        <div id="modalContent"
            class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-sm mx-4 transform transition-all duration-300 scale-95 opacity-0">

            <div class="flex items-center justify-between mb-4 border-b pb-2">
                <h3 id="modalTitle" class="text-xl font-extrabold text-red-600">
                    <i class="fas fa-exclamation-triangle ml-2"></i> تأكيد الحذف
                </h3>
                <button type="button" id="closeModalBtn"
                    class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-100"
                    aria-label="إغلاق">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <p class="text-sm text-gray-700 mb-6">
                هل أنت متأكد من حذف العنصر:
                <span id="modalItemTitle" class="font-bold text-red-700"></span>؟
                <br>
                <span class="font-semibold text-xs text-red-500 block mt-1">لا يمكن التراجع عن هذا الإجراء!</span>
            </p>

            <!-- Form Action سيتم تعيينه ديناميكياً بواسطة JavaScript -->
            <form id="deleteForm" method="POST">

                <!-- يجب إضافة هذين التوجيهين لتفادي أخطاء Laravel -->
                @csrf
                @method('DELETE')

                <div class="flex justify-end space-x-3 space-x-reverse">
                    <button type="button" id="cancelDeleteBtn"
                        class="px-5 py-2 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition shadow-md">
                        نعم، قم بالحذف
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- انتهى مودال تأكيد الحذف -->




    {{-- إضافة ملفات CSS و JS الخاصة بكِ --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // =============================================
            // A. منطق مودال تأكيد الحذف
            // =============================================
            const modal = document.getElementById('deleteConfirmationModal');
            const modalContent = document.getElementById('modalContent');
            const deleteForm = document.getElementById('deleteForm');
            const cancelBtn = document.getElementById('cancelDeleteBtn');
            const closeBtn = document.getElementById('closeModalBtn');
            const itemTitle = document.getElementById('modalItemTitle');
            // استهداف جميع أزرار الحذف في الصفحة (يجب أن تحمل هذا الكلاس)
            const deleteTriggers = document.querySelectorAll('.js-delete-trigger');

            // دالة الإغلاق الموحدة
            function closeModal() {
                // بدء إغلاق تأثير الانتقال (من 100% إلى 95% ومظهر من 1 إلى 0)
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    // إخفاء الـ modal بالكامل من العرض بعد اكتمال الانتقال
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300); // 300ms تتطابق مع مدة transition-all duration-300
            }

            // فتح المودال
            deleteTriggers.forEach(trigger => {
                trigger.addEventListener('click', function () {
                    const actionUrl = this.getAttribute('data-action');
                    const title = this.getAttribute('data-title') || 'هذا العنصر';

                    itemTitle.textContent = title;
                    deleteForm.setAttribute('action', actionUrl);

                    // إظهار المودال مع تأثير انتقال سلس
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    // تأخير بسيط لضمان عمل الانتقال بشكل صحيح
                    setTimeout(() => {
                        modalContent.classList.remove('scale-95', 'opacity-0');
                        modalContent.classList.add('scale-100', 'opacity-100');
                    }, 50);
                });
            });

            // ربط أزرار الإلغاء والإغلاق بدالة الإغلاق
            if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
            if (closeBtn) closeBtn.addEventListener('click', closeModal);

            // إغلاق المودال عند النقر خارج المحتوى
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            // إغلاق المودال عند الضغط على مفتاح Escape
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });


            // =============================================
            // B. منطق رسائل التنبيه (Toasts) التلقائية (نجاح وخطأ)
            // =============================================

            // دالة موحدة لإخفاء التنبيه
            function hideAlert(alertElement) {
                if (!alertElement) return;

                // بدء إخفاء تأثير الانتقال (إزاحة للخارج وشفافية من 100% إلى 0%)
                alertElement.classList.remove('translate-x-0', 'opacity-100');
                alertElement.classList.add('translate-x-full', 'opacity-0');

                // إزالة العنصر من DOM بعد اكتمال الانتقال
                setTimeout(() => {
                    alertElement.remove();
                }, 300);
            }

            // معالجة تنبيه النجاح
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                // إظهار الرسالة بتأثير انتقال بعد تحميل الصفحة بوقت قصير
                setTimeout(() => {
                    successAlert.classList.remove('translate-x-full', 'opacity-0');
                    successAlert.classList.add('translate-x-0', 'opacity-100');
                }, 100);

                // إخفاء الرسالة تلقائياً بعد 5 ثوانٍ
                const timer = setTimeout(function () {
                    hideAlert(successAlert);
                }, 5000);

                // الإغلاق اليدوي
                successAlert.querySelectorAll('.js-alert-close').forEach(btn => {
                    btn.addEventListener('click', function () {
                        clearTimeout(timer); // إيقاف المؤقت التلقائي
                        hideAlert(successAlert);
                    });
                });
            }

            // معالجة تنبيه الخطأ (يظهر لمدة أطول قليلاً، 8 ثوانٍ)
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                // إظهار الرسالة بتأثير انتقال
                setTimeout(() => {
                    errorAlert.classList.remove('translate-x-full', 'opacity-0');
                    errorAlert.classList.add('translate-x-0', 'opacity-100');
                }, 100);

                // إخفاء الرسالة تلقائياً بعد 8 ثوانٍ
                const timer = setTimeout(function () {
                    hideAlert(errorAlert);
                }, 8000);

                // الإغلاق اليدوي
                errorAlert.querySelectorAll('.js-alert-close').forEach(btn => {
                    btn.addEventListener('click', function () {
                        clearTimeout(timer); // إيقاف المؤقت التلقائي
                        hideAlert(errorAlert);
                    });
                });
            }
        });
    </script>

</body>

</html>