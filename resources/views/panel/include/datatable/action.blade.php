<div class="row m-1 justify-content-around">
    @if((isset($is_single) && $is_single) && route_is_defined('web.'.($is_single_slug ?? $slug).'.single'))
        <div>
            <a target="_blank" href="{{route('web.'.($is_single_slug ?? $slug).'.single', [app()->getLocale(), $row->slug])}}"
               class="btn btn-block btn-default mb-1">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
    @endif
    @if(((isset($method) && $method == 'GET') || !isset($method)) && route_is_defined('panel.'.$slug.'.show')
    && role_permission_check('panel.'.$slug.'.show'))
        <div>
            <a href="{{route('panel.'.$slug.'.show', [app()->getLocale(), $row->id])}}" class="btn btn-info mb-1">
                <i class="fa fa-expand"></i>
            </a>
        </div>
        @if(isset($duplicate) && $duplicate)
            <div>
                <a href="{{$addRoute ?? route('panel.'.$slug.'.add', [app()->getLocale(), 'id'=>$row->id])}}"
                   class="btn btn-default mb-1">
                    <i class="fas fa-clone"></i>
                </a>
            </div>
        @endif
    @endif
    @if((isset($method) && $method != 'GET') || !isset($method))
        @if(route_is_defined('panel.'.$slug.'.edit') && role_permission_check('panel.'.$slug.'.edit'))
            <div>
                <a href="{{$editRoute ?? route('panel.'.$slug.'.edit', [app()->getLocale(), $row->id])}}" class="btn btn-success mb-1">
                    <i class="fa fa-edit"></i>
                </a>
            </div>
        @endif
        @if(route_is_defined('panel.'.$slug.'.delete') && role_permission_check('panel.'.$slug.'.delete'))
            <div>
                <a href="javascript:{{\App\Support\Str::replace('.','',$slug)}}delModal({{$row->id}})"
                   class="btn btn-danger mb-1">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        @endif
    @endif
</div>
