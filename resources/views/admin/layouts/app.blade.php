@php
    $currentRoute = Route::currentRouteName();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة الأخبار</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen bg-gray-100">

        <!-- Sidebar -->
        <aside class="flex-shrink-0 w-64 bg-gray-800 text-white shadow-xl">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-center">لوحة التحكم</h1>
            </div>
            <nav class="mt-8">
                <a href="{{ route('news.index') }}"
                    class="flex items-center px-6 py-3 text-gray-200 hover:bg-gray-700 transition duration-300 {{ Str::startsWith($currentRoute, 'admin.news') ? 'bg-gray-700 border-r-4 border-blue-500' : '' }}">
                    <i class="fas fa-newspaper ml-3 text-xl"></i>
                    الأخبار
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-200 hover:bg-gray-700 transition duration-300">
                    <i class="fas fa-users ml-3 text-xl"></i>
                    المستخدمين
                </a>
                <a href="#" class="flex items-center px-6 py-3 text-gray-200 hover:bg-gray-700 transition duration-300">
                    <i class="fas fa-cog ml-3 text-xl"></i>
                    الإعدادات
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <!-- Header -->
            <header class="bg-white shadow p-4 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">إدارة المحتوى</h2>
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-300">
                        <i class="fas fa-bell text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-300">
                        <i class="fas fa-user-circle text-xl"></i>
                    </a>
                </div>
            </header>

            <div class="p-6">
                @yield('content')
            </div>
        </main>

    </div>

</body>

</html>