<!-- products -->
<section class="products">
    <div class="shape-01">
        <img src="{{asset($themeTrue.'images/shape/line-left-02.png')}}" alt="shape">
    </div>
    <div class="earth-round">
        <img src="{{asset($themeTrue.'images/shape/earth-round.png')}}" alt="shape">
    </div>
    <div class="product-doller-shape doller-anim">
        <img src="{{asset($themeTrue.'images/shape/dollor.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <h3>{!! styleSentence2($product_section['single']['heading']??null,0) !!}</span></h3>
                <p>{!! $product_section['single']['short_text']??'' !!}</p>
            </div>
        </div>
        <div class="row">
            @foreach($projects as $project)
                <div class="col-lg-4 col-md-6">
                    <div class="products-single">
                        {!! $project->projectStatus() !!}
                        <div class="products-single-image">
                            <a href="{{route('project.details',optional($project->details)->slug??'project-title')}}"><img src="{{$project->getThumbnailImage()}}" alt="image"></a>
                        </div>
                        <div class="products-single-content">
                            <a href="{{route('project.details',optional($project->details)->slug??'project-title')}}">{!! optional($project->details)->title !!}</a>
                            <p>{{ Str::limit(strip_tags(optional($project->details)->short_description), 150, '...') }} </p>
                            <h6>@lang('Unit Price'): {{$project->investAmount()}} </h6>
                        </div>
                         <div class="products-single-bottom">
                            <div class="products-single-bottom-content">
                                <h6>{{$project->getProjectDuration()}}</h6>
                                <p>@lang('Project Duration')</p>
                            </div>
                            <div class="products-single-bottom-content">
                                <h6>{{$project->getReturn()}}</h6>
                                <p>@lang('ROI')</p>
                            </div>
                            <div class="products-single-bottom-content">
                                <h6>{{$project->number_of_return?$project->number_of_return.' '.trans('Times'):trans('Lifetime Earning')}}</h6>
                                <p>@lang('Number of Return')</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="products-btn">
            <a href="{{$product_section['single']['media']->button_link}}" class="btn-1"> @lang($product_section['single']['button_name']) <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
        </div>
    </div>
</section>
<!-- products -->
