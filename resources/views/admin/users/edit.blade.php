@extends('layouts.admin')

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold mb-6">تعديل المستخدم: {{ $user->name }}</h1>

        <div class="bg-white p-6 shadow-md rounded-lg max-w-lg mx-auto">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">الاسم:</label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني:</label>
                    <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('email') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-gray-700 text-sm font-bold mb-2">تعيين الدور:</label>
                    <select name="role" id="role" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @if($role->name === $currentRole) selected @endif>
                                {{ $role->name }}
                            </option>
                        @endforeach

                    </select>
                    @error('role') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">كلمة المرور (اتركها فارغة لعدم
                        التغيير):</label>
                    <input type="password" name="password" id="password"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('password') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">تأكيد كلمة
                        المرور:</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <!-- <div class="flex items-center justify-between">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                حفظ التعديلات
                            </button>
                        </div> -->
                {{-- أزرار الإرسال والإلغاء --}}
                <div class="flex items-center justify-end mt-10 border-t pt-6">
                    <a href="{{ route('admin.users.index') }}"
                        class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                        <i class="fas fa-times ml-2"></i> إلغاء
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md  transition duration-300 shadow-md inline-flex items-center font-bold"
                        style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                        <i class="fas fa-save ml-2 rtl:mr-2"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection