
    @if ($paginator->hasPages())
        <ul class="" data-wow-duration="1s" data-wow-delay="0.35s">

            @if ($paginator->onFirstPage())
                <li class="disabled page-item">
                    <a href="javascript:void(0)" class="" aria-label="Previous">
                       <i class="fa-regular fa-angle-left"></i>
                    </a>
                </li>
            @else
                <li class="">
                    <a href="{{ $paginator->previousPageUrl() }}" class="" rel="prev"><i class="fa-regular fa-angle-left"></i></a>
                </li>
            @endif


            @foreach ($elements as $element)

                @if (is_string($element))
                    <li class="">
                        <a href="javascript:void(0)" class="">{{ $element }}</a>
                    </li>
                @endif


                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="">
                                <a href="javascript:void(0)" class="active">{{ $page }}<span class="sr-only">(current)</span></a>
                            </li>
                        @else
                            <li class="">
                                <a href="{{ $url}}" class="">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach


            @if ($paginator->hasMorePages())
                <li class="">
                    <a href="{{ $paginator->nextPageUrl() }}" class="" rel="next"><i class="fa-regular fa-angle-right"></i></a>
                </li>
            @else
                <li class="disabled ">
                    <a href="javascript:void(0)" class="disabled" aria-label="Next">
                        <i class="fa-regular fa-angle-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    @endif

