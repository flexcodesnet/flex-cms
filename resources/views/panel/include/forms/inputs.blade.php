<?php
$fieldLocal = isset($parentFieldLocal) ? '-'.$parentFieldLocal : '';
$fieldSlug = $field->slug.$fieldLocal;
$localFieldSlug = isset($parentFieldLocal) ? $parentFieldLocal.'['.$field->slug.']' : $field->slug;
$fieldLabel = __(sprintf('messages.fields.%s', $field->slug));
$fieldPlaceholder = __('messages.fields.enter')." $fieldLabel";
$fieldId = "input-{$fieldSlug}";

?>
@switch($field->type)
    @case('tags')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <input name="{{$localFieldSlug}}"
               id="{{$fieldId}}"
               class="tags form-control"
               @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
               readonly
               @endif
               @if(isset($model))
               @if(isset($model->translatable))
               @if(isset($field->key))
               value='{{in_array($field->key, $model->translatable) ? $model->getTranslationWithoutFallback($field->key, $parentFieldLocal ?? app()->getLocale()) : $model[$field->key]}}'
               @else
               value='{{in_array($field->slug, $model->translatable) ? $model->getTranslationWithoutFallback($field->slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug]}}'
               @endif
               @endif
               @if(isset($model->seoable) and in_array($field->slug, $model->seoable) )
               value='{{$model[$field->slug]}}'
                @endif
                @endif
        >
    </div>
    @push('foot')
        <script type="text/javascript">
            tagify('#{{$fieldId}}');
        </script>
    @endpush
    @break
    @case('code_editor')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <textarea id="{{$fieldId}}"
                  class="codeMirrorDemo p-3"
                  name="{{$field->slug}}">{{isset($model[$field->slug]) ? $model[$field->slug] : ''}}</textarea>
    </div>
    @push('foot')
        <script type="text/javascript">
            initCodeMirror("{{$fieldId}}");
        </script>
    @endpush
    @break
    @case('boolean')
    <div class="form-group row">
        <div class="col-4">
            <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        </div>
        <div class="col-6">
            <input type="checkbox" id="{{$fieldId}}"
                   name="{{$localFieldSlug}}"
                   @if(isset($model) && !empty(isset($model->translatable) && in_array($field->slug, $model->translatable) ? $model->getTranslationWithoutFallback($field->slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug]))
                   checked
                   @endif
                   @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                   disabled
                   @endif
                   data-on-text="@lang('messages.fields.yes')"
                   data-off-text="@lang('messages.fields.no')"
                   data-bootstrap-switch data-off-color="danger"
                   data-on-color="success">
        </div>
    </div>
    @break
    @case('image')
    @case('one_image')
    @include('panel.include.modal.caption')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        @if(isset($model) and isset($model[$field->slug]))
            <div class="form-group">
                @include('panel.include.forms.image', ['captioning'=>false,'featured'=>false,'remove'=>true])
            </div>
            @push('head')
                <style>
                    .custom-file-input:lang({{app()->getLocale()}}) ~ .custom-file-label::after {
                        content: "@lang('messages.fields.browse')" !important;
                    }
                </style>
            @endpush
            @push('foot')
                <script type="text/javascript">
                    removeImage({
                        query: '.{{$field->slug}} .remove-image',
                        url: '{{ route('panel.image.delete', [app()->getLocale(),$moduleName, $model->id]) }}?path=',
                    });
                </script>
            @endpush
        @endif
        <div class="custom-file">
            <input type="file" class="custom-file-input" id="{{$fieldId}}"
                   name="{{$field->slug}}" accept="image/*"
                   @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                   disabled
                    @endif>
            <label class="custom-file-label"
                   for="{{$fieldId}}">@lang('messages.fields.please_choose') {{$fieldLabel}}</label>
        </div>
    </div>
    @break
    @case('images')
    @if(isset($model))
        @include('panel.include.forms.images')
    @endif
    @break
    @case('featured_images')
    @if(isset($model) && $model->featuredImages() != null && $model->featuredImages()->count() > 0)
        <div class="form-group">
            <label
                    for="{{$fieldId}}">{{$fieldLabel}}</label>
            <ul id="image-list" class="ul-{{$field->slug}}">
                @foreach($model->featuredImages()->get() as $image)
                    <li>
                        @include('panel.include.forms.image', ['image'=>$image->path,'slug'=>$field->slug,'captioning'=>false,'featured'=>false,'remove'=>true,])
                    </li>
                @endforeach
            </ul>
        </div>
        @push('foot')
            <script type="text/javascript">
                sortable({
                    query: '.ul-{{$field->slug}}',
                    url: '{{route("panel.{$field->slug}.images.featured.update", [app()->getLocale(), $model->id])}}'
                });

                removeImage({
                    query: '.ul-{{$field->slug}} .remove-image',
                    url: '{{ route("panel.{$field->slug}.images.featured.delete", [app()->getLocale(), $model->id]) }}?path=',
                });
            </script>
        @endpush
    @endif
    @break
    @case('text')
    @case('number')
    @case('email')
    @case('tel')
    @case('url')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <input type="{{$field->type}}" class="form-control"
               id="{{$fieldId}}"
               name="{{$localFieldSlug}}"
               @if(isset($parentFieldLocal))
               dir="{{__(sprintf('messages.languages.%s.dir', $parentFieldLocal))}}"
               @endif
               @if(isset($model))
               value="{{isset($model->translatable) && in_array($field->slug, $model->translatable) ? $model->getTranslationWithoutFallback($field->slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug]}}"
               @endif
               @if(isset($field->value))
               value="{{$field->value}}"
               @endif
               @if($field->type == 'number')
               step="{{$field->step ?? 'any' ?? '0.0001'}}"
               @if(isset($field->min))
               min="{{$field->min}}"
               @endif
               @if(isset($field->max))
               max="{{$field->max}}"
               @endif
               @endif
               placeholder="{{$fieldPlaceholder}}"
               {{isset($field->required) && $field->required ? 'required' : ''}}
               @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
               disabled
                @endif>
    </div>
    @break
    @case('password')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <input type="{{$field->type}}" class="form-control"
               id="{{$fieldId}}"
               name="{{$field->slug}}"
               @if(isset($parentFieldLocal))
               dir="{{__(sprintf('messages.languages.%s.dir', $parentFieldLocal))}}"
               @endif
               placeholder="{{$fieldPlaceholder}}"
               {{isset($field->required) && $field->required ? 'required' : ''}}
               @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
               disabled
                @endif>
    </div>
    @break
    @case('textarea')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <textarea class="form-control" rows="3"
                  @if(isset($parentFieldLocal))
                  dir="{{__(sprintf('messages.languages.%s.dir', $parentFieldLocal))}}"
                  @endif
                  placeholder="{{$fieldPlaceholder}}"
                  id="{{$fieldId}}"
                  name="{{$localFieldSlug}}"
                  {{isset($field->required) && $field->required ? 'required' : ''}}
                  @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                  disabled
                  @endif>@if(isset($model)){!! str_replace('<br />','&#13;&#10;', (isset($model->translatable) && in_array($field->slug, $model->translatable) ? $model->getTranslationWithoutFallback($field->slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug])) !!}@endif</textarea>
    </div>
    @break
    @case('editor')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <textarea class="form-control text-editor" rows="3"
                  data-locale="{{__('messages.languages.'.$parentFieldLocal.'.code')}}"
                  placeholder="{{$fieldPlaceholder}}"
                  id="{{$fieldId}}"
                  name="{{$localFieldSlug}}"
                  {{isset($field->required) && $field->required ? 'required' : ''}}
                  @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                  disabled
                @endif>@if(isset($model)){{in_array($field->slug, $model->transFields) ? $model->translation($field->slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug]}}@endif</textarea>
    </div>
    @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
        @push('foot')
            <script type="text/javascript">
                window.editor['{{$fieldId}}'].isReadOnly = true;
            </script>
        @endpush
    @endif
    @break
    @case('nested')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <select class="form-control select2"
                id="{{$fieldId}}"
                name="{{$field->input_name ?? $field->nested->relation_key}}"
                style="width: 100%;"
                @if($field->required)
                required
                @endif
                @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                disabled
                @endif>
            <option selected value="">@lang('messages.fields.please_choose')</option>
            <?php
            $data = $field->nested->data;
            if (!$data and $field->nested->query) {
                $data = $field->nested->query->get();
            }
            ?>
            @foreach($data as $item)
                <option
                        @if((isset($model)))
                        @if(isset($model->model_class))
                        @if($model[$field->nested->relation_key] == $item->id && \Illuminate\Support\Str::contains($model->model_class,__(sprintf('messages.models.single.%s',$field->slug))))
                        selected="selected"
                        @endif
                        @elseif($model[$field->nested->relation_key] == $item->id)
                        selected="selected"
                        @endif
                        @endif
                        value="{{$item->id}}">{{$item->title}}</option>
            @endforeach
        </select>
    </div>
    @break
    @case('locale')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <select class="form-control select2"
                id="{{$fieldId}}"
                name="{{$field->slug}}"
                style="width: 100%;"
                @if($field->required)
                required
                @endif
                @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                disabled
                @endif>
            <option selected value="">@lang('messages.fields.please_choose')</option>
            @foreach($field->nested->data as $item)
                <?php $value = \FXC\Base\Supports\Str::slug($item->title);?>
                <option
                        @if((isset($model)))
                        @if(isset($model->model_class))
                        @if($model[$field->nested->id] == $item->id && \Illuminate\Support\Str::contains($model->model_class,__(sprintf('messages.models.single.%s',$field->slug))))
                        selected="selected"
                        @endif
                        @elseif($model[$field->slug] == $value)
                        selected="selected"
                        @endif
                        @endif
                        value="{{$value}}">{{$item->title}}</option>
            @endforeach
        </select>
    </div>
    @break
    @case('countries')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <select class="form-control"
                id="{{$fieldId}}"
                name="{{$field->slug}}"
                @if($field->required)
                required
                @endif
                @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                disabled
                @endif>
            <option selected disabled>@lang('messages.fields.please_choose')</option>
            @include('countries')
        </select>
    </div>
    @break
    @case('image-picker')
    <div class="form-group">
        <label for="{{$fieldId}}">@lang(sprintf('messages.models.single.%s',$field->slug))</label>
        <select class="image-picker show-html form-control">
            @foreach($field->query->get() as $item)
                <option data-img-src="{{asset('storage/'.$item->main_image)}}"
                        data-img-class="first"
                        data-img-alt="{{$item->title}}"
                        @if(isset($model) && $model[$field->relation_key] == $item->id)
                        selected
                        @endif
                        value="{{$item->id}}"> {{$item->main_image}}
                </option>
            @endforeach
        </select>
    </div>
    @push('foot')
        <script type="text/javascript">
            $("select.image-picker").imagepicker();
        </script>
    @endpush
    @break
    @case('treeview')
    <div class="form-group">
        <label for="{{$fieldId}}">@lang(sprintf('messages.models.single.%s',$field->slug))</label>
        @include('panel.include.forms.treeview')
    </div>
    @break
    @case('children')
    @if($method != 'POST' && (isset($model) && isset($model->{$field->slug}) && (isset($model->needChildren) && $model->needChildren)))
        <div class="form-group mt-5">
            <div class="row mb-2">
                <div class="col-6"><label
                            for="{{$fieldId}}">{{$fieldLabel}}</label>
                </div>
                @if(!(isset($method) && $method == 'GET'))
                    <div class="col-6 d-flex justify-content-end">
                        <div class="">
                            <a href="{{route(sprintf('panel.%s.%s.model.add', $moduleName, $field->slug), [app()->getLocale(), $model->id])}}"
                               class="btn btn-primary">@lang('messages.buttons.add')</a>
                        </div>
                    </div>
                @endif
            </div>
            @php
                $children = $model->{$field->slug};
            @endphp
            @includeWhen((isset($children) && $children->count() > 0), 'panel.include.forms.children')
        </div>
    @endif
    @break
    @case('date')
    <div class="form-group">
        <label for="{{$fieldId}}">{{$fieldLabel}}</label>
        <div class="input-group date" id="{{$fieldId}}"
             data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input"
                   id="{{$fieldId}}"
                   @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                   disabled
                   @endif
                   data-target="#{{$fieldId}}" name="{{$field->slug}}">
            <div class="input-group-append" data-target="#{{$fieldId}}"
                 data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
    </div>
    @push('foot')
        <script type="text/javascript">
            $('#{{$fieldId}}').datetimepicker({
                @if(isset($model) && isset($model[$field->slug]))
                defaultDate: '{{$model[$field->slug]->format('Y-m-d')}}',
                @endif
                viewMode: 'years',
                format: '{{$field->format ?? 'MM/YYYY'}}',
            });
        </script>
    @endpush
    @break
    @case('enum')
    @include('panel.include.forms.enum')
    @break
    @case('enum_relation')
    @if($method != 'POST' && (isset($model)))
        @include('panel.{$moduleName}.enum')
    @endif
    @break
    @case('multi_select')
    <div class="form-group">
        <input type="hidden" id="hidden{{$fieldSlug}}"
               name="{{$localFieldSlug}}"
               @if(isset($model) && isset($model[$field->nested->relation]))
               value="{{isset($parentFieldLocal) ? $model->getTranslationWithoutFallback($field->nested->relation, $parentFieldLocal ?? app()->getLocale()) : $model[$field->nested->relation]->pluck('id')->implode(',')}}"
                @endif
        >
        <label for="{{$fieldId}}">@lang(sprintf('messages.models.plural.%s',$field->slug))</label>
        <select class="select2" multiple="multiple"
                data-placeholder="@lang('messages.fields.please_choose')"
                id="{{$fieldId}}"
                @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                disabled
                @endif
                @if(isset($field->maximum_selection_length))
                data-maximum-selection-length="{{$field->maximum_selection_length}}"
                @endif
                style="width: 100%;">
            @foreach(isset($parentFieldLocal) ? $field->nested->query[$parentFieldLocal]->where('locale', $parentFieldLocal)->get() : $field->nested->query->get() as $item)
                <option
                        value="{{$item->id}}"
                        @if(isset($model) && isset($model[$field->nested->relation]))
                        @if(isset($parentFieldLocal))
                        @if(in_array($item->id, explode(',',$model->getTranslationWithoutFallback($field->nested->relation, $parentFieldLocal ?? app()->getLocale()))))
                        selected
                        @endif
                        @else
                        @if(in_array($item->id, $model[$field->nested->relation]->pluck('id')->toArray()))
                        selected
                        @endif
                        @endif
                        @endif
                >{{ $item->{$field->title??'title'} }}</option>
            @endforeach
        </select>
    </div>
    @push('foot')
        <script type="text/javascript">
            initMultiSelect({
                queryInput: '#{{$fieldId}}',
                queryHidden: '#hidden{{$fieldSlug}}',
            });
        </script>
    @endpush
    @break
    {{--    @case('custom')--}}
    {{--    @includeWhen(in_array($method, $field->methods), $field->view)--}}
    {{--    @break--}}
    @default
    @break
@endswitch
