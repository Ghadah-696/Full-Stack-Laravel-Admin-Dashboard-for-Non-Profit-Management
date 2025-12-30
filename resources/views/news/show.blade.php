@extends('layouts.app')

@section('page_title', 'إدارة الأخبار')

@section('content')
    <h1>{{ $news->title_ar }}</h1>
    <p>
        <img src="{{ asset('images/' . $news->image) }}" alt="{{ $news->title_ar }}" style="max-width: 500px;">
    </p>
    <p>
        <strong>الملخص:</strong> {{ $news->summary_ar }}
    </p>
    <div>
        <strong>المحتوى:</strong>
        <p>{{ $news->body_ar }}</p>
    </div>
    <a href="{{ route('admin.news.index') }}"
        class="px-4 py-2 text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 transition duration-300">العودة
        إلى
        قائمة الأخبار</a>
@endsection