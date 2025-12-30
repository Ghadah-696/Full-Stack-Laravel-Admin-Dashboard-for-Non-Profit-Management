<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('معلومات الملف الشخصي') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('تحديث الاسم والبريد الإلكتروني لحسابك.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <div class="bg-white p-6 shadow-md rounded-lg mb-8 flex justify-center">
        {{-- 💡 التعديل: إضافة enctype="multipart/form-data" --}}
        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6 w-full max-w-lg mx-auto"
            enctype="multipart/form-data">
            @csrf
            @method('patch')

            {{-- 1. الآلية الجديدة لتغيير الصورة (الدائرة التفاعلية فقط وموسّطة) --}}
            <div class="mb-8 flex justify-center">

                {{-- حقل الإدخال الفعلي (مخفي) --}}
                <input type="file" name="profile_photo" id="profile_photo_input" class="!hidden"
                    onchange="document.getElementById('profile_photo_preview').src = window.URL.createObjectURL(this.files[0])">

                {{-- وسم Label القابل للنقر (الصورة فقط، ولا شيء إضافي) --}}
                <label for="profile_photo_input" class="relative cursor-pointer group">

                    {{-- الصورة الحالية --}}
                    <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('images/default-profile.png') }}"
                        alt="صورة الملف الشخصي" id="profile_photo_preview"
                        class="h-32 w-32 rounded-full object-cover border-4 border-gray-300 group-hover:border-[#38b6ff] transition duration-300">

                    {{-- تأثير التمرير (Overlay) --}}
                    <div
                        class="absolute inset-0 bg-black bg-opacity-40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <i class="fas fa-camera text-white text-2xl"></i>
                    </div>
                </label>

                {{-- عرض خطأ الصورة إذا حدث (يظهر أسفل الدائرة) --}}
                <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
            </div>

            {{-- حقول الاسم والبريد (تم توسيطها الآن ضمن النموذج) --}}
            <div>
                <x-input-label for="name" :value="__('الاسم')" />
                <x-text-input id="name" name="name" type="text"
                    class="mt-1 block w-full focus:border-primary focus:ring-primary" :value="old('name', $user->name)"
                    required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('البريد الإلكتروني')" />
                <x-text-input id="email" name="email" type="email"
                    class="mt-1 block w-full focus:border-primary focus:ring-primary" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            {{-- زر الحفظ (موسّط ضمن النموذج) --}}
            <div class="flex items-center justify-center gap-4">
                <button type="submit"
                    class="bg-[#38b6ff] hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150">
                    {{ __('حفظ التغييرات') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600">
                        {{ __('تم الحفظ.') }}
                    </p>
                @endif
            </div>
        </form>
</section>