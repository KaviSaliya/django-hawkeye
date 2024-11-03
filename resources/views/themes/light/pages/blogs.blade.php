@extends($theme.'layouts.app')
@section('title',trans('Project Details'))
@section('content')

    <!-- blog -->
    <section class="blog blog-page">
        <div class="container">
            <div class="common-title-container">
                <div class="common-title">
                    <h3>{!! styleSentence2($blog_section['single']['heading']??'',2) !!}</h3>
                    <p>{!! $blog_section['single']['short_description']??'' !!} </p>
                </div>
            </div>

            <div class="row d-flex justify-content-center">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="blog-single wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="blog-single-image">
                                <img src="{{$blog->blogImage()}}" alt="image">
                            </div>
                            <div class="blog-single-content">
                                <a href="{{route('blog.details',[slug(optional($blog->details)->slug),$blog->id])}}">{{optional($blog->details)->title}}</a>
                                <p>{{ Str::limit(strip_tags(optional($blog->details)->description), 100, '...') }}</p>

                                <div class="blog-single-bottom">
                                    <div class="blog-single-author">
                                        <div class="author-image">
                                            <img src="{{$blog->createdByInfo('image')}}" alt="author">
                                        </div>
                                        <h6>{{$blog->createdByInfo('name')}}</h6>
                                    </div>
                                    <a href="{{route('blog.details',slug(optional($blog->details)->slug)??'blog_details')}}" class="btn-2">@lang('Details') <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="no-data-found-image">
                            <img src="{{asset($themeTrue.'images/no_data.jpg')}}" alt="image">
                        </div>
                    </div>
                @endforelse
                <div class="paigination">
                    {{ $blogs->appends($_GET)->links($theme.'partials.pagination') }}
                </div>
            </div>
        </div>
    </section>
    <!-- blog -->


    <!-- subscribe -->
    <section class="subscribe">
        <div class="container">
            <div class="subscribe-container" style=" background: url({{isset($subscribe_section['single']['media']->image)?getFile($subscribe_section['single']['media']->image->driver,$subscribe_section['single']['media']->image->path):getFile('local','image')}}) no-repeat;">
                <div class="subscribe-content">
                    <h3>{!! styleSentence($subscribe_section['single']['heading']??'',5) !!}</h3>
                    <div class="subscribe-btn">
                        <a href="{{$subscribe_section['single']['media']->button_link??'#'}}" class="btn-1">{!! $subscribe_section['single']['button_name']??'Create An Account' !!} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- subscribe -->
@endsection
