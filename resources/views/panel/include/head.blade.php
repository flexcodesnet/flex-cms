<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="robots" content="noindex, noimageindex, nofollow, nosnippet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    {{--    @if(config('app.env') == 'production')--}}
    {{--        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">--}}
    {{--    @endif--}}
    <title>{{ __(isset($page_title) ? $page_title : $title) }} - @lang('messages.title')</title>
    <link rel="shortcut icon" href="{{asset('assets/adminlte/custom/img/favicon.svg')}}" type="image/x-icon">
    <!-- Google Font: Tajawal -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700&display=fallback">
    <!-- Ionicons -->
    <link href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset_version('assets/adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <!-- flag-icon-css -->
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- flag-icon-css -->
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/jquery-ui/jquery-ui.css') }}">
    <!-- pace-progress -->
    <link rel="stylesheet"
          href="{{ asset_version('assets/adminlte/plugins/pace-progress/themes/black/pace-theme-flat-top.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/toastr/toastr.min.css') }}">
    <!-- CodeMirror -->
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/codemirror/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/codemirror/theme/monokai.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
          href="{{ asset_version('assets/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet"
          href="{{ asset_version('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset_version('assets/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- image-picker -->
    <link rel="stylesheet"
          href="{{asset_version('assets/adminlte/plugins/image-picker/image-picker.css')}}">
    <!-- uppy JavaScript file uploader -->
    <link href="https://releases.transloadit.com/uppy/v1.24.0/uppy.min.css" rel="stylesheet">
    <!-- fancybox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"/>
    <!-- OwlCarousel2 -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
    <!-- tagify -->
    <link href="https://unpkg.com/@yaireo/tagify@3.21.5/dist/tagify.css" rel="stylesheet">
    <link href="https://unpkg.com/@yaireo/dragsort@1.0.8/dist/dragsort.css" rel="stylesheet">
    @stack('head')
<!-- Theme style -->
    @if (__('messages.dir') == 'rtl')
        <link href="{{ asset_version('assets/adminlte/dist/css/adminlte.rtl.min.css') }}" rel="stylesheet">
        <link href="{{ asset_version('assets/adminlte/custom/style/rtl.css') }}" rel="stylesheet">
    @else
        <link href="{{ asset_version('assets/adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    @endif
{{--    <link href="{{ asset_version('assets/adminlte/custom/style/style.css') }}" rel="stylesheet">--}}
    @stack('style')
</head>
