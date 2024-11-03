<!-- faq -->
<section class="faq">
    <div class="line-left">
        <img src="{{asset($themeTrue.'images/shape/line-left-02.png')}}" alt="shape">
    </div>
    <div class="earth-right">
        <img src="{{asset($themeTrue.'images/shape/earth-round.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="faq-left-container">
                    <!--Accordian Box-->
                    <ul class="accordion-box acc_style_h4">
                        @foreach(collect($faq_section['multiple'])->toArray() as $key  =>  $item)
                            <li class="accordion block  {{$key == 1?'active-block':''}}">
                                <div class="acc-btn {{$key == 1?'active':''}}">
                                    <div class="icon-box">
                                        <div class="icon icon_1">
                                            <i class="fa-light fa-angle-down"></i>
                                        </div>
                                        <div class="icon icon_2">
                                            <i class="fa-sharp fa-light fa-angle-up"></i>
                                        </div>
                                    </div>
                                    <h4>{!! $item['question'] !!} </h4>
                                </div>
                                <div class="acc-content {{$key == 1?'current':''}}">
                                    <p class="text">
                                        {{strip_tags($item['answer'])}}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="faq-right-container">
                    <div class="faq-right-image">
                        <img src="{{isset($faq_section['single']['media']->faq_image)?getFile($faq_section['single']['media']->faq_image->driver,$faq_section['single']['media']->faq_image->path):getFile('local','image')}}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- faq -->
