@extends($theme.'layouts.app')
@section('title',trans('Project Details'))
@section('content')
    <!-- projects Details -->
    <section class="project-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="project-details-left">
                        <div class="swiper projectSwiper2">
                            <div class="swiper-wrapper swiper_big_slider">
                                @foreach($project->getImages() as $image)
                                    <div class="swiper-slide">
                                        <img src="{{$image}}"/>
                                       {!! $project->projectStatus() !!}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper projectSwiper swiper_small_slider">
                            <div class="swiper-wrapper">
                                @foreach($project->getImages() as $image)
                                    <div class="swiper-slide">
                                        <img src="{{$image}}"/>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="project-invest">
                        <div class="project-invest-title">
                            <h3>{!! optional($project->details)->title !!} </h3>
                            <p><i class="fa-sharp fa-light fa-location-dot"></i> {{$project->location}}</p>
                        </div>
                        <div class="project-invest-body">
                            <div class="project-single-content-bottom">
                                <div class="project-single-content-wrapper">
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fal fa-hashtag"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Number of Return')</p>
                                            <span>{{$project->number_of_return? $project->number_of_return.' '.trans('Times') :trans('Lifetime Earning')}} </span>
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

                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fal fa-tag"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Total Unit')</p>
                                            <span>{{$project->total_units}}</span>
                                        </div>
                                    </div>
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fal fa-clock-rotate-left"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Maturity')</p>
                                            <span>{{$project->maturity.' '.trans('Days')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="project-single-content-wrapper">
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fa-sharp fa-light fa-tags"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Unite Price')</p>
                                            <span>{{$project->investAmount()}}</span>
                                        </div>
                                    </div>
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fal fa-calendar-days"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Return Period')</p>
                                            <span>{{$project->returnPeriod()}}</span>
                                        </div>
                                    </div>
                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fal fa-hand-holding-dollar"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Capital Back')</p>
                                            <span>{{$project->capital_back?'Yes':'No'}}</span>
                                        </div>
                                    </div>

                                    <div class="project-single-content-inner">
                                        <div class="icon">
                                            <i class="fal fa-clock"></i>
                                        </div>
                                        <div class="content">
                                            <p>@lang('Investment Last Date')</p>
                                            <span>{{dateTime($project->invest_last_date)}}</span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="project-invest-count">
                                <div class="count-container">
                                    <p>@lang('You are Sponsoring:')</p>
                                    <div class="count-single">
                                        <button type="button"  class="decrement">-</button>
                                        <span class="number unitOfProjects">1</span>
                                        <button type="button" class="increment">+</button>
                                    </div>
                                </div>
                                <div class="project-invest-value">
                                    <p>@lang('Available Unit')</p>
                                    <h4>{{$project->available_units}}</h4>
                                </div>
                            </div>
                            <div class="project-invest-button">
                                @if($project->checkInvestmentLastDate())
                                    <button type="button" class="btn-1 projectInvestButton" data-bs-toggle="modal" data-bs-target="#investModal">@lang('Invest Now') <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></button>
                                @else
                                    <button type="button" class="btn-1">@lang('Expired') <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about-farmproject">
                <div class="about-farmproject-title">
                    <h3>@lang('About This Farm Project')</h3>
                </div>
                <div class="about-farmproject-article">
                   {!! optional($project->details)->description !!}
                </div>
            </div>
        </div>
    </section>
    <!-- projects Details -->

    <!-- counter 2 -->
    <section class="counter-two">
        <div class="cloud-shape">
            <img src="{{asset($themeTrue.'images/shape/cloud.png')}}" alt="shape">
        </div>
        <div class="star-shape">
            <img src="{{asset($themeTrue.'images/shape/star.png')}}" alt="shape">
        </div>
        <div class="layer-bg" style="background: url({{isset($counter_section_2['single']['media']->background_image)?getFile($counter_section_2['single']['media']->background_image->driver,$counter_section_2['single']['media']->background_image->path):getFile('local','image')}});" data-jarallax data-speed="0.2" data-imgPosition="50% 0%"></div>
        <div class="container">
            <div class="common-title-container">
                <div class="common-title">
                    <h3>{!! styleSentence2($counter_section_2['single']['heading']??''??null,4) !!}</h3>
                    <p>{!! $counter_section_2['single']['short_text']??'' !!} </p>
                </div>
            </div>
            <div class="counter-two-container">
                <div class="row">
                    @foreach(collect($counter_section_2['multiple'])->toArray() as $item)
                        <div class="col-lg-3 col-md-6">
                            <div class="counter">
                                <div class="odometer-box">
                                    <h5 class="odometer" data-count="{{(int)$item['media']->count??0}}">00</h5>
                                    <div class="odometer-text">{{$item['prefix']??''}}</div>
                                </div>
                                <p>{{$item['countable_item_name']}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- counter 2 -->

    <!-- farming -->
    <section class="farming">
        <div class="container">
            <div class="common-title-container">
                <div class="common-title">
                    <h3>{!! styleSentence2($farming_section['single']['heading']??'',4) !!}</span></h3>
                    <p>{!! $farming_section['single']['short_description'] !!}</p>
                </div>
                <div class="common-title-btn">
                    <a href="{{$farming_section['single']['media']->button_link??'#'}}" class="btn-1">{{$farming_section['single']['button_name']??''}} <i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                </div>
            </div>
        </div>
        <div class="farming-container">
            <div class="farming-carousol">
                <div class="four-item-carousel swiper-container farming-carousol-container">
                    <div class="swiper-wrapper">

                        @foreach($projects as $item)
                            <div class="swiper-slide">
                                <div class="farming-single wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                                    <div class="farming-single-image">
                                        <img src="{{$item->getThumbnailImage()}}" alt="image">
                                    </div>
                                    <div class="farming-single-content">
                                        <a href="{{route('project.details',[slug(optional($item->details)->title??'project-title'),$item->id])}}">{{optional($item->details)->title}}</a>
                                        <p>{{strip_tags(optional($item->details)->short_description)}}</p>
                                    </div>
                                    <div class="farming-single-btn">
                                        <a href="{{route('project.details',[slug(optional($item->details)->title??'project-title'),$item->id])}}"><i class="fa-sharp fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- farming -->

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

    <div class="modal fade"  id="investModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Invest Now')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.projectInvest')}}" method="post">
                    @csrf
                <div class="modal-body project-modal">
                    <p class="text-center mt-2 mb-1"> @lang('Maturity') : {{$project->number_of_return?$project->number_of_return.' '.trans('Times'):'Lifetime Earning'}}</p>
                    <p class="text-center mb-1"> @lang('ROI :') {{$project->getReturn()}}</p>
                    <p class="text-center mb-2"> @lang('Return Period') : {{$project->returnPeriod()}}</p>
                    <p class="text-center mb-2">@lang('Capital Back') : {{$project->capital_back?'Yes':'No'}}</p>

                        <div class="row g-3">
                            <div class="col-12">
                                <select class="nice-select" aria-label="Default select example" name="balance_type" required>
                                    @auth
                                        <option
                                            value="balance">@lang('Wallet Balance -') {{currencyPosition(auth()->guard('web')->user()->balance)}}</option>
                                        <option
                                            value="profit">@lang('Profit Balance -') {{currencyPosition(auth()->guard('web')->user()->profit_balance)}}</option>
                                    @endauth
                                    <option value="checkout">@lang('Checkout')</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="input-group">
                                    <input type="text" class="form-control invest-amount" name="amount" id="amount"
                                           value="{{old('amount')}}"
                                           onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" autocomplete="off"
                                           placeholder="@lang('enter per unit price')" required/>
                                    <span class="input-group-text show-currency bg-white">{{basicControl()->currency_symbol}}</span>
                                </div>
                            </div>
                            <input type="hidden" name="project_id" value="{{$project->id}}">
                            <input type="hidden" name="unit" id="investUnit" value="1">
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn-1 d-flex justify-content-center align-items-center text-center">@lang('Make Payment') <span></span></button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('click','.projectInvestButton',function (){
            let unit = Number($('.unitOfProjects').text());
            $('#investUnit').val(unit);
        })
    </script>
@endpush
