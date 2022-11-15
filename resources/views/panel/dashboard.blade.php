@extends('panel.layout')
@push('head')
@endpush

@section('main-content')
    <section class="content dashboard">
        <div class="container-fluid">
            <div id="dashboard">
                <Dashboard
                    materials="{{$materials}}"
                    suppliers="{{$suppliers}}"
                    manufacturers="{{$manufacturers}}"

                    {{-- out-stock-count="{{$out_of_stock_count}}"
                    on-hold-order-count="{{$on_hold_order_count}}"
                    running-order-count="{{$running_order_count}}"
                    history-count="{{$history_order_count}}" --}}

                    dashboard-route="{{ route('panel.dashboard.api', app()->getLocale()) }}"
                    history-route="{{ route('panel.dashboard.orders.data.history', app()->getLocale()) }}"
                    ordered-route="{{ route('panel.dashboard.orders.data.ordered', app()->getLocale()) }}"
                    on-hold-route="{{ route('panel.dashboard.orders.data.onHold', app()->getLocale()) }}"
                    out-of-stock-route="{{ route('panel.dashboard.orders.data.outOfStock', app()->getLocale()) }}"
                    materials-route="{{route('panel.materials.index', app()->getLocale())}}"
                    manufacturers-route="{{route('panel.manufacturers.index', app()->getLocale())}}"

                    receive-order-route="{{ route('panel.dashboard.orders.update.receive', app()->getLocale()) }}"
                    run-order-route="{{ route('panel.dashboard.orders.update.run', app()->getLocale()) }}"
                    hold-order-route="{{ route('panel.dashboard.orders.update.hold', app()->getLocale()) }}"
                    cancel-order-route="{{ route('panel.dashboard.orders.update.cancel', app()->getLocale()) }}"

                    store-order-in-route="{{ route('panel.dashboard.orders.store.orderIn', app()->getLocale()) }}"
                    store-order-out-route="{{ route('panel.dashboard.orders.store.orderOut', app()->getLocale()) }}"
                />
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('foot')
    <script src="{{ asset_version('assets/adminlte/custom/script/vue.js') }}"></script>
@endpush
