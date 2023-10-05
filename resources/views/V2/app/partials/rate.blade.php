
@for($i=1; $i<=5; $i++)
    @if($i < $stars)
        <i class="fa fa-star text-warning"></i>
    @else
        @if( ceil($stars) == $i)
            <i class="fa fa-star-half-alt text-warning"></i>
        @else
            <i class="fa fa-star"></i>
        @endif
    @endif
@endfor