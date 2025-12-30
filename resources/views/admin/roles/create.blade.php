@extends('layouts.admin')

@section('page_title', 'إنشاء دور جديد')

@section('content')
    <div class="p-6 md:p-10">

        {{-- ================================================= --}}
        {{-- 1. العنوان والعودة --}}
        {{-- ================================================= --}}
        <div class="flex items-center mb-8">
            <a href="{{ route('admin.roles.index') }}"
                class="text-gray-500 hover:text-gray-700 transition duration-300 ml-4">
                <i class="fas fa-arrow-right text-2xl"></i>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-user-plus ml-3 text-xl" style="color: var(--primary-color);"></i> إنشاء دور جديد
            </h1>
        </div>
        {{-- ================================================= --}}
        {{-- 3. نموذج إنشاء الدور --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl p-6 md:p-8">
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf

                {{-- حقل اسم الدور --}}
                <div class="mb-6">
                    <label for="name" class="block text-lg font-medium text-gray-700 mb-2">اسم الدور (باللغة
                        الإنجليزية)</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                        placeholder="مثل: editor أو moderator">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- اختيار الصلاحيات --}}
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">اختيار الصلاحيات</h3>
                    <p class="text-sm text-gray-500 mb-6">قم بتحديد الصلاحيات اللازمة لهذا الدور، وهي منظمة حسب المورد.</p>

                    <!-- مربع التحكم الشامل (Master Checkbox) -->
                    <div class="bg-indigo-50 border border-indigo-200 p-4 rounded-xl shadow-md flex items-center mb-6">
                        <input type="checkbox" id="master-select-all"
                            class="h-6 w-6 text-indigo-700 rounded-md focus:ring-indigo-600 border-indigo-400">
                        <label for="master-select-all"
                            class="ml-3 mr-3 text-lg font-semibold text-indigo-800 cursor-pointer">
                            تحديد/إلغاء تحديد الكل
                        </label>
                        <span class="text-sm text-indigo-600">(يؤثر على جميع الصلاحيات أدناه)</span>
                    </div>

                    @if(isset($permissions) && $permissions->isNotEmpty())

                        {{-- تجميع الصلاحيات حسب المورد --}}
                        @php
                            // يجب أن يكون $permissions هو مجموعة Laravel (Collection)
                            $groupedPermissions = $permissions->groupBy(function ($item) {
                                $parts = explode(' ', $item->name);
                                return end($parts); // مثل 'posts' أو 'users'
                            });
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($groupedPermissions as $resource => $perms)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 shadow-sm">
                                    <h4 class="font-bold text-indigo-700 mb-3 border-b pb-2 flex justify-between items-center"
                                        style="border-color: var(--primary-color); ">
                                        <span>صلاحيات {{ $resource }}</span>
                                        <label class="inline-flex items-center text-sm font-normal text-gray-600">
                                            <input type="checkbox"
                                                class="group-select-all h-4 w-4 text-indigo-500 rounded focus:ring-indigo-500"
                                                data-group="{{ Str::slug($resource) }}">
                                            <span class="ml-2 mr-2 text-xs">تحديد المجموعة</span>
                                        </label>
                                    </h4>

                                    <div class="space-y-2">
                                        @foreach ($perms as $permission)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="perm_{{ $permission->id }}" name="permissions[]"
                                                    value="{{ $permission->id }}" {{-- تمت إضافة الفئتين الهامتين هنا للربط
                                                    بالجافاسكريبت --}}
                                                    class="permission-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                                    data-group="{{ Str::slug($resource) }}" style="color: var(--primary-color);">
                                                <label for="perm_{{ $permission->id }}"
                                                    class="mr-3 text-sm font-medium text-gray-700 cursor-pointer">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500 py-6">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <p>لم يتم العثور على صلاحيات مسجلة في النظام. تأكدي من تنفيذ ملفات Seeds.</p>
                        </div>
                    @endif
                </div>

                {{-- زر الإرسال --}}
                <div class="flex justify-end pt-4 border-t">
                    <a href="{{ route('admin.roles.index') }}"
                        class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 font-bold py-2 px-6 rounded-lg mr-3 transition duration-150 shadow-md">
                        <i class="fas fa-times ml-2"></i> إلغاء
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-md transition duration-300 shadow-md inline-flex items-center font-bold"
                        style="background-color: var(--primary-color); hover:background-color: var(--secondary-color);">
                        <i class="fas fa-save ml-2"></i> حفظ الدور والصلاحيات
                    </button>
                </div>
            </form>
        </div>

        <!-- JavaScript لتشغيل وظائف الاختيار/الإلغاء -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const masterCheckbox = document.getElementById('master-select-all');
                const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
                const groupSelectAllCheckboxes = document.querySelectorAll('.group-select-all');

                /**
                 * الوظيفة الشاملة: اختيار/إلغاء اختيار جميع مربعات الصلاحيات والمجموعات.
                 */
                function setAllPermissions(checked) {
                    permissionCheckboxes.forEach(checkbox => {
                        checkbox.checked = checked;
                    });
                    groupSelectAllCheckboxes.forEach(checkbox => {
                        checkbox.checked = checked;
                        checkbox.indeterminate = false;
                    });
                    masterCheckbox.checked = checked;
                    masterCheckbox.indeterminate = false;
                }

                /**
                 * تحديث مربع التحكم الشامل ومربع اختيار المجموعة بناءً على تغيير الصلاحيات الفردية.
                 */
                function updateGroupAndMasterCheckboxes() {
                    let totalCheckedPermissions = 0;
                    let totalPermissions = permissionCheckboxes.length;

                    // 1. تحديث حالة مربعات اختيار المجموعات الفرعية أولاً
                    groupSelectAllCheckboxes.forEach(groupSelectAll => {
                        const groupName = groupSelectAll.dataset.group;
                        const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]`);
                        const checkedGroupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]:checked`);

                        totalCheckedPermissions += checkedGroupCheckboxes.length;

                        // تحديث حالة مربع اختيار المجموعة 
                        if (checkedGroupCheckboxes.length === 0) {
                            groupSelectAll.checked = false;
                            groupSelectAll.indeterminate = false;
                        } else if (checkedGroupCheckboxes.length === groupCheckboxes.length) {
                            groupSelectAll.checked = true;
                            groupSelectAll.indeterminate = false;
                        } else {
                            groupSelectAll.checked = false;
                            groupSelectAll.indeterminate = true;
                        }
                    });

                    // 2. تحديث حالة مربع التحكم الشامل (Master Checkbox)
                    if (masterCheckbox) {
                        if (totalCheckedPermissions === 0) {
                            masterCheckbox.checked = false;
                            masterCheckbox.indeterminate = false;
                        } else if (totalCheckedPermissions === totalPermissions) {
                            masterCheckbox.checked = true;
                            masterCheckbox.indeterminate = false;
                        } else {
                            masterCheckbox.checked = false;
                            masterCheckbox.indeterminate = true;
                        }
                    }
                }

                // --- ربط الأحداث (Event Listeners) ---

                // 1. عند تغيير مربع التحكم الشامل (Master Checkbox)
                if (masterCheckbox) {
                    masterCheckbox.addEventListener('change', function () {
                        // *** التعديل الرئيسي هنا: إزالة setTimeout لضمان التزامن ***
                        setAllPermissions(this.checked);
                    });
                }

                // 2. عند تغيير مربع اختيار المجموعة (Group Checkbox)
                groupSelectAllCheckboxes.forEach(groupCheckbox => {
                    groupCheckbox.addEventListener('change', function () {
                        const groupName = this.dataset.group;
                        const isChecked = this.checked;

                        // تعيين حالة جميع الصلاحيات الفردية في هذه المجموعة
                        document.querySelectorAll(`.permission-checkbox[data-group="${groupName}"]`).forEach(checkbox => {
                            checkbox.checked = isChecked;
                        });

                        // تحديث حالة المربع الشامل بعد تغيير المجموعة
                        updateGroupAndMasterCheckboxes();
                    });
                });

                // 3. عند تغيير أي صلاحية فردية
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateGroupAndMasterCheckboxes);
                });

                // تشغيل التحديث الأولي 
                updateGroupAndMasterCheckboxes();
            });
        </script>
    </div>
@endsection