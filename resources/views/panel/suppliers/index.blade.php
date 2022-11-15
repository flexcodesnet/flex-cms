@extends('panel.include.datatable.index')
@section('custom-script')
    <!-- Page specific script -->
    <script type="text/javascript">
        const $table = $("#main-table").DataTable({
            @include('panel.include.datatable.option')
            "ajax": "{{route(sprintf('panel.%s.data', $slug), app()->getLocale())}}",
            "columns": [
                {data: "name"},
                {data: "email"},
                {data: "sale_person_name"},
                {data: "sale_person_phone_number"},
                {data: "preferred_ordering_method"},
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
                    "searchable": true
                },
                {
                    "targets": [3],
                    "visible": true,
                    "searchable": true
                },
                {
                    "targets": [4],
                    "visible": true,
                    "searchable": true
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
