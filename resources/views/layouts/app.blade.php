<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>{{ config('app.name', 'Arsip Digital') }}</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="{{ asset('') }}/favicon.ico">

    <!-- FontAwesome JS-->
    <script defer src="{{ asset('') }}/assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.min.css">
    <link id="theme-style" rel="stylesheet" href="{{ asset('') }}/assets/css/portal.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css"
        integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    
    @stack('scriptcss')

</head>

<body class="app">
    <header class="app-header fixed-top">
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')
    </header><!--//app-header-->

    <div class="app-wrapper">

        <div class="app-content pt-3 p-md-3 p-lg-4">
            @yield('content')
        </div><!--//app-content-->

        @include('layouts.partials.footer')

    </div><!--//app-wrapper-->


    <!-- Javascript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ asset('') }}/assets/plugins/popper.min.js"></script>
    <script src="{{ asset('') }}/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Charts JS -->
    <script src="{{ asset('') }}/assets/plugins/chart.js/chart.min.js"></script>

    <!-- Page Specific JS -->
    <script src="{{ asset('') }}/assets/js/app.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.bootstrap5.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/plugins/jquery-mask/jquery-mask.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scriptjs')

    <script>
        $('.uang').mask('000.000.000.000.000', {
            reverse: true
        });
        $('.year').mask('0000', {
            reverse: true
        });
        $('.number').mask('000000000', {
            reverse: true
        });
        //flatpicker
        flatpicker_config = {
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d"
        };
        $(".flatpicker").flatpickr(flatpicker_config);
    </script>

</body>

</html>
