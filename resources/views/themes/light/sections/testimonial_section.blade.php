

<!-- testimonial -->
<section class="testimonial">
    <div class="testimonial-shape-1">
        <img src="{{asset($themeTrue.'images/shape/light-left.png')}}" alt="shape">
    </div>
    <div class="testimonial-doller-shape">
        <img src="{{asset($themeTrue.'images/shape/dollor-2.png')}}" alt="shape">
    </div>
    <div class="earth-round">
        <img src="{{asset($themeTrue.'images/shape/earth-round.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <div class="common-title-top-right">
                    <img src="{{asset($themeTrue.'images/shape/title-top-shape.png')}}" alt="shape">
                </div>
                <h3>{!! styleSentence($testimonial_section['single']['heading']??null,2) !!}</h3>
                <p>{!! $testimonial_section['single']['short_description'] !!}</p>
            </div>
        </div>
        <div class="three-item-carousel swiper-container farming-carousol-container">
            <div class="swiper-wrapper">
                @foreach(collect($testimonial_section['multiple'])->toArray() as $key => $item)
                    <div class="swiper-slide" data-wow-delay="00ms" data-wow-duration="1500ms">
                        <div class="testimonial-single">
                            <div class="testimonial-single-image">
                                <img src="{{isset($item['media']->image)?getFile($item['media']->image->driver,$item['media']->image->path):getFile('local','image')}}" alt="image">
                            </div>
                            <div class="testimonial-info">
                                <h5>{{$item['investor_name']??''}}</h5>
                                <p>{!! $item['position'] !!}</p>
                                <ul class="rating">
                                    @for($i = 1; $i <= $item['media']->rating??0;$i++)
                                        <li><i class="fa-solid fa-star"></i></li>
                                    @endfor
                                    @for($i = 1; $i <= 5 - (int)$item['media']->rating??0; $i++)
                                        <li><i class="fa-regular fa-star"></i></li>
                                    @endfor
                                </ul>
                            </div>
                            <div class="testimonial-content">
                                <h6>{!! $item['heading'] !!}</h6>
                                <p>{!! $item['short_text'] !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- testimonial -->
