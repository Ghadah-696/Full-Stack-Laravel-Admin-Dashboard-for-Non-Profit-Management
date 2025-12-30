<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('تحديث كلمة المرور') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('تأكدي من أن حسابك يستخدم كلمة مرور طويلة وعشوائية ليبقى آمناً.') }}
        </p>
    </header>

    {{-- يتم استخدام المسار الجاهز password.update --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        {{-- حقل كلمة المرور الحالية --}}
        <div>
            <x-input-label for="current_password" :value="__('كلمة المرور الحالية')" />
            <x-text-input id="current_password" name="current_password" type="password"
                class="mt-1 block w-full focus:border-red-500 focus:ring-red-500" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- حقل كلمة المرور الجديدة --}}
        <div>
            <x-input-label for="password" :value="__('كلمة المرور الجديدة')" />
            <x-text-input id="password" name="password" type="password"
                class="mt-1 block w-full focus:border-red-500 focus:ring-red-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- حقل تأكيد كلمة المرور الجديدة --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full focus:border-red-500 focus:ring-red-500" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            {{-- 💡 التعديل: استخدام لون تحذيري (أحمر) لزر تغيير كلمة المرور --}}
            <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                {{ __('حفظ كلمة المرور') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    {{ __('تم الحفظ.') }}
                </p>
            @endif
        </div>
    </form>
</section>