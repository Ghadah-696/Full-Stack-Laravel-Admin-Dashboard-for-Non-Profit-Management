<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - لوحة التحكم</title>

    {{-- تأكدي من ربط ملف CSS الخاص بك هنا (مثل ملف vite أو mix) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* يمكن إضافة بعض التنسيقات لضبط الخلفية */
        body {
            background-color: #f4f7fa;
            /* لون خلفية خارجي فاتح */
        }

        .login-card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            border-top: 5px solid #38b6ff;
            /* شريط علوي بلون الأزرق الذي اخترناه */
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen flex-col">

    <a href="{{ url('/') }}" class="hover:opacity-75 transition duration-150  mb-6">

        {{-- ✅ استخدام دالة asset() لتحديد مسار الصورة في مجلد public --}}
        <img src="{{ asset('/images/charitylogo.png') }}" alt="شعار الموقع" class="h-28 w-auto" {{-- تحديد ارتفاع وعرض
            مناسبين --}}>
    </a>
    <div class="login-card w-full max-w-md bg-white rounded-lg p-8">

        {{-- 💡 الإضافة الجديدة: أيقونة ورابط الخروج --}}
        <div class="text-center mb-6">

            <h1 class="text-3xl font-bold text-gray-800">تسجيل الدخول</h1>
            <p class="text-gray-500 mt-2">لوحة تحكم المدير</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- حقل البريد الإلكتروني --}}
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#38b6ff] @error('email') border-red-500 @enderror">

                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- حقل كلمة المرور --}}
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور:</label>
                <input id="password" type="password" name="password" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-[#38b6ff] @error('password') border-red-500 @enderror">

                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- تذكرني (Remember Me) --}}
            <div class="mb-6 flex items-center justify-between">
                <label class="flex items-center text-gray-600 text-sm">
                    <input type="checkbox" name="remember" id="remember" class="ml-2">
                    تذكرني
                </label>

                {{-- رابط نسيان كلمة المرور (إذا كان مفعلاً لديكِ) --}}
                @if (Route::has('password.request'))
                    <a class="inline-block align-baseline font-bold text-sm text-[#38b6ff] hover:text-blue-800"
                        href="{{ route('password.request') }}">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            {{-- زر تسجيل الدخول --}}
            <div class="flex items-center justify-center">
                <button type="submit"
                    class="bg-[#38b6ff] hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-150">
                    تسجيل الدخول
                </button>
            </div>
        </form>
    </div>
</body>

</html>