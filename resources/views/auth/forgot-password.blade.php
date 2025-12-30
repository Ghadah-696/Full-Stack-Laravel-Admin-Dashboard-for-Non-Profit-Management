<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>استعادة كلمة المرور</title>

    {{-- 💡 تأكدي من ربط Vite أو Mix --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* تعريف الشريط العلوي باللون الجديد */
        .login-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            border-top: 5px solid #38b6ff;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen flex-col">

    {{-- 1. شعار الموقع في الأعلى (h-20) --}}
    <a href="{{ url('/') }}" class="hover:opacity-75 transition duration-150 mb-6">
        <img src="{{ asset('images/charitylogo.png') }}" alt="شعار الموقع" class="h-20 w-auto">
    </a>

    <div class="login-card w-full max-w-md bg-white rounded-lg p-8">

        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">استعادة كلمة المرور</h1>
            <p class="text-gray-500 mt-2">أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين.</p>
        </div>

        {{-- 2. عرض رسالة الحالة (نجاح الإرسال) --}}
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 p-3 rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            {{-- 3. حقل البريد الإلكتروني --}}
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-primary @error('email') border-red-500 @enderror">

                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- 4. زر إرسال الرابط وروابط العودة --}}
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('login') }}"
                    class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800">
                    العودة لصفحة تسجيل الدخول
                </a>

                <!-- <button type="submit"
                    class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                    إرسال رابط التعيين
                </button> -->
                <button type="submit"
                    class="bg-[#38b6ff] hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                    إرسال رابط التعيين
                </button>
            </div>
        </form>
    </div>
</body>

</html>