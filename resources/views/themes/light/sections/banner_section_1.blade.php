<!-- banner-section -->
<section class="banner-section banner-section-one">
    <div class="bg-layer" style="background: url('{{asset($themeTrue.'images/banner/banner-bg-shape.png')}}');"></div>
    <div class="swiper-container banner-slider-1">
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="container">
{{--                    @dd()--}}
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="banner-left-content">
                                <div class="banner-left-content-inner">
                                    <div class="content-box">
                                        <h1>{{$banner_section_1['single']['heading_1']??''}}</h1>
                                        <p>{{$banner_section_1['single']['short_description']??''}}</p>
                                        <div class="link-box alt"><a href="{{$banner_section_1['single']['media']->button_link??'#'}}" class="btn-1">{{$banner_section_1['single']['button_name']??'Getting Started'}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6">
                            <div class="banner-right-container">
                                <div class="banner-right-container-inner">
                                    <div class="banner-right-image-1">
                                        <img src="{{$banner_section_1['single']['media']->banner_right_image_2?getFile($banner_section_1['single']['media']->banner_right_image_2->driver,$banner_section_1['single']['media']->banner_right_image_2->path):''}}" alt="image">
                                    </div>
                                    <div class="banner-right-image-content" style="background: url('{{asset($themeTrue.'images/banner/banner-bg-1.png')}}') no-repeat;">
                                        <div class="banner-right-image-content-inner">
                                            <div class="content">
                                                <h5>{{$banner_section_1['single']['heading_2']??''}}</h5>
                                                <p>{{$banner_section_1['single']['short_text']??''}} </p>
                                            </div>
                                            <div class="banner-right-image-2">
                                                <img src="{{$banner_section_1['single']['media']->banner_right_image_1?getFile($banner_section_1['single']['media']->banner_right_image_1->driver,$banner_section_1['single']['media']->banner_right_image_1->path):''}}" alt="image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="banner-stiky-post banner-stiky-post-1">
                                        <div class="icon">
                                            <i class="fa-light fa-circle-dollar"></i>
                                        </div>
                                        <div class="stiky-post-content">
                                            <h6>{{$banner_section_1['single']['heading_3']??''}}</h6>
                                            <p>{{$banner_section_1['single']['short_text_2']??''}} </p>
                                        </div>
                                    </div>
                                    <div class="banner-stiky-post banner-stiky-post-2">
                                        <div class="icon">
                                            <i class="fa-sharp fa-light fa-shield-check"></i>
                                        </div>
                                        <div class="stiky-post-content">
                                            <h6>{{$banner_section_1['single']['heading_4']??''}}</h6>
                                            <p>{{$banner_section_1['single']['short_text_3']??''}} </p>
                                        </div>
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
                        <div class="col-lg-6 col-md-6">
                            <div class="banner-left-content">
                                <div class="banner-left-content-inner">
                                    <div class="content-box">
                                        <h1>{{$banner_section_1['single']['heading_1']??''}}</h1>
                                        <p>{{$banner_section_1['single']['short_description']??''}}</p>
                                        <div class="link-box alt"><a href="{{$banner_section_1['single']['media']->button_link??'#'}}" class="btn-1">{{$banner_section_1['single']['button_name']??'Getting Started'}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="banner-right-container">
                                <div class="banner-right-container-inner">
                                    <div class="banner-right-image-1">
                                        <img src="{{$banner_section_1['single']['media']->banner_right_image_2?getFile($banner_section_1['single']['media']->banner_right_image_2->driver,$banner_section_1['single']['media']->banner_right_image_2->path):''}}" alt="image">
                                    </div>
                                    <div class="banner-right-image-content">
                                        <div class="banner-right-image-content-inner">
                                            <div class="content">
                                                <h5>{{$banner_section_1['single']['heading_2']??''}}</h5>
                                                <p>{{$banner_section_1['single']['short_text']??''}} </p>
                                            </div>
                                            <div class="banner-right-image-2">
                                                <img src="{{$banner_section_1['single']['media']->banner_right_image_1?getFile($banner_section_1['single']['media']->banner_right_image_1->driver,$banner_section_1['single']['media']->banner_right_image_1->path):''}}" alt="image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="banner-stiky-post banner-stiky-post-1">
                                        <div class="icon">
                                            <i class="fa-light fa-circle-dollar"></i>
                                        </div>
                                        <div class="stiky-post-content">
                                            <h6>{{$banner_section_1['single']['heading_3']??''}}</h6>
                                            <p>{{$banner_section_1['single']['short_text_2']??''}} </p>
                                        </div>
                                    </div>
                                    <div class="banner-stiky-post banner-stiky-post-2">
                                        <div class="icon">
                                            <i class="fa-sharp fa-light fa-shield-check"></i>
                                        </div>
                                        <div class="stiky-post-content">
                                            <h6>{{$banner_section_1['single']['heading_4']??''}}</h6>
                                            <p>{{$banner_section_1['single']['short_text_3']??''}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="banner-bottom-area">
                    <div class="banner-avater">
                        <div class="avater-image">
                            <ul>
                                <li><a href="#"><img src="{{$banner_section_1['single']['media']->client_image_1?getFile($banner_section_1['single']['media']->client_image_1->driver,$banner_section_1['single']['media']->client_image_1->path):''}}" alt="client"></a></li>
                                <li><a href="#"><img src="{{$banner_section_1['single']['media']->client_image_2?getFile($banner_section_1['single']['media']->client_image_2->driver,$banner_section_1['single']['media']->client_image_2->path):''}}" alt="client"></a></li>
                                <li><a href="#"><img src="{{$banner_section_1['single']['media']->client_image_3?getFile($banner_section_1['single']['media']->client_image_3->driver,$banner_section_1['single']['media']->client_image_3->path):''}}" alt="client"></a></li>
                                <li><a href="#"><img src="{{$banner_section_1['single']['media']->client_image_4?getFile($banner_section_1['single']['media']->client_image_4->driver,$banner_section_1['single']['media']->client_image_4->path):''}}" alt="client"></a></li>
                            </ul>
                        </div>
                        <div class="avater-text">
                            <h6>{{$banner_section_1['single']['heading_5']??''}}</h6>
                            <p><i class="fa-sharp fa-solid fa-star"></i> <strong>{{$banner_section_1['single']['media']->number_of_ratings}}</strong> {{$banner_section_1['single']['short_text_4']??''}}</p>
                        </div>
                    </div>

                    <div class="partner-carousol">
                        <div class="five-item-carousel swiper-container">
                            <div class="swiper-wrapper">

                                @foreach(collect($banner_section_1['multiple'])->toArray() as $item)
                                    <div class="swiper-slide">
                                        <div class="partner-item  wow fadeInUp" data-wow-delay="100ms" data-wow-duration="1500ms">
                                            <div class="partner-logo">
                                                <img src="{{getFile($item['media']->partner_logo->driver,$item['media']->partner_logo->path)}}" alt="logo">
                                                <img src="{{getFile($item['media']->partner_logo->driver,$item['media']->partner_logo->path)}}" class="hide-client" alt="logo">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- banner-section -->
