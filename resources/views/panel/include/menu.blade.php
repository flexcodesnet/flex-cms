<?php
$menu->has_allowed_menus = false;
$activeLinks = collect();
if (isset($menu->menus)) {
    foreach ($menu->menus as $subMenuItem) {
        if (isset($subMenuItem->href)) {
            $menu->has_allowed_menus = $menu->has_allowed_menus || role_permission_check($subMenuItem->href);
        }
    }
    $activeLinks = $activeLinks->merge($menu->menus)->pluck('href');
}
if (isset($menu->active)) {
    $activeLinks = $activeLinks->merge($menu->active);
}
$menuIsActive = request()->routeIs($activeLinks->toArray());
?>
@if((isset($menu->href) && role_permission_check($menu->href)) || ($menu->has_allowed_menus))
    <li class="nav-item {{$menuIsActive ? 'menu-open' : ''}}">
        <a href="{{isset($menu->href) ? route($menu->href, isset($menu->params) ? $menu->params : app()->getLocale()) : 'javascript:void(0)'}}"
           class="nav-link {{$menuIsActive ? 'active' : ''}}">
            <i class="nav-icon fas {{$menu->icon??'far fa-circle'}}"></i>
            <p>
                @lang($menu->title)
                @if(isset($menu->menus))
                    <i class="right fas fa-angle-left"></i>
                @endif
            </p>
        </a>
        @if(isset($menu->menus))
            <ul class="nav nav-treeview">
                @foreach($menu->menus as $subMenu)
                    @if(role_permission_check($subMenu->href))
                        <li class="nav-item">
                            @php
                                if (isset($subMenu->params) && isset($subMenu->params['locale']))
                                    $subMenu->params['locale'] = app()->getLocale();
                            @endphp
                            <a href="{{route($subMenu->href, isset($subMenu->params) ? $subMenu->params : app()->getLocale())}}"
                               class="nav-link {{request()->routeIs($subMenu->active) ? 'active' : ''}} {{ (isset($subMenu->params) && !is_null(request()->route()) && in_array(request()->route()->parameter('slug'), $subMenu->params)) ? 'active' : ''}}">
                                <i class="fas {{$subMenu->icon??'far fa-circle'}} nav-icon"></i>
                                <p>@lang($subMenu->title)</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endif
    </li>
@endif

