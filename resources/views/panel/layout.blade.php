<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ __('messages.dir') }}">
@include('panel.include.head')
<body class="cms-page hold-transition sidebar-mini layout-fixed">
@stack('modal')
<div class="wrapper">
@include('panel.include.header')
@include('panel.include.sidebar')
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    @if(!isset($with_header) || (isset($with_header) && $with_header))
        <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">@lang($title)</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @if (isset($slug))
                                    @if (!request()->routeIs(sprintf('panel.%s.index', $slug)) )
                                        <li class="breadcrumb-item">
                                            <a href="{{ route(sprintf('panel.%s.index', $slug), app()->getLocale()) }}">
                                                @lang(sprintf('messages.models.plural.%s', $slug))
                                            </a>
                                        </li>
                                    @endif
                                    @if ((isset($model) && isset($model->parent)) || isset($parent_model))
                                        <li class="breadcrumb-item active">
                                            <a href="{{ route(sprintf('panel.%s.edit', $slug), [app()->getLocale(), $parent_model->id ?? $model->parent->id]) }}">
                                                {{ sprintf('%s %s', __(sprintf('messages.models.single.%s', $slug)), $parent_model->id ?? $model->parent->id) }}
                                            </a>
                                        </li>
                                    @endif
                                @endif
                            </ol>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
        @endif
        @yield('main-content')
        @yield('content')
    </div>
    <!-- /.content-wrapper -->
    @include('panel.include.footer')
</div>
<!-- ./wrapper -->
@include('panel.include.foot')
</body>
</html>
<!-- ######## Version {{config('app.version')}} ######## -->
