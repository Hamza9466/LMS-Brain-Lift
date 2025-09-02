

@php
    $cats = $cats ?? \App\Models\CourseCategory::orderBy('name')->get(['id','name','slug','name as title']);
@endphp
@include('website.layouts.head')


@include('website.layouts.navbar')


@yield('content')
@include('website.layouts.footer')
@include('website.layouts.script')
