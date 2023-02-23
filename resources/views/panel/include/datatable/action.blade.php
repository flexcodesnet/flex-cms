<div class="row m-1 justify-content-around">
    @if((isset($is_single) && $is_single) && route_is_defined('web.'.($is_single_slug ?? $slug).'.single'))
        <div>
            <a target="_blank"
               href="{{route('web.'.($is_single_slug ?? $slug).'.single', [app()->getLocale(), $row->slug])}}"
               class="btn btn-block btn-default mb-1">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
    @endif

    @if(((isset($method) && $method == 'GET') || !isset($method)) && route_is_defined("panel.{$moduleName}.show")
    && role_permission_check("panel.{$moduleName}.show"))
        <div>
            <a href="{{route("panel.{$moduleName}.show", [app()->getLocale(), $row->id])}}"
               class="btn btn-sm btn-outline-success mb-1">
                <i class="fa fa-eye"></i>
            </a>
        </div>
        @if(isset($duplicate) && $duplicate)
            <div>
                <a href="{{$addRoute ?? route("panel.{$moduleName}.add", [app()->getLocale(), 'id'=>$row->id])}}"
                   class="btn btn-success mb-1">
                    <i class="fas fa-clone"></i>
                </a>
            </div>
        @endif
    @endif
    @if(route_is_defined("panel.{$moduleName}.seo") && role_permission_check("panel.{$moduleName}.seo") && method_exists($row,'meta_tags'))
        <div>
            <a href="{{$editRoute ?? route("panel.{$moduleName}.seo", [app()->getLocale(), $row->id])}}"
               class="btn btn-sm btn-outline-info mb-1">
                <i class="fab fa-yoast"></i>
            </a>
        </div>
    @endif
    @if((isset($method) && $method != 'GET') || !isset($method))
        @if(route_is_defined("panel.{$moduleName}.edit") && role_permission_check("panel.{$moduleName}.edit"))
            <div>
                <a href="{{$editRoute ?? route("panel.{$moduleName}.edit", [app()->getLocale(), $row->id])}}"
                   class="btn btn-sm btn-outline-info mb-1">
                    <i class="fa fa-edit"></i>
                </a>
            </div>
            {{--            todo--}}
            {{--            @if($slug == 'image')--}}
            {{--                <div>--}}
            {{--                    <a href="{{route('panel.{$moduleName}.images.index', [app()->getLocale(), $row->id])}}"--}}
            {{--                       class="btn btn-warning mb-1">--}}
            {{--                        <i class="fa fa-image"></i>--}}
            {{--                    </a>--}}
            {{--                </div>--}}
            {{--            @endif--}}
        @endif
        @if(route_is_defined("panel.{$moduleName}.delete") && role_permission_check("panel.{$moduleName}.delete"))
            <div>
                <a href="javascript:{{\Illuminate\Support\Str::replace('.','',$moduleName)}}delModal({{$row->id}})"
                   class="btn btn-sm btn-outline-danger mb-1">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        @endif
    @endif
</div>
