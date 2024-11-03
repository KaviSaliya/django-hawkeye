


<!-- Agriculture -->
<section class="agriculture">
    <div class="agriculture-shape">
        <img src="{{asset($themeTrue.'images/shape/shape-03.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="agriculture-left-container">
                    <div class="common-title-container">
                        <div class="common-title">
                            <div class="common-title-top-right">
                                <img src="{{asset($themeTrue.'images/shape/title-top-shape.png')}}" alt="shape">
                            </div>
                            <h3>{!! styleSentence($agriculture_section['single']['heading']??null,1) !!}</h3>
                            <p>{!! $agriculture_section['single']['short_description'] !!}</p>
                        </div>
                    </div>
                    @foreach(collect($agriculture_section['multiple'])->toArray() as $key => $item)
                        <div class="agriculture-left-content {{$key == 0?'agriculture-left-content-active':''}}">
                            <h5>{!! $item['heading'] !!}</h5>
                            <p>{!! $item['short_description'] !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="agriculture-right-container">
                    <div class="agriculture-right-container-inner">
                        <div class="agriculture-expert">
                            <h4>{!! $agriculture_section['single']['heading_2']??'' !!}</h4>
                        </div>
                        <div class="agriculture-right-image">
                            <img src="{{isset($agriculture_section['single']['media']->image)?getFile($agriculture_section['single']['media']->image->driver,$agriculture_section['single']['media']->image->path):getFile('local','image')}}" alt="image">
                        </div>
                        <div class="agriculture-right-content">
                            <h6>{!! $agriculture_section['single']['sub_heading'] !!}</h6>
                            <p>{!! $agriculture_section['single']['short_text'] !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Agriculture -->
