@extends('panel.include.datatable.index')
@section('custom-script')
    <!-- Page specific script -->
    <script type="text/javascript">
        const $table = $("#main-table").DataTable({
            @include('panel.include.datatable.option')
            "ajax": "{{route(sprintf('panel.%s.data', $slug), app()->getLocale())}}",
            "columns": [
                {data: "name"},
                {data: "link"},
                {data: "min_packages_count"},
                // {data: "min_pieces_count"},
                {data: "inventory_packages_amount"},
                {data: "inventory_pieces_amount"},
                {data: "action", "width": "20%"},
            ],
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": true,
                    "searchable": true
                },
                {
                    "targets": [1],
                    "visible": true,
                    "searchable": true
                },
                {
                    "targets": [2],
                    "visible": true,
                    "searchable": false
                },
                // {
                //     "targets": [3],
                //     "visible": true,
                //     "searchable": false
                // },
                {
                    "targets": [3],
                    "visible": true,
                    "searchable": false
                },
                {
                    "targets": [4],
                    "visible": true,
                    "searchable": false
                },
                {
                    "targets": [5],
                    "visible": true,
                    "searchable": false,
                    "orderable": false,
                },
            ],
        });
    </script>
@endsection
