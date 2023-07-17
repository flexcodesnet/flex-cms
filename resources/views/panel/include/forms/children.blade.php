@push('head')
@endpush
@push('foot')
@endpush
@push('modal')
    @if($field->slug == 'children')
        @include('panel.include.modal.delete',[])
    @else
        @include('panel.include.modal.delete',['inner_action'=>route(sprintf('panel.%s.delete', $slug.'.'.$field->slug.'.model'),[$model->id,':id']),'slug'=>$slug.'.'.$field->slug.'.model'])
    @endif
@endpush
<table class="table table-hover table-bordered">
    <thead>
    <tr>
        <th style="width: 10%">@lang('panel.fields.id')</th>
        @foreach($field->ths as $th)
            @if(\App\Support\Str::contains($th, '.'))
                <th>{{__($th)}}</th>
            @else
                <th>{{__('panel.fields.'.$th)}}</th>
            @endif
        @endforeach
        <th style="width: 25%">@lang('panel.fields.action')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($children as $key => $child)
        @if(isset($child))
            <tr>
                <td>{{$key + 1}}</td>
                @if(isset($field->ths))
                    @foreach($field->ths as $th)
                        <td>{{ $child->{$th} }}</td>
                    @endforeach
                @endif
                <td>
                    @php
                        $row = $child;
                        if (!isset($row->id) || (isset($field->mustUseKey) && $field->mustUseKey))
                            $row->id = $key;
                    @endphp
                    @if($field->slug == 'children')
                        @include('panel.include.datatable.action',['is_single'=>false,])
                    @else
                        @include('panel.include.datatable.action',['is_single'=>false,'slug'=>$slug.'.'.$field->slug.'.model','addRoute'=>route(sprintf('panel.%s.add', $slug.'.'.$field->slug.'.model'),[$model->id,$row->id]),'editRoute'=>route(sprintf('panel.%s.edit', $slug.'.'.$field->slug.'.model'),[$model->id,$row->id])])
                    @endif
                </td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
