@extends('panel.layout')
@section('main-content')
    <section class="content">
        <div class="container-fluid">
            @forelse($menu_cards as $group)
                <div class="menu-card-group mb-4">
                    <h5 class="menu-card-group-title mb-3">@lang($group['title'])</h5>
                    <div class="row">
                        @foreach($group['items'] as $item)
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                <a href="{{ route($item['href']) }}" class="menu-card">
                                    <div class="menu-card-icon">
                                        <i class="fas {{ $item['icon'] }}"></i>
                                    </div>
                                    <div class="menu-card-body">
                                        <h6 class="menu-card-title">@lang($item['title'])</h6>
                                    </div>
                                    <div class="menu-card-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center text-muted py-5">
                        <i class="fas fa-lock fa-2x mb-3"></i>
                        <p class="mb-0">@lang('panel.messages.no_permissions')</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
@endsection
