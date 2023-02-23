@extends('panel.include.datatable.index')
@section('custom-script')
    <!-- Page specific script -->
    <script type="text/javascript">
        var _columns = [];
        _columns.push({data: "id"})
        @foreach($availableFields as $key=>$field)
        _columns.push({
            data: '{{$field->slug}}',
            name: '{{$field->name}}',
            className: 'align-middle ',
            orderable: Boolean({{$field->sortable}}),
            searchable: Boolean({{$field->searchable}}),
            {{-- visible: Boolean({{$key <= 5 or ($field->default_visible??false)}}),--}}
        })
        @endforeach
        _columns.push({data: "action", "width": "15%"})

        const $table = $("#main-table").DataTable({
            @include('panel.include.datatable.option')
            "ajax": "{{route(sprintf('panel.%s.data', $moduleName), app()->getLocale())}}",
            "columns": _columns,
        });
    </script>
@endsection
