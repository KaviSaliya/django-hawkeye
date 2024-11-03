
<!-- farming -->
<section class="farming">
    <div class="position-dot-box">
        <img src="{{asset($themeTrue.'images/shape/dot-box-2.png')}}" alt="shape">
    </div>
    <div class="farming-right-shape">
        <img src="{{asset($themeTrue.'images/shape/farming-right-shape.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <div class="common-title-top-right">
                    <img src="{{asset($themeTrue.'images/shape/title-top-shape.png')}}" alt="shape">
                </div>
                <h3> {!! styleSentence($farming_section['single']['heading']??null,1) !!}</h3>
                <p>{!! $farming_section['single']['short_description'] !!}</p>
            </div>
        </div>
    </div>
    <div class="farming-container">
        <div class="farming-carousol">
            <div class="four-item-carousel swiper-container farming-carousol-container">
                <div class="swiper-wrapper">

                    @foreach($projects as $project)
                        <div class="swiper-slide">
                            <div class="farming-single wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                                <div class="farming-single-image">
                                    <img src="{{$project->getThumbnailImage()}}" alt="image">
                                </div>
                                <div class="farming-single-content">
                                    <a href="{{route('project.details',[slug(optional($project->details)->title??'project-title'),$project->id])}}">{{optional($project->details)->title}}</a>
                                    <p>{{strip_tags(optional($project->details)->short_description)}}</p>
                                </div>
                                <div class="farming-single-btn">
                                    <a href="{{route('project.details',[slug(optional($project->details)->title??'project-title'),$project->id])}}"><i class="fa-sharp fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
            <div class="farming-navigation">
                <div class="swiper-button-next"><i class="fa-regular fa-angle-right"></i></div>
                <div class="swiper-button-prev"><i class="fa-regular fa-angle-left"></i></div>
            </div>
        </div>
    </div>
</section>
<!-- farming -->

