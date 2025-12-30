<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // 💡 منطق معالجة الصورة الجديدة
        if ($request->hasFile('profile_photo')) {
            $user = $request->user();

            // 1. حذف الصورة القديمة إذا كانت موجودة
            // يُفترض أن الحقل في جدول المستخدمين اسمه 'profile_photo_path'
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // 2. حف الملف الجديد في مجلد 'profile-photos'
            // يتم تخزين المسار المتعلق بالـ Storage في قاعدة البيانات
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // 3. تحديث مسار الصورة في قاعدة البيانات
            $user->profile_photo_path = $path;

            // حذف التغييرات على الـ user
            $user->save();
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
