<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <div class="container mx-auto p-4 md:p-8">

        <div class="bg-white rounded-t-lg shadow-md p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">@yield('title')</h1>
        </div>

        <div class="bg-white rounded-b-lg shadow-md p-6">
            @yield('form_content')
        </div>

    </div>
</body>

</html>