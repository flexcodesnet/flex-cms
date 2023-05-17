@extends('panel.include.datatable.index')
@section('custom-script')
    <!-- Page specific script -->
    <script type="text/javascript">
        const $table = $("#main-table").DataTable({
            @include('panel.include.datatable.option')
            "ajax": "{{route(sprintf('panel.%s.data', $slug))}}",
            "columns": [
                {data: "id", "width": "20%"},
                {data: "title"},
                {data: "action", "width": "20%"},
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": true,
                    "searchable": false
                },
                {
                    "targets": [1],
                    "visible": true,
                    "searchable": true
                },
                {
                    "targets": [2],
                    "visible": true,
                    "searchable": false,
                    "orderable": false,
                },
            ],
        });
    </script>
@endsection
