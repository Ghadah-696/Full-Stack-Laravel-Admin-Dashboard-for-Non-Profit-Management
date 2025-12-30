<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        <style> :root {
            --primary-color: #1e56a0;
            /* أزرق داكن */
            --primary-hover-color: #153d71;
            --secondary-color: #f28f3b;
            /* برتقالي دافئ */
            --background-color: #f8f9fa;
            --card-background: #ffffff;
            --text-color: #212529;
            --secondary-text-color: #6c757d;
            --border-color: #e9ecef;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .sidebar {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar a {
            color: white;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: var(--primary-hover-color);
        }

        .content {
            margin-right: 220px;
        }

        .header {
            background-color: var(--card-background);
            border-bottom: 1px solid var(--border-color);
        }

        .header h1 {
            color: var(--text-color);
        }

        .header .logout {
            color: var(--secondary-color);
            font-weight: bold;
        }

        .card {
            background-color: var(--card-background);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- /* لتسهيل التمييز في الوقت الحالي */
    

    .main-content {
    background-color: #ecf0f1;
    } -->
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <aside class="w-64 sidebar text-gray-100 p-4 md:p-6 shadow-lg hidden md:block">
            <h2 class="text-2xl font-bold mb-8 text-white">لوحة التحكم</h2>
            <ul>
                <li class="mb-4">
                    <a href="#" class="block hover:text-gray-300 transition-colors duration-200">الأخبار</a>
                </li>
                <li class="mb-4">
                    <a href="#" class="block hover:text-gray-300 transition-colors duration-200">المستخدمون</a>
                </li>
            </ul>
        </aside>

        <main class="flex-1 main-content p-4 md:p-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">
                    @yield('title')</h1>

                @yield('content')
                <!-- </div> -->
                <!-- <div class="sidebar w-64 p-5 h-full fixed top-0 right-0">
        <aside class="w-64 sidebar text-gray-100 p-4 md:p-6 shadow-lg hidden md:block">
            <h2 class="text-2xl font-bold mb-8 text-white">لوحة التحكم</h2>
            <ul>
                <li class="mb-4"> <a class="block" href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                <li class="mb-4"> <a href="{{ route('admin.news.index') }}">الأخبار</a></li>
                <li class="mb-4"> <a href="{{ route('admin.projects.index') }}">المشاريع</a></li>
                <li class="mb-4"> <a href="{{ route('admin.pages.index') }}">الصفحات الثابتة</a></li>
                <li class="mb-4"> <a href="{{ route('admin.sliders.index') }}">السلايدر</a></li>
                <li class="mb-4"> <a href="{{ route('admin.stories.index') }}">القصص</a></li>
                <hr class="my-4 border-gray-400">
                <a href="#" class="logout"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">تسجيل الخروج</a>
            </ul>
        </aside>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    <div class="content p-8">
        <div class="header">
            <h1>@yield('page_title')</h1>
        </div> -->
                <!-- @yield('content') -->
            </div>
    </div>

    </div>
    </main>
</body>

</html>