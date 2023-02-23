<?php
$fieldType = $field->type;
$fieldName = $field->slug;
$rowAttrValue = $row->{$fieldName};
?>

@switch($fieldType)
    @case('image')
    @case('on_image')
    <img src="{{ $row->{"{$fieldName}Url"} }}" width="75px"/>
    @case('multi_select')
    <span>
        {{ collect($rowAttrValue)->pluck('title')->join(',') }}
    </span>
    @break
    @case('select')
    @case('nested')
    <span>
        {{ optional($row->{$field->nested->relation})->title }}
    </span>
    @break
    @case('boolean')
    <i class="{{$rowAttrValue?"fa fa-check":"fa fa-times"}}"></i> {{$rowAttrValue?"Yes":"NO"}}
    @break
    @default
    @switch($fieldName)
        @case('views')
        <i class="fa fa-eye"></i> {{$rowAttrValue}}
        @break
        @default
        {{$rowAttrValue}}
        @break
    @endswitch
@endswitch

