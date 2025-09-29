<!DOCTYPE html>
<html class="no-js" lang="fa">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>elyas - @yield('title')</title>

    {{-- استایل‌های دستی از پوشه public --}}
    <link href="{{asset('/css/home.css')}}" rel="stylesheet">

    {{-- استایل‌های کامپایل شده با Vite --}}
    @vite(['resources/scss/home/home.scss', 'resources/js/home/home.js'])

    {{-- جایگاه استایل‌های اختصاصی هر صفحه --}}
    @stack('styles')
</head>

<body id="page-top" @if(session('swal-success')) data-swal-success="{{ session('swal-success') }}" @endif>

   <div class="wrapper">

        @include('home.sections.header')

        @include('home.sections.mobile_off_canvas')

        @yield('content')

        @yield('script')

        @include('home.sections.footer')



    </div>


    {{-- اسکریپت‌های دستی از پوشه public (jQuery باید اول باشد) --}}
    <script src="{{ asset('js/home/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('js/home/plugins.js') }}"></script>

    {{-- جایگاه اسکریپت‌های اختصاصی هر صفحه --}}
    @stack('scripts')
</body>
</html>
