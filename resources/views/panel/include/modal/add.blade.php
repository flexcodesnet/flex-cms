@push('head')
@endpush
@push('foot')
    <script type="text/javascript">
        function {{$slug}}addModal($id) {
            addModal({
                id: $id,
                query: '#{{$slug}}add-modal',
                url: "{{ route(sprintf('panel.%s.create', $slug),[':id',':id']) }}".replace(':id', $id),
            });
        }
    </script>
@endpush
<div id="{{$slug}}add-modal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="loading" class="overlay d-flex justify-content-center align-items-center invisible">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="modal-header">
                <h4 class="modal-title">Add Child</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{$action}}" method="POST">
                <div class="modal-body">
                    <!-- form start -->
                    @foreach($fields as $field)
                        @php
                            $field = (object)$field;
                            if (isset($field->required) && !$field->required) continue;
                        @endphp
                        @switch($field->type)
                            @case('text')
                            @case('number')
                            @case('email')
                            <div class="form-group">
                                <label
                                    for="Input{{$field->slug}}">@lang(sprintf('panel.fields.%s',$field->slug))</label>
                                <input type="{{$field->type}}" class="form-control"
                                       id="Input{{$field->slug}}"
                                       name="{{$field->slug}}"
                                       placeholder="@lang('panel.fields.enter') @lang(sprintf('panel.fields.%s',$field->slug))"
                                    {{isset($field->required) && $field->required ? 'required' : ''}}
                                    {{isset($field->disabled) && $field->disabled ? 'disabled' : ''}}>
                            </div>
                            @break
                            @default
                            @break
                        @endswitch
                    @endforeach
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" data-dismiss="modal"
                            class="btn btn-default">@lang('panel.buttons.cancel')</button>
                    <button type="submit" class="btn btn-primary" id="add">
                        @lang('panel.buttons.add')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
