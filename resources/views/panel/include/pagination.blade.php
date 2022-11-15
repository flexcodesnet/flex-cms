<nav aria-label="Page Navigation">
    <ul class="pagination justify-content-center m-0">
        <li class="page-item">
            <a class="page-link" href="{{$first_page}}">
                <i class="fas fa-angle-double-left"></i>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="{{$previous_page}}">
                <i class="fas fa-angle-left"></i>
            </a>
        </li>
        @foreach($url_range as $key => $item)
            @if($key > 0)
                <li class="page-item {{$key == $paginator->currentPage()?'active' : ''}}">
                    <a class="page-link" href="{{$item}}">{{$key}}</a>
                </li>
            @endif
        @endforeach
        <li class="page-item"><a class="page-link" href="{{$next_page}}">
                <i class="fas fa-angle-right"></i>
            </a></li>
        <li class="page-item">
            <a class="page-link" href="{{$last_page}}">
                <i class="fas fa-angle-double-right"></i>
            </a>
        </li>
    </ul>
</nav>
