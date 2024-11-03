@extends($theme.'layouts.app')
@section('title',trans('Blog Details'))
@section('content')

<!-- blog details -->
<section class="blog-details">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="blog-details-left-container">

                    <div class="blog-details-top-image">
                        <img src="{{$blog->blogImage()}}" alt="image">
                    </div>

                    <div class="blog-details-content">
                        <h2>{{optional($blog->details)->title}} </h2>
                        <p class="blog-created-at"><i class="fa-light fa-calendar-days me-2"></i> {{dateTime($blog->created_at)}}</p>
                        <div class="blog-details-content-text">
                           {!! optional($blog->details)->description !!}
                        </div>
                    </div>
                    <div class="blog-details-image-caption">
                        <div class="footer-media">
                            <p class="blog-shared-social"><i class="fa-light fa-share ms-2"></i> @lang('Share This Articles')</p>

                            <ul id="socialShare">

                            </ul>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-lg-4">
                <div class="blog-details-sidebar">

                    <div class="blog-details-sidebar-content">
                        <div class="sidebar-search">
                            <form action="{{route('search')}}" method="get">
                                <input type="search" name="title" placeholder="Search">
                                <button type="submit"><i class="fa-regular fa-magnifying-glass"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="blog-details-sidebar-content">
                        <h4>@lang('Recent post')</h4>
                        @foreach($blogs as $item)
                            <div class="resent-post-container">
                                <div class="resent-post-image">
                                    <img src="{{$item->blogImage()}}" alt="image">
                                </div>
                                <div class="resent-post-content">
                                    <a href="{{route('blog.details',slug(optional($item->details)->slug)??'blog_details')}}">{{optional($item->details)->title}}</a>
                                    <div class="resent-post-content-info">
                                        <p><img src="{{$item->createdByInfo('image')}}" alt="image"> {{$item->createdByInfo('name')}}</p>
                                        <p><i class="fa-light fa-calendar-days"></i> {{dateTime($item->created_at)}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="blog-details-sidebar-content">
                        <h4>@lang('Categories')</h4>
                        <div class="sidebar-tags">
                            @foreach($categories as $category)
                                <a href="{{route('category.blogs',$category->id)}}">{{$category->name}}</a>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- blog details -->

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

@endsection


@push('script')
    <script>
        $('#socialShare').socialSharingPlugin({
            url: window.location.href,
            title: $('meta[property="og:title"]').attr('content'),
            description: $('meta[property="og:description"]').attr('content'),
            img: $('meta[property="og:image"]').attr('content'),
            enable: ['copy', 'facebook', 'twitter', 'pinterest', 'linkedin']
        });

    </script>
@endpush
