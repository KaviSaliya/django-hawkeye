


<!-- investment way -->
<section class="investment-way">
    <div class="line">
        <img src="{{asset($themeTrue.'images/shape/line-left-04.png')}}" alt="shape">
    </div>
    <div class="earth-round">
        <img src="{{asset($themeTrue.'images/shape/earth-round.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="investment-way-container">
            <div class="common-title-container">
                <div class="common-title">
                    <h3>{!! styleSentence2($investment_way_section['single']['heading']??null,3) !!}</h3>
                    <p>{{$investment_way_section['single']['short_text']}}  </p>
                </div>
            </div>
            <div class="row">
                @foreach(collect($investment_way_section['multiple'])->toArray() as $item)
                    <div class="col-lg-3 col-md-6">
                        <div class="investment-way-single">
                            <div class="icon">
                                <img src="{{isset($item['media']->icon_image)?getFile($item['media']->icon_image->driver,$item['media']->icon_image->path):getFile('local','image')}}" alt="icon">
                            </div>
                            <div class="investment-way-single-content">
                                <a href="javascript:void(0)">{!! $item['heading'] !!}</a>
                                <p>{!! $item['short_text'] !!}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
<!-- investment way -->
