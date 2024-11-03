
<!-- about-->
<section class="about">
    <div class="angle-shape">
        <img src="{{asset($themeTrue.'images/shape/tree.png')}}" alt="shape">
    </div>
    <div class="line-shape">
        <img src="{{asset($themeTrue.'images/shape/line-shape.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-left-container">
                    <div class="about-left-image">
                        <img src="{{isset($about_section_2['single']['media']->banner_image)?getFile($about_section_2['single']['media']->banner_image->driver,$about_section_2['single']['media']->banner_image->path):getFile('local','image')}}" alt="image">

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-right-container">
                    <div class="about-2-right-content">
                        <h1>{!! styleSentence2($about_section_2['single']['heading']??null,2) !!}</h1>
                        <p>{{$about_section_2['single']['short_description']}}</p>
                        <a href="{{$about_section_2['single']['media']->button_link}}" class="btn-1">{{$about_section_2['single']['button_name']}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 offset-lg-4">
                <div class="about-carousol">
                    <div class="about-right-carousel swiper-container about-carousol-container">
                        <div class="swiper-wrapper">
                            @foreach(collect($about_section_2['multiple'])->toArray() as $item)
                                <div class="swiper-slide">
                                    <div class="about-single wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                                        <div class="about-single-image">
                                            <img src="{{getFile($item['media']->image->driver,$item['media']->image->path)}}" alt="image">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="slider__pagination3"></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- about -->
