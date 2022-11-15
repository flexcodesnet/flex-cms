@php
    $menu = (object)$menu;
    $menu->has_allowed_menus = false;
    if(isset($menu->menus))
    foreach ($menu->menus as $inner_menu) {
    $inner_menu = (object)$inner_menu;
    if (isset($inner_menu->href))
    $menu->has_allowed_menus = $menu->has_allowed_menus ||
    role_permission_check($inner_menu->href);
    }
@endphp
@if((isset($menu->href) && role_permission_check($menu->href)) || ($menu->has_allowed_menus))
    <li class="nav-item {{isset($menu->menus) && request()->routeIs($menu->active) ? 'menu-open' : ''}}">
        <a href="{{isset($menu->href) ? route($menu->href, isset($menu->params) ? $menu->params : app()->getLocale()) : 'javascript:void(0)'}}"
           class="nav-link {{request()->routeIs($menu->active) ? 'active' : ''}}">
            <i class="nav-icon fas {{$menu->icon}}"></i>
            <p>
                @lang($menu->title)
                @if(isset($menu->menus))
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if(isset($menu->menus))
            <ul class="nav nav-treeview">
                @foreach($menu->menus as $inner_menu)
                    @php
                        $inner_menu = (object)$inner_menu;
                    @endphp
                    @if(role_permission_check($inner_menu->href))
                        <li class="nav-item">
                            @php
                                if (isset($inner_menu->params) && isset($inner_menu->params['locale']))
                                    $inner_menu->params['locale'] = app()->getLocale();
                            @endphp
                            <a href="{{route($inner_menu->href, isset($inner_menu->params) ? $inner_menu->params : app()->getLocale())}}"
                               class="nav-link {{request()->routeIs($inner_menu->active) ? 'active' : ''}} {{ (isset($inner_menu->params) && !is_null(request()->route()) && in_array(request()->route()->parameter('slug'), $inner_menu->params)) ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>@lang($inner_menu->title)</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </li>
@endif
