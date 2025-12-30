<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e56a0;
            --primary-hover-color: #153d71;
            --secondary-color: #f28f3b;
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

        .main-header {
            background-color: var(--card-background);
            border-bottom: 1px solid var(--border-color);
        }

        .btn-primary {
            background-color: var(--secondary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover-color);
        }

        .btn-secondary {
            background-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body>
    <header class="main-header p-4 md:p-6 flex justify-between items-center">
        <div class="logo">
            <a href="/" class="text-2xl font-bold" style="color: var(--primary-color);">اسم الجمعية</a>
        </div>
        <nav>
            <a href="#" class="text-gray-700 hover:text-gray-900 mx-2">الرئيسية</a>
            <a href="{{ route('admin.news.index') }}" class="text-gray-700 hover:text-gray-900 mx-2">الأخبار</a>
            <a href="#" class="btn-primary text-white py-2 px-4 rounded-md">تبرّع الآن</a>
            <a href="#" class="text-gray-700 hover:text-gray-900 mx-2">EN</a>
        </nav>
    </header>

    <main class="container mx-auto p-4 md:p-8">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-gray-400 p-8 text-center mt-8">
        <p>&copy; 2025 جميع الحقوق محفوظة لـ [اسم الجمعية].</p>
    </footer>

</body>

</html>