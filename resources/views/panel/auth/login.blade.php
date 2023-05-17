<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
@push('head')
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset_version('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@endpush
@include('panel.include.head')
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{route('web.index')}}" class="h2 d-flex justify-center align-items-center text-center">
                <hr>
                <span class="brand-text text-center"
                      style="width: 100%;font-weight: 700 !important;font-size: 1.7rem;">{{__('messages.title')}}</span>
            </a>
        </div>
        <div class="card-body">
            <p class="login-box-msg">@lang('messages.buttons.sign_in')</p>

            <form method="POST" action="{{ route('panel.auth') }}">
                @csrf

                <div class="input-group mb-3">
                    <input class="form-control"
                           placeholder="{{__('messages.fields.username')}} @lang('messages.fields.or') {{__('messages.fields.email')}}"
                           type="text"
                           name="username" required autofocus/>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input class="form-control" placeholder="{{__('messages.fields.password')}}" type="password"
                           name="password" required
                           autocomplete="current-password"/>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">@lang('messages.buttons.remember_me')</label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit"
                                class="btn btn-primary btn-block">@lang('messages.buttons.sign_in')</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

@include('panel.include.foot')

</body>
</html>
