<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary">
    <!-- Brand Logo -->
    <a href="{{route('panel.index')}}" class="brand-link">
        <img src="{{asset_version('assets/adminlte/custom/img/favicon.svg')}}" alt="Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{__('messages.title')}}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                @if(true)
                    <li class="nav-item {{request()->routeIs('panel.index') ? 'menu-open' : ''}}">
                        <a href="{{route('panel.index')}}"
                           class="nav-link {{request()->routeIs('panel.index') ? 'active' : ''}}">
                            <i class="nav-icon fas fa-h-square"></i>
                            <p>
                                @lang('messages.fields.welcome')
                            </p>
                        </a>
                    </li>
                @endif
                @foreach($menus as $menu)
                    @php
                        $menu = (object)$menu;
                    @endphp
                    @if(isset($menu->children))
                        @if(role_permission_check($menu->hrefs))
                            <li class="nav-header">
                                @lang($menu->title)
                            </li>
                            @foreach($menu->children as $child)
                                @include('panel.include.menu', ['menu'=>$child])
                            @endforeach
                            @continue
                        @endif
                    @endif
                    @include('panel.include.menu')
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
