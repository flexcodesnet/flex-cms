@push('head')
@endpush
@push('foot')
    <!-- tree.js -->
    <script src="{{ asset_version('assets/adminlte/plugins/treejs/tree.min.js') }}"></script>
    <script type="text/javascript">
        const myTree = initTreeView({
            all: '@lang('messages.fields.all')',
            slug: '{{$field->slug}}',
            query: '#{{$field->slug}}',
            data: JSON.parse('{!! isset($field->treeViewModel)? $field->treeViewModel->treeView():$field->model->treeView() !!}'), // prettier-ignore
            @if(isset($values))
            values: {!! $values !!},
            @endif
            disabled: {{((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled)) ? 'true':'false'}},
        });
    </script>
@endpush
<input type="hidden" name="{{$field->slug}}">
<div id="{{$field->slug}}"></div>
