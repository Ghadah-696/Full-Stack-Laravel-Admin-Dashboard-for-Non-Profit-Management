@extends('layouts.admin')
@section('page_title', 'تسجيل مقياس أثر جديد')
@section('content')
    <div class="p-6 md:p-10">
        {{-- ================================================= --}}
        {{-- 1. العنوان وزر العودة --}}
        {{-- ================================================= --}}
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-project-diagram ml-2" style="color: var(--primary-color);"></i> إضافة مقياس أثر جديد
            </h1>
            <a href="{{ route('admin.impacts.index') }}"
                class="btn bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition duration-300 shadow-md whitespace-nowrap">
                <i class="fas fa-arrow-right ml-2"></i> العودة لقائمة مقاييس الأثر
            </a>
        </div>

        <form action="{{ route('admin.impacts.store') }}" method="POST" class="card bg-white p-6 shadow-md rounded-lg">
            @csrf

            <h3 class="text-xl font-bold mb-4 border-b pb-2 " style="color: var(--primary-hover-color);">1. ربط المشروع
                وتحديد التمويل
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

                <div class="form-group md:col-span-3">
                    <label for="project_id" class="block text-sm font-medium text-gray-700">اختر المشروع المرتبط <span
                            class="text-red-500">*</span></label>
                    <select name="project_id" id="project_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="">-- اختر مشروعًا --</option>
                        {{-- $projects تم تمريره من المتحكم --}}
                        @foreach ($projects as $id => $title)
                            <option value="{{ $id }}" {{ old('project_id') == $id ? 'selected' : '' }}>
                                {{ $title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="required_amount" class="block text-sm font-medium text-gray-700">المبلغ المطلوب ($) <span
                            class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="required_amount" id="required_amount"
                        value="{{ old('required_amount') }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        placeholder="مثال: 50000.00">
                </div>

                <div class="form-group">
                    <label for="raised_amount" class="block text-sm font-medium text-gray-700">المبلغ المجموع حتى الآن ($)
                        <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="raised_amount" id="raised_amount"
                        value="{{ old('raised_amount', 0) }}"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                        placeholder="مثال: 35000.00">
                    <p class="text-xs text-gray-500 mt-1">يجب أن يكون أقل من أو يساوي المبلغ المطلوب.</p>
                </div>

                <!-- <div class="form-group flex flex-col justify-end">
                                                        <label for="status" class="block text-sm font-medium text-gray-700">تفعيل مقياس الأثر</label>
                                                        <label class="switch mt-2">
                                                            <input type="hidden" name="status" value="0">
                                                            <input type="checkbox" name="status" id="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div> -->
            </div>

            <h3 class="text-xl font-bold mb-4 border-b pb-2 text-blue-700" style="color: var(--primary-hover-color);">2.
                الأهداف الكمية المحققة</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. الهدف
                        باللغة
                        العربية
                    </h3>

                    <div class="form-group">
                        <label for="goal_ar" class="block text-sm font-medium text-gray-700">الهدف الكلي (عربي) <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="goal_ar" id="goal_ar" value="{{ old('goal_ar') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            placeholder="مثال: توفير 5000 وحدة دواء">
                    </div>

                    <div class="form-group">
                        <label for="reached_ar" class="block text-sm font-medium text-gray-700">ما تم تحقيقه (عربي) <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="reached_ar" id="reached_ar" value="{{ old('reached_ar') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            placeholder="مثال: تم توفير 3500 وحدة دواء">
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-xl font-bold mb-6 border-b pb-3" style="color: var(--primary-color);">1. Goal in
                        English
                    </h3>

                    <div class="form-group">
                        <label for="goal_en" class="block text-sm font-medium text-gray-700">Total Goal (English) <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="goal_en" id="goal_en" value="{{ old('goal_en') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            placeholder="Example: Provide 5000 units of medicine">
                    </div>

                    <div class="form-group">
                        <label for="reached_en" class="block text-sm font-medium text-gray-700">What has been reached
                            (English) <span class="text-red-500">*</span></label>
                        <input type="text" name="reached_en" id="reached_en" value="{{ old('reached_en') }}"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            placeholder="Example: 3500 units of medicine provided">
                    </div>
                </div>
            </div>

            {{-- أزرار الإرسال والإلغاء --}}
            <div class="flex items-center justify-end mt-10 border-t pt-6">
                <a href="{{ route('admin.impacts.index') }}"
                    class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                    <i class="fas fa-times ml-2"></i> إلغاء
                </a>
                <button type="submit"
                    class="btn text-white font-bold py-2 px-6 rounded-lg shadow-lg transition duration-150"
                    style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                    <i class="fas fa-save ml-2"></i> تسجيل مقياس الأثر
                </button>
            </div>

            <!-- <div class="mt-8">
                            <button type="submit"
                                class="btn btn-success bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                                تسجيل مقياس الأثر
                            </button>
                        </div> -->
        </form>
    </div>
@endsection