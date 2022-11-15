@switch($field->type)
    @case('tags')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <input name="{{isset($parentFieldLocal) ? $parentFieldLocal.'['.$field->slug.']' : $field->slug}}"
                   id="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}"
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
                @endif
            >
        </div>
        @push('foot')
            <script type="text/javascript">
                tagify('#Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}');
            </script>
        @endpush
        @break
    @case('code_editor')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <textarea id="Input{{$field->slug}}"
                      class="codeMirrorDemo p-3"
                      name="{{$field->slug}}"
            >{{isset($model[$field->slug]) ? $model[$field->slug] : ''}}</textarea>
        </div>
        @push('foot')
            <script type="text/javascript">
                initCodeMirror("Input{{$field->slug}}");
            </script>
        @endpush
        @break
    @case('boolean')
        <div class="form-group row">
            <div class="col-4">
                <label
                    for="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            </div>
            <div class="col-6">
                <input type="checkbox" id="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}"
                       name="{{isset($parentFieldLocal) ? $parentFieldLocal.'['.$field->slug.']' : $field->slug}}"
                       @if(isset($model) && !empty(isset($model->translatable) && in_array($field->init_slug, $model->translatable) ? $model->getTranslationWithoutFallback($field->init_slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug]))
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
    @case('one_image')
        @include('panel.include.modal.caption')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            @if(isset($model) and isset($model[$field->slug]))
                <div class="form-group">
                    @include('panel.include.forms.image', ['image'=>$model[$field->slug],'slug'=>$field->slug,'captioning'=>false,'featured'=>false,'remove'=>true])
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
                            url: '{{ route(sprintf('panel.%s.image.delete', $slug), [app()->getLocale(), $model->id]) }}?path=',
                        });
                    </script>
                @endpush
            @endif
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="Input{{$field->slug}}"
                       name="{{$field->slug}}" accept="image/*"
                       @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                           disabled
                    @endif>
                <label class="custom-file-label"
                       for="Input{{$field->slug}}">@lang('messages.fields.please_choose') @lang(sprintf('messages.fields.%s',$field->slug))</label>
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
                    for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
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
            <label
                for="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <input type="{{$field->type}}" class="form-control"
                   id="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}"
                   name="{{isset($parentFieldLocal) ? $parentFieldLocal.'['.$field->slug.']' : $field->slug}}"
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
                   placeholder="@lang('messages.fields.enter') @lang(sprintf('messages.fields.%s',$field->slug))"
                   {{isset($field->required) && $field->required ? 'required' : ''}}
                   @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                       disabled
                @endif>
        </div>
        @break
    @case('password')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <input type="{{$field->type}}" class="form-control"
                   id="Input{{$field->slug}}"
                   name="{{$field->slug}}"
                   @if(isset($parentFieldLocal))
                       dir="{{__(sprintf('messages.languages.%s.dir', $parentFieldLocal))}}"
                   @endif
                   placeholder="@lang('messages.fields.enter') @lang(sprintf('messages.fields.%s',$field->slug))"
                   {{isset($field->required) && $field->required ? 'required' : ''}}
                   @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                       disabled
                @endif>
        </div>
        @break
    @case('textarea')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <textarea class="form-control" rows="3"
                      @if(isset($parentFieldLocal))
                          dir="{{__(sprintf('messages.languages.%s.dir', $parentFieldLocal))}}"
                      @endif
                      placeholder="@lang('messages.fields.enter') @lang(sprintf('messages.fields.%s',$field->slug))"
                      id="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}"
                      name="{{isset($parentFieldLocal) ? $parentFieldLocal.'['.$field->slug.']' : $field->slug}}"
                      {{isset($field->required) && $field->required ? 'required' : ''}}
                      @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                          disabled
                  @endif>@if(isset($model))
                    {!! str_replace('<br />','&#13;&#10;', (isset($model->translatable) && in_array($field->slug, $model->translatable) ? $model->getTranslationWithoutFallback($field->slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug])) !!}
                @endif</textarea>
        </div>
        @break
    @case('editor')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <textarea class="form-control text-editor" rows="3"
                      data-locale="{{__('messages.languages.'.$parentFieldLocal.'.code')}}"
                      placeholder="@lang('messages.fields.enter') @lang(sprintf('messages.fields.%s',$field->slug))"
                      id="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}"
                      name="{{isset($parentFieldLocal) ? $parentFieldLocal.'['.$field->slug.']' : $field->slug}}"
                      {{isset($field->required) && $field->required ? 'required' : ''}}
                      @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                          disabled
                @endif>@if(isset($model))
                    {{in_array($field->slug, $model->myTranslatable) ? $model->translate($field->slug, $parentFieldLocal ?? app()->getLocale()) : $model[$field->slug]}}
                @endif</textarea>
        </div>
        @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
            @push('foot')
                <script type="text/javascript">
                    window.editor['Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}'].isReadOnly = true;
                </script>
            @endpush
        @endif
        @break
    @case('select')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.models.%s.single',$field->slug))</label>
            <select class="form-control select2"
                    id="Input{{$field->slug}}"
                    name="{{$field->input_name ?? $field->relation_key}}"
                    style="width: 100%;"
                    @if($field->required)
                        required
                    @endif
                    @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                        disabled
                @endif>
                <option selected value="x">@lang('messages.fields.please_choose')</option>
                @foreach($field->query->get() as $item)
                    <option
                        @if((isset($model)))
                            @if(isset($model->model_class))
                                @if($model[$field->relation_key] == $item->id && \App\Support\Str::contains($model->model_class,__(sprintf('messages.models.%s.single',$field->slug))))
                                    selected="selected"
                        @endif
                        @elseif($model[$field->relation_key] == $item->id)
                            selected="selected"
                        @endif
                        @endif
                        value="{{$item->id}}">{{$item->title}}</option>
                @endforeach
            </select>
        </div>
        @break
    @case('countries')
        <div class="form-group">
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <select class="form-control"
                    id="Input{{$field->slug}}"
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
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.models.%s.single',$field->slug))</label>
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
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.models.%s.single',$field->slug))</label>
            @include('panel.include.forms.treeview')
        </div>
        @break
    @case('children')
        @if($method != 'POST' && (isset($model) && isset($model->{$field->slug}) && (isset($model->needChildren) && $model->needChildren)))
            <div class="form-group mt-5">
                <div class="row mb-2">
                    <div class="col-6"><label
                            for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
                    </div>
                    @if(!(isset($method) && $method == 'GET'))
                        <div class="col-6 d-flex justify-content-end">
                            <div class="">
                                <a href="{{route(sprintf('panel.%s.%s.model.add',$slug,$field->slug), [app()->getLocale(), $model->id])}}"
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
            <label
                for="Input{{$field->slug}}">@lang(sprintf('messages.fields.%s',$field->slug))</label>
            <div class="input-group date" id="Input{{$field->slug}}"
                 data-target-input="nearest">
                <input type="text" class="form-control datetimepicker-input"
                       id="Input{{$field->slug}}"
                       @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                           disabled
                       @endif
                       data-target="#Input{{$field->slug}}" name="{{$field->slug}}">
                <div class="input-group-append" data-target="#Input{{$field->slug}}"
                     data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                </div>
            </div>
        </div>
        @push('foot')
            <script type="text/javascript">
                $('#Input{{$field->slug}}').datetimepicker({
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
            @include('panel.'.$slug.'.enum')
        @endif
        @break
    @case('multi_select')
        <div class="form-group">
            <input type="hidden" id="hidden{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}"
                   name="{{isset($parentFieldLocal) ? $parentFieldLocal.'['.$field->slug.']' : $field->slug}}"
                   @if(isset($model) && isset($model[$field->relation]))
                       value="{{isset($parentFieldLocal) ? $model->getTranslationWithoutFallback($field->relation, $parentFieldLocal ?? app()->getLocale()) : $model[$field->relation]->pluck('id')->implode(',')}}"
                @endif
            >
            <label
                for="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}">@lang(sprintf('messages.models.%s.plural',$field->slug))</label>
            <select class="select2" multiple="multiple"
                    data-placeholder="@lang('messages.fields.please_choose')"
                    id="Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}"
                    @if((isset($method) && $method == 'GET') || (isset($field->disabled) && $field->disabled))
                        disabled
                    @endif
                    @if(isset($field->maximum_selection_length))
                        data-maximum-selection-length="{{$field->maximum_selection_length}}"
                    @endif
                    style="width: 100%;">
                @foreach(isset($parentFieldLocal) ? $field->query[$parentFieldLocal]->where('locale', $parentFieldLocal)->get() : $field->query->get() as $item)
                    <option
                        value="{{$item->id}}"
                        @if(isset($model) && isset($model[$field->relation]))
                            @if(isset($parentFieldLocal))
                                @if(in_array($item->id, explode(',',$model->getTranslationWithoutFallback($field->relation, $parentFieldLocal ?? app()->getLocale()))))
                                    selected
                        @endif
                        @else
                            @if(in_array($item->id, $model[$field->relation]->pluck('id')->toArray()))
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
                    queryInput: '#Input{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}',
                    queryHidden: '#hidden{{$field->slug}}{{isset($parentFieldLocal) ? '-'.$parentFieldLocal:''}}',
                });
            </script>
        @endpush
        @break
    @case('custom')
        @includeWhen(in_array($method, $field->methods), $field->view)
        @break
    @default
        @break
@endswitch
