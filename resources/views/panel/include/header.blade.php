<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <div class="nav-link" id="current-time"></div>
            <script>
                function display() {
                    document.getElementById('current-time').innerHTML = new Date().toLocaleString();
                }

                setInterval(display, 1000);
            </script>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
                {{strtoupper(app()->getLocale())}}
            </a>
            <div class="dropdown-menu dropdown-menu-right p-0">
                @foreach(get_translated_routes() as $local=>$item)
                    <a href="{{$item}}"
                       class="dropdown-item {{app()->getLocale() == $local ? 'active' : ''}}">
                        {{__('messages.languages.'.$local.'.title')}}
                    </a>
                @endforeach
            </div>
        </li>
        @if(!is_null(auth()->user()))
            <li class="nav-item dropdown user-menu">
                <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
                    <i class="fas fa-user-alt"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <p>{{auth()->user()->name}} - {{auth()->user()->role->title}}</p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        @if(role_permission_check('panel.users.edit'))
                            <a href="{{route('panel.users.edit', [app()->getLocale(), auth()->id()])}}"
                               class="btn btn-default btn-flat">@lang('messages.buttons.profile')</a>
                        @endif
                        <a href="{{route('panel.logout', app()->getLocale())}}"
                           class="btn btn-default btn-flat">@lang('messages.buttons.sign_out')</a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
</nav>
<!-- /.navbar -->
