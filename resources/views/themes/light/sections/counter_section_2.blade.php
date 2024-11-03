

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
