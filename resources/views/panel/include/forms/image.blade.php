<div class="image-area">
    <a href="{{asset('storage/'.$image)}}"
       data-fancybox="{{$slug}}">
        <img src="{{asset('storage/'.$image)}}" class="img-fluid"
             alt="{{$slug}}">
    </a>
    <div class="{{$slug}} gallery action-btn">
        @if(isset($captioning) && $captioning)
            <a class="captioning-image" href="javascript:captionModal('{{$image}}')"
               style="display: inline;"><i class="fa fa-closed-captioning"></i></a>
        @endif
        @if(isset($featured) && $featured)
            <a class="featured-image" href="{{$image}}"
               style="display: inline;"><i class="fa fa-star"></i></a>
        @endif
        @if(isset($remove) && $remove)
            <a class="remove-image" href="{{$image}}" data-item="{{$itemId ?? ''}}"
               style="display: inline;"><i class="fa fa-trash"></i></a>
        @endif
    </div>
</div>
