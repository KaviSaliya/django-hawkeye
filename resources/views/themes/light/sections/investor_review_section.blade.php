


<!-- investor review -->
<section class="investor-review">
    <div class="testimonial-doller-shape">
        <img src="{{asset($themeTrue.'images/shape/dollor-2.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <h3>{!! $investor_review_section['single']['heading']??'' !!}</h3>
                <p>{!! $investor_review_section['single']['short_text']??'' !!}</p>
            </div>
        </div>
        <div class="investor-review-carousol pagination-style-one">
            <div class="four-item-carousel swiper-container">
                <div class="swiper-wrapper">

                    @php
                        $numberOfLoop =(int)count(collect($investor_review_section['multiple'])->toArray())/2;
                        $itemList = collect($investor_review_section['multiple'])->toArray();
                        $numberOfItem = count($itemList);
                        $k = 0;
                    @endphp

                    @for($i = 1; $i  <= (int)$numberOfLoop; $i++)
                        <div class="swiper-slide">
                            <div class="investor-review-item  wow fadeInUp" data-wow-delay="100ms" data-wow-duration="1500ms">
                                @for($j = 1; $j <=2 ; $j++)
                                    <div class="investor-review-content">
                                        <div class="investor-review-info">
                                            <div class="investor">
                                                <img src="{{getFile($itemList[$k]['media']->investor_image->driver,$itemList[$k]['media']->investor_image->path)}}" alt="image">
                                                <div class="info">
                                                    <h5>{{$itemList[$k]['investor_name']}}</h5>
                                                    <p>{{$itemList[$k]['position']}}</p>
                                                </div>
                                            </div>
                                            <div class="review">
                                                <h6><i class="fa-sharp fa-solid fa-star"></i>{{$itemList[$k]['media']->rating}}</h6>
                                            </div>
                                        </div>
                                        <div class="investor-review-message">
                                            <p>{{strip_tags($itemList[$k]['review'])}}</p>
                                        </div>
                                    </div>

                                    @php(++$k)
                                @endfor
                                @if($numberOfItem % 2 == 0)
                                    @break($k == $numberOfItem-1)
                                @else
                                    @break($k == $numberOfItem-2)
                                @endif

                            </div>
                        </div>
                    @endfor

                </div>
            </div>
            <div class="slider__pagination3"></div>
            <div class="swiper-button-next">
                <i class="fa-regular fa-arrow-right"></i>
            </div>
            <div class="swiper-button-prev">
                <i class="fa-regular fa-arrow-left"></i>
            </div>

        </div>
    </div>
</section>
<!-- investor review -->
