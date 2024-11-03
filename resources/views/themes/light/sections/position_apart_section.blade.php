


<!-- Position Apart -->
<section class="position-apart" style="background: url('{{isset($position_apart_section['single']['media']->background_image)?getFile($position_apart_section['single']['media']->background_image->driver,$position_apart_section['single']['media']->background_image->path):getFile('local','image')}}') no-repeat">
    <div class="position-apart-image">
        <img src="{{isset($position_apart_section['single']['media']->image)?getFile($position_apart_section['single']['media']->image->driver,$position_apart_section['single']['media']->image->path):getFile('local','image')}}" alt="image">
    </div>
    <div class="position-dot-box">
        <img src="{{asset($themeTrue.'images/shape/dot-box-1.png')}}" alt="shape">
    </div>
    <div class="position-line-shape">
        <img src="{{asset($themeTrue.'images/shape/line-shape-2.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <h3>{!! styleSentence($position_apart_section['single']['heading']??null , 3) !!}</h3>
                <p>{!! $position_apart_section['single']['short_description'] !!}</p>
                <div class="common-title-top-left">
                    <img src="{{asset($themeTrue.'images/shape/title-top-left-2.png')}}" alt="shape">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <div class="position-apart-left-container">
                    @php
                        $totalItem = count(collect($position_apart_section['multiple'])->toArray());
                        $numberOfLoop = (int) $totalItem/2;
                    @endphp
                    @foreach(collect($position_apart_section['multiple'])->toArray() as $key => $item)
                        @break($key == $numberOfLoop)
                        <div class="position-apart-content">
                            <div class="icon">
                                <img src="{{isset($item['media']->icon_image)?getFile($item['media']->icon_image->driver,$item['media']->icon_image->path):''}}" alt="icon">
                            </div>
                            <div class="content">
                                <a href="#0">{!! $item['heading'] !!}</a>
                                <p>{!! $item['short_description'] !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-5 offset-lg-2">
                <div class="position-apart-right-container">
                    @foreach(collect($position_apart_section['multiple'])->toArray() as $key => $item)
                        @continue($key < $numberOfLoop )

                        <div class="position-apart-content">
                            <div class="icon">
                                <img src="{{isset($item['media']->icon_image)?getFile($item['media']->icon_image->driver,$item['media']->icon_image->path):''}}" alt="icon">

                            </div>
                            <div class="content">
                                <a href="#0">{!! $item['heading'] !!}</a>
                                <p>{!! $item['short_description'] !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Position Apart -->
