@switch($field->inner_type)
    @case('select')
    <div class="form-group">
        <label
            for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
        <select class="form-control select2"
                id="Input{{$field->slug}}"
                name="{{$field->slug}}"
                @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                disabled
            @endif
            {{$field->required ? 'required' : ''}}>
            <option selected disabled>@lang('messages.fields.please_choose')</option>
            @foreach($field->items as $item)
                <option
                    value="{{$item}}"
                    @if(isset($model)&&$model[$field->slug] == $item)
                    selected
                    @endif>
                    @if(isset($field->title))
                        @lang(sprintf($field->title,$item))@else
                        @lang(sprintf('messages.enum.%s.%s',$field->slug,$item))
                    @endif
                </option>
            @endforeach
        </select>
    </div>
    @break
@endswitch
