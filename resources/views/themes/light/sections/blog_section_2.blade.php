

<!-- blog 2 -->
<section class="blog-two">
    <div class="project-shape-2">
        <img src="{{asset($themeTrue.'images/shape/dollor-2.png')}}" alt="shape">
    </div>
    <div class="blog-two-earth-round">
        <img src="{{asset($themeTrue.'images/shape/earth-round.png')}}" alt="shape">
    </div>
    <div class="blog-two-line-right">
        <img src="{{asset($themeTrue.'images/shape/line-right.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <h3>{!! styleSentence2($blog_section_2['single']['heading']??null,1) !!}</h3>
                <p>{!! $blog_section_2['single']['short_text']??'' !!} </p>
            </div>
        </div>
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-lg-6">
                    <div class="blog-two-single">
                        <div class="blog-two-single-image">
                            <a href="{{route('blog.details',[slug(optional($blog->details)->slug),$blog->id])}}"><img src="{{$blog->blogImage()}}" alt="image"></a>
                        </div>
                        <div class="blog-two-content">
                            <div class="blog-two-subtitle">
                                <h6>{{optional($blog->category)->name}}</h6>
                            </div>
                            <div class="blog-two-content-title">
                                <a href="{{route('blog.details',[slug(optional($blog->details)->slug),$blog->id])}}">{{optional($blog->details)->title}}</a>
                                <p>{{ Str::limit(strip_tags(optional($blog->details)->description), 140, '...') }}</p>
                            </div>
                            <div class="blog-two-content-button">
                                <a href="{{route('blog.details',[slug(optional($blog->details)->slug),$blog->id])}}" class="btn-1">{{trans('Details')}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="blog-two-btn">
            <a href="{{$blog_section_2['single']['media']->button_link}}" class="btn-1">{{$blog_section_2['single']['button_name']}}<i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
        </div>
    </div>
</section>
<!-- blog 2 -->
