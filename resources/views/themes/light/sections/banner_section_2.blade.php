<!-- banner-section -->
<section class="banner-section banner-section-two">
    <div class="bg-layer" style="background: url('{{$banner_section_2['single']['media']->background_image->driver?getFile($banner_section_2['single']['media']->background_image->driver,$banner_section_2['single']['media']->background_image->path):''}}');"></div>
    <div class="swiper-container banner-slider-1">
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <div class="banner-left-content">
                                <div class="banner-2-shape-arrow">
                                    <img src="{{asset($themeTrue.'images/shape/banner-shape-1.png')}}" alt="shape">
                                </div>
                                <div class="banner-left-content-inner">
                                    <div class="content-box">
                                        <h1>{!! styleSentence2($banner_section_2['single']['heading']??null,3) !!} </h1>
                                        <p>{!! $banner_section_2['single']['short_description'] !!}</p>
                                        <div class="link-box alt">
                                            <a href="{{$banner_section_2['single']['media']->button_link}}" class="btn-1">{{$banner_section_2['single']['button_name']}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                                            <a class="play_btn hv-popup-link" href="{{$banner_section_2['single']['media']->video_link}}">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <div class="banner-right-container">
                                <div class="banner-2-shape-spring">
                                    <img src="{{asset($themeTrue.'images/shape/banner-shape-2.png')}}" alt="shape">
                                </div>
                                <div class="banner-right-container-inner">
                                    <div class="banner-right-image-1">
                                        <img src="{{$banner_section_2['single']['media']->banner_right_image->driver?getFile($banner_section_2['single']['media']->banner_right_image->driver,$banner_section_2['single']['media']->banner_right_image->path):''}}" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-7">
                            <div class="banner-left-content">
                                <div class="banner-2-shape-arrow">
                                    <img src="{{asset($themeTrue.'images/shape/banner-shape-1.png')}}" alt="shape">
                                </div>
                                <div class="banner-left-content-inner">
                                    <div class="content-box">
                                        <h1>{!! styleSentence2($banner_section_2['single']['heading']??null,3) !!} </h1>
                                        <p>{!! $banner_section_2['single']['short_description'] !!}</p>
                                        <div class="link-box alt">
                                            <a href="{{$banner_section_2['single']['media']->button_link}}" class="btn-1">{{$banner_section_2['single']['button_name']}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                                            <a class="play_btn hv-popup-link" href="{{$banner_section_2['single']['media']->video_link}}">
                                                <i class="fas fa-play"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-5">
                            <div class="banner-right-container">
                                <div class="banner-2-shape-spring">
                                    <img src="{{asset($themeTrue.'images/shape/banner-shape-2.png')}}" alt="shape">
                                </div>
                                <div class="banner-right-container-inner">
                                    <div class="banner-right-image-1">
                                        <img src="{{$banner_section_2['single']['media']->banner_right_image->driver?getFile($banner_section_2['single']['media']->banner_right_image->driver,$banner_section_2['single']['media']->banner_right_image->path):''}}" alt="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- banner-section -->
