

<!-- Projects -->
<section class="project">
    <div class="project-shape-1">
        <img src="{{asset($themeTrue.'images/shape/light-left.png')}}" alt="shape">
    </div>
    <div class="project-shape-2 doller-anim">
        <img src="{{asset($themeTrue.'images/shape/dollor.png')}}" alt="shape">
    </div>
    <div class="project-light-shape">
        <img src="{{asset($themeTrue.'images/shape/light-1.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <div class="common-title-top-left">
                    <img src="{{asset($themeTrue.'images/shape/title-top-left-3.png')}}" alt="shape">
                </div>
                <div class="common-title-top-right">
                    <img src="{{asset($themeTrue.'images/shape/title-top-shape.png')}}" alt="shape">
                </div>
                <h3> {!! styleSentence($project_section['single']['heading'],4) !!} </h3>
                <p>{!! $project_section['single']['short_description']??'' !!}</p>
            </div>
        </div>
        <div class="row">
            @foreach($projects as $project)
                <div class="col-lg-6 projectSingle">
                    <div class="project-single">
                        {!! $project->projectStatus() !!}
                        <div class="project-single-image">
                            <img src="{{$project->getThumbnailImage()}}" alt="image">
                        </div>
                        <div class="project-single-content">
                            <div class="project-single-content-top">
                                <a href="{{route('project.details',optional($project->details)->slug??'project-title')}}">{!! optional($project->details)->title !!}</a>
                                <p><i class="fa-sharp fa-light fa-location-dot"></i> {{$project->location}}</p>
                            </div>
                            <div class="project-single-content-bottom">
                                <div class="project-single-content-wrapper">
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fa-regular fa-calendar-clock"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Project duration')</p>
                                            <span>{{$project->getProjectDuration()}}</span>
                                        </div>
                                    </div>
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fa-solid fa-chart-mixed"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('ROI')</p>
                                            <span>{{$project->getReturn()}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-single-content-wrapper">
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fa-sharp fa-light fa-tags"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Unit Price')</p>
                                            <span>{{$project->investAmount()}}</span>
                                        </div>
                                    </div>
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fal fa-hashtag"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Number of Return')</p>
                                            <span>{{$project->number_of_return?$project->number_of_return.' '.trans('Times'):trans('Lifetime Earning')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="project-button">
            <a href="{{$project_section['single']['media']->button_link??'#'}}" class="btn-1">{{$project_section['single']['button_name']??''}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
        </div>
    </div>
</section>
<!-- Projects -->
