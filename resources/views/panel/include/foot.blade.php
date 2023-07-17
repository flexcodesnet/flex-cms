<!-- jQuery -->
<script src="{{ asset_version('assets/adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script
    src="{{ asset_version('assets/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset_version('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- pace-progress -->
<script src="{{ asset_version('assets/adminlte/plugins/pace-progress/pace.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset_version('assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- Toastr -->
<script src="{{asset_version('assets/adminlte/plugins/toastr/toastr.min.js')}}"></script>
<!-- CKEditor 5 -->
<script src="{{asset_version('assets/adminlte/plugins/ckeditor5/ckeditor.js')}}"></script>
<!-- CodeMirror -->
<script src="{{asset_version('assets/adminlte/plugins/codemirror/codemirror.js')}}"></script>
<script src="{{asset_version('assets/adminlte/plugins/codemirror/mode/css/css.js')}}"></script>
<script src="{{asset_version('assets/adminlte/plugins/codemirror/mode/xml/xml.js')}}"></script>
<script src="{{asset_version('assets/adminlte/plugins/codemirror/mode/htmlmixed/htmlmixed.js')}}"></script>
<!-- InputMask -->
<script src="{{asset_version('assets/adminlte/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset_version('assets/adminlte/plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset_version('assets/adminlte/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script
    src="{{asset_version('assets/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Bootstrap Switch -->
<script src="{{asset_version('assets/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset_version('assets/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- bs-custom-file-input -->
<script src="{{asset_version('assets/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<!-- image-picker -->
<script src="{{asset_version('assets/adminlte/plugins/image-picker/image-picker.min.js')}}"></script>
<!-- uppy JavaScript file uploader -->
<script src="https://releases.transloadit.com/uppy/v1.24.0/uppy.min.js"></script>
<!-- fancybox -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<!-- OwlCarousel2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- tagify -->
<script src="https://unpkg.com/@yaireo/tagify@3.21.5/dist/tagify.min.js"></script>
<script src="https://unpkg.com/@yaireo/dragsort@1.0.8/dist/dragsort.js"></script>
@if(app()->getLocale() != 'en')
    <script src="{{__(sprintf('panel.languages.%s.uppy.url', app()->getLocale()))}}"></script>
    <script
        src="{{asset_version('assets/adminlte/plugins/ckeditor5/translations/'.app()->getLocale().'.js')}}"></script>
@endif
<script type="text/javascript">
    const $local = '{{app()->getLocale()}}';
    const $debug = '{{config('app.debug') == true ? 1 : 0}}' === '1';
    const $token = $('meta[name="csrf-token"]').attr('content');
    const $please_choose = '@lang('panel.fields.please_choose')';
    toastr.options.preventDuplicates = true;
    toastr.options.timeOut = 500 * 2; // How long the toast will display without user interaction
    $(function () {
        @if($errors->any())
        toastr.error('{{$errors->first()}}');
        @endif
        @if(session()->has('warnings'))
        toastr.warning('{{session()->get('warnings')}}');
        @endif
        @if(session()->has('success'))
            @if(request()->routeIs('panel.languages.index'))
                toastr.options.onHidden = function () {
                    window.location.reload(true);
                };
            @endif
        toastr.success('{{session()->get('success')}}');
        @endif
        @if(isset($error))
        toastr.error('{{$error}}');
        @endif
        @if(isset($warning))
        toastr.warning('{{$warning}}');
        @endif
        @if(isset($success))
        toastr.success('{{$success}}');
        @endif
    });
</script>
<!-- AdminLTE App -->
<script src="{{ asset_version('assets/adminlte/dist/js/adminlte.js') }}"></script>
<script src="{{ asset_version('assets/adminlte/custom/script/script.js') }}"></script>
{{--<!-- AdminLTE for demo purposes -->--}}
{{--<script src="{{ asset_version('assets/adminlte/dist/js/demo.js') }}"></script>--}}
@stack('foot')
