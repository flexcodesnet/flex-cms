@extends(\FXC\Base\Helpers\BaseHelper::getAdminMasterLayoutTemplate())
@section('styles')
    <link rel="stylesheet" href="{{ asset_version('vendor/core/packages/plugin-management/css/plugin.css') }}">
@stop
@section('content')
    <div id="plugin-list" class="clearfix app-grid--blank-slate row mx-2">
        @foreach ($list as $plugin)
            <div class="card card-outline card-primary app-card-item">
                <div class="app-item app-{{ $plugin->path }} p-3">
                    <div class="app-icon">
                        @if ($plugin->image)
                            <img src="data:image/png;base64,{{ $plugin->image }}" alt="{{ $plugin->name }}">
                        @endif
                    </div>
                    <div class="app-details">
                        <h4 class="app-name">{{ $plugin->name }}</h4>
                    </div>
                    <div class="app-footer">
                        <div class="app-description" title="{{ $plugin->description }}">{{ $plugin->description }}</div>
                        @if (!config('packages.plugin-management.general.hide_plugin_author', false))
                            <div class="app-author">{{ trans('packages/plugin-management::plugin.author') }}: <a
                                        href="{{ $plugin->url }}" target="_blank">{{ $plugin->author }}</a></div>
                        @endif
                        <div class="app-version">{{ trans('packages/plugin-management::plugin.version') }}
                            : {{ $plugin->version }}</div>
                        <div class="app-actions">
                            @if (role_permission_check('panel.plugins.edit'))
                                <button class="btn @if ($plugin->status) btn-warning @else btn-info @endif btn-trigger-change-status"
                                        data-plugin="{{ $plugin->path }}"
                                        data-status="{{ $plugin->status }}">@if ($plugin->status) {{ trans('packages/plugin-management::plugin.deactivate') }} @else {{ trans('packages/plugin-management::plugin.activate') }} @endif</button>
                            @endif

                            @if (role_permission_check('panel.plugins.remove'))
                                <button class="btn btn-danger btn-trigger-remove-plugin"
                                        data-plugin="{{ $plugin->path }}">{{ trans('packages/plugin-management::plugin.remove') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{--    {!! Form::modalAction('remove-plugin-modal', trans('packages/plugin-management::plugin.remove_plugin'), 'danger', trans('packages/plugin-management::plugin.remove_plugin_confirm_message'), 'confirm-remove-plugin-button', trans('packages/plugin-management::plugin.remove_plugin_confirm_yes')) !!}--}}
@stop

@section('scripts')
    <script src="{{asset_version('vendor/core/packages/plugin-management/js/plugin.js')}}"></script>
@stop