@extends('layouts.admin')
@section('title', 'إدارة المشاريع')

{{-- يفترض أن layout/admin.blade.php يحتوي على Alpine.js ومكون Delete Modal --}}
@section('content')

    {{-- CSS مخصص لزر التبديل (Toggle Switch) --}}
    
    <div class="p-6 md:p-10" x-data="{}">

        {{-- ================================================= --}}
        {{-- 1. العنوان وزر الإضافة --}}
        {{-- ================================================= --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-gray-800" style="color: var(--secondary-color);">
                <i class="fas fa-folder-open ml-2" style="color: var(--primary-color);"></i> إدارة المشاريع
            </h1>
            @can('create_project')
                <a href="{{ route('admin.projects.create') }}"
                    class="btn bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300 shadow-lg whitespace-nowrap"
                    style="background-color: var(--primary-color);">
                    <i class="fas fa-plus ml-2"></i> إضافة مشروع جديد
                </a>
            @endcan
        </div>

       
         {{-- ================================================= --}}
        {{-- 2. نموذج البحث (Search Form) --}}
        {{-- * افتراض أن البحث يتم على حقل 'q' ويتم إرساله إلى نفس المسار * --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl p-5 mb-8">
            <h3 class="text-lg font-bold mb-3 text-gray-700">البحث والتصفية</h3>
            <form action="{{ route('admin.projects.index') }}" method="GET"
                class="flex flex-wrap items-center gap-4">
                
                <input type="text" name="q" placeholder="ابحث بالعنوان أو الخلاصة..." value="{{ request('q') }}"
                    class="flex-1 min-w-[200px] border border-gray-300 rounded-lg py-2 px-4 text-gray-700 focus:outline-none focus:ring-2 focus:border-transparent"
                    style="focus-ring-color: var(--primary-color-light);">

                <button type="submit"
                    class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                    <i class="fas fa-search"></i>
                </button>

                {{-- زر مسح البحث (يظهر فقط إذا كان هناك معيار بحث) --}}
                @if (request()->has('q') && request('q') != '')
                    <a href="{{ route('admin.projects.index') }}"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
        

        {{-- ================================================= --}}
        {{-- 3. جدول البيانات --}}
        {{-- ================================================= --}}
        <div class="card bg-white shadow-xl rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-gray-600 uppercase text-sm font-semibold">
                            <th class="py-3 px-6 text-right">#</th>
                            <th class="py-3 px-6 text-center">الصورة</th>
                            <th class="py-3 px-6 text-right">العنوان (عربي)</th>
                            <th class="py-3 px-6 text-right max-w-xs">الخلاصة (عربي)</th>
                            <th class="py-3 px-6 text-center">الحالة</th>
                            <th class="py-3 px-6 text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm font-light">
                        @forelse($projects as $index => $project)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-300">
                                <td class="py-4 px-6 text-right whitespace-nowrap font-semibold text-gray-800">
                                    {{ $projects->firstItem() + $index }}
                                </td>
                                {{-- الصورة --}}
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    @if ($project->image)
                                        <img src="{{ asset('images/' . $project->image) }}" alt="{{ $project->title_ar }}"
                                            class="w-16 h-16 object-cover rounded-lg shadow-md mx-auto">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 text-xs">
                                            لا صورة
                                        </div>
                                    @endif
                                </td>
                                {{-- العنوان --}}
                                <td class="py-4 px-6 text-right whitespace-nowrap">
                                    {{ $project->title_ar }}
                                </td>
                                {{-- الخلاصة --}}
                                <td class="py-4 px-6 text-right max-w-xs text-wrap break-words">
                                    {{ Str::limit($project->summary_ar, 80) }}
                                </td>
                                {{-- الحالة (Toggle Switch) --}}
                                <td class="py-4 px-6 text-center">
                                    <form action="{{ route('admin.projects.toggle-status', $project) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="switch">
                                            <input type="checkbox" name="status_toggle" onchange="this.form.submit()"
                                                {{ $project->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </form>
                                </td>
                                {{-- الإجراءات --}}
                                <td class="px-6 py-3 text-center whitespace-nowrap text-sm space-x-3 space-x-reverse">
                                    <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                        {{-- زر التعديل --}}
                                        @can('edit_project')
                                            <a href="{{ route('admin.projects.edit', $project->id) }}" title="تعديل"
                                                class="text-blue-600 hover:text-blue-900 transition duration-150 p-2 rounded-full hover:bg-blue-50">
                                                 <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        {{-- زر الحذف (يطلق حدث Alpine.js) --}}
                                        @can('delete_project')
                                            <button type="button"
                                            class="text-red-600 hover:text-red-900 transition duration-150 p-2 rounded-full hover:bg-red-50 js-delete-trigger" 
                                        data-action="{{ route('admin.projects.destroy', $project->id) }}" 
                                        data-title="{{ $project->title_ar }}" 
                                        title="حذف">
                                        <i class="fas fa-trash-alt"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-6 text-center text-lg text-gray-500">
                                    <i class="fas fa-box-open text-3xl mb-3"></i>
                                    <p>لا توجد مشاريع مسجلة حتى الآن.</p>
                                    @can('create_project')
                                        <a href="{{ route('admin.projects.create') }}"
                                            class="text-blue-600 hover:underline mt-2 inline-block">ابدأ بإضافة أول مشروع.</a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- التصفح (Pagination) --}}
            @if ($projects->hasPages())
                <div class="p-4 border-t bg-gray-50">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection