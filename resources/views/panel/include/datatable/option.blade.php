"paging": true,
"searching": true,
"ordering": {{(isset($with_ordering) && $with_ordering) ? 'true' : 'false'}},
"info": true,
"responsive": true,
"lengthChange": true,
"autoWidth": false,
"cash": true,
"serverSide": true,
"language": {
"url": "{{__(sprintf('panel.languages.%s.datatable', app()->getLocale()))}}"
},
"initComplete": function (settings, json) {
$table.buttons().container().appendTo('#table-buttons')
},
@if(role_permission_check('panel.'.$slug.'.export'))
    "buttons": ["copy", "csv", /*"excel",*/ {
    extend: 'pdf',
    footer: true,
    exportOptions: {
    columns: "thead th:not(.noExport)"
    }
    }, "print"/*, "colvis"*/],
@endif
