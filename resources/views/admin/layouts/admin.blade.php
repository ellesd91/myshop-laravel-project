<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Webprog.ir @yield('title')</title>

  {{-- استایل پلاگین --}}
  <link rel="stylesheet" href="{{ asset('css/jquery.md.bootstrap.datetimepicker.style.css') }}">

  {{-- استایل‌ها و اسکریپت‌های پروژه (Vite) --}}
  @vite(['resources/scss/admin/admin.scss', 'resources/js/admin/admin.js'])
</head>

<body id="page-top" data-swal-success="{{ session('swal-success') }}">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    @include('admin.sections.sidebar')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        @include('admin.sections.topbar')
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
          @yield('content')
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      @include('admin.sections.footer')
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  @include('admin.sections.scroll_top')

  {{-- پلاگین باید بعد از لود شدن jQuery و admin.js اجرا بشه --}}
  <script>
(function () {
  // صبر می‌کنیم تا jQuery بالا بیاد
  function loadPlugin() {
    if (!window.jQuery) return setTimeout(loadPlugin, 50);

    var s = document.createElement('script');
    s.src = "{{ asset('js/jquery.md.bootstrap.datetimepicker.js') }}";
    s.onload = function () {
      console.log('MDP loaded →', typeof jQuery.fn.MdPersianDateTimePicker);
      if (window.initMDP) window.initMDP(); // وقتی پلاگین لود شد، اینیت اینپوت‌ها
    };
    document.body.appendChild(s);
  }
  loadPlugin();
})();
</script>


  @yield('script')
  @stack('scripts')
  @stack('styles')
</body>
</html>
