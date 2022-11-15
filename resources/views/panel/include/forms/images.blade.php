@push('head')
@endpush
@push('foot')
    <script type="text/javascript">
        initUppy({
            slug: '{{$field->slug}}',
            target: '#Input{{$field->slug}}',
            url: '{{ route(sprintf('panel.%s.images.upload', $slug), [app()->getLocale(), $model->id]) }}',
            @if(app()->getLocale() != 'en')
            locale: Uppy.locales.{{__(sprintf('messages.languages.%s.uppy.iso', app()->getLocale()))}},
            @endif
        });

        initGallery({
            url: {
                removeImage: '{{ route(sprintf('panel.%s.image.delete', $slug), [app()->getLocale(), $model->id]) }}?path=',
                @if (route_is_defined(sprintf('panel.%s.images.featured.add', $slug)))
                featuredImage: '{{ route(sprintf('panel.%s.images.featured.add', $slug), [app()->getLocale(), $model->id]) }}?path=',
                @endif
            },
            queryCarousel: '.{{$field->slug}}-owl-carousel',
            @if (__('messages.dir') == 'rtl')
            rtl: true,
            @endif
        });
    </script>
@endpush
@include('panel.include.modal.caption')
<div class="form-group">
    <div class="form-group">
        <label for="Input{{$field->slug}}"
               class="mr-4">@lang(sprintf('messages.fields.%s',$field->slug))</label>
    </div>
    @if(isset($model) && $model->imagesByKey($field->slug) != null)
        <div class="{{$field->slug}}-owl-carousel owl-carousel owl-theme form-group">
            @foreach($model->imagesByKey($field->slug) as $itemId => $image)
                @include('panel.include.forms.image', ['image'=>$image,'slug'=>$field->slug,'captioning'=>false,'featured'=>false,'remove'=>true,'itemId'=>$itemId])
            @endforeach
        </div>
    @endif
    <div id="Input{{$field->slug}}"></div>
</div>
