<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('حذف الحساب') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته بشكل دائم. قبل الحذف، يرجى تنزيل أي بيانات أو معلومات ترغب في الاحتفاظ بها.') }}
        </p>
    </header>

    {{-- زر فتح النافذة المنبثقة --}}
    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded transition duration-150">
        {{ __('حذف الحساب') }}
    </button>

    {{-- النافذة المنبثقة (Modal) التي تحتوي على نموذج تأكيد الحذف --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('هل أنت متأكد من رغبتك في حذف حسابك؟') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('بمجرد حذف حسابك، سيتم حذف جميع موارده وبياناته بشكل دائم. الرجاء إدخال كلمة المرور لتأكيد الحذف.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('كلمة المرور') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-3/4 focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('كلمة المرور') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                {{-- زر الإلغاء --}}
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('إلغاء') }}
                </x-secondary-button>

                {{-- زر التأكيد على الحذف --}}
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ml-3">
                    {{ __('حذف الحساب') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>