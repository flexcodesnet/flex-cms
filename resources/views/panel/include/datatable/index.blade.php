@extends('panel.layout')

@push('head')
    <!-- DataTables -->
    <link rel="stylesheet"
          href="{{ asset_version('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"
          ?v={{config('app.version')}}>
    <link rel="stylesheet"
          href="{{ asset_version('assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"
          ?v={{config('app.version')}}>
    <link rel="stylesheet"
          href="{{ asset_version('assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"
          ?v={{config('app.version')}}>
@endpush

@push('foot')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset_version('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script
        src="{{ asset_version('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script
        src="{{ asset_version('assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script
        src="{{ asset_version('assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script
        src="{{ asset_version('assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script
        src="{{ asset_version('assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset_version('assets/adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset_version('assets/adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset_version('assets/adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset_version('assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset_version('assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset_version('assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    @yield('custom-script')
@endpush
@push('modal')
    @if(route_is_defined(sprintf('panel.%s.delete', $slug)))
        @include('panel.include.modal.delete')
    @endif
@endpush
@section('main-content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div id="table-buttons"></div>
                                </div>
                                <div class="col-6 d-flex justify-content-end">
                                    @if(route_is_defined(sprintf('panel.%s.add', $slug)) && role_permission_check(sprintf('panel.%s.add', $slug)))
                                        <div class="">
                                            <a href="{{route(sprintf('panel.%s.add', $slug), app()->getLocale())}}"
                                               class="btn btn-block btn-primary">@lang('messages.buttons.add')</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <table id="main-table" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    @if($with_id ?? true)
                                        <th>@lang('messages.fields.id')</th>
                                    @endif
                                    @foreach($ths as $th)
                                        <th>{{__($th)}}</th>
                                    @endforeach
                                    <th class="noExport">@lang('messages.fields.action')</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
