

<!-- footer -->
<footer class="main-footer footer-style-1 main-footer-padding">
    <div class="bg-layer" style="background: url('{{asset($themeTrue.'images/background/footer-bg-shape.png')}}');"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-4">
                <div class="link-widget-1 logo-widget pr_20 wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
                    <div class="logo-widget-inner">
                        <div class="footer-logo">
                            <a href="{{route('page')}}"><img src="{{getFile(basicControl()->logo_driver,basicControl()->logo)}}" alt="logo"></a>
                        </div>
                        <div class="footer-location">
                            <div class="location">
                                <div class="icon"><i class="fa-light fa-location-dot"></i></div>
                                <p>{!! $footer_section['single']['address']??'' !!}</p>
                            </div>
                            <div class="hot-line">
                                <div class="icon"><i class="fa-light fa-phone"></i></div>
                                <p>@lang('Call Us'): <a href="tel:{{$footer_section['single']['media']->phone_number??''}}">{{$footer_section['single']['media']->phone_number??''}}</a></p>
                            </div>
                        </div>
                        <div class="footer-media">
                            <ul>
                                @foreach(collect($footer_section['multiple'])->toArray() as $item)
                                    <li><a href="{{$item['media']->link}}"><i class="{{$item['media']->icon}}"></i></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3  col-lg-2 col-md-3">
                <div class="link-widget-1 useful-widget wow fadeInUp" data-wow-delay="500ms" data-wow-duration="1500ms">
                    <h6>@lang('Useful Link')</h6>
                    <ul class="link-widget-1-list">
                        @if(getFooterMenuData('useful_link') != null)

                            @foreach(getFooterMenuData('useful_link') as $list)
                                {!! $list !!}
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-5">
                <div class="link-widget-1 help-widget wow fadeInUp" data-wow-delay="600ms" data-wow-duration="1500ms">
                    <h6>@lang('Resources')</h6>
                    <ul class="link-widget-1-list">
                        @if(getFooterMenuData('support_link') != null)

                            @foreach(getFooterMenuData('support_link') as $list)
                                {!! $list !!}
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="link-widget-1 form-widget wow fadeInUp" data-wow-delay="700ms" data-wow-duration="1500ms">
                    <h6>@lang($footer_section['single']['subscribe_heading']??'')</h6>
                    <div class="footer-form">
                        <p>@lang($footer_section['single']['subscribe_text']??'')</p>
                        <div class="footer-newsletter-form">
                            <form action="{{route('subscribe')}}" method="post">
                                @csrf
                                <input type="email" name="email" placeholder="@lang('Email')" required>
                                <button type="submit">@lang('Subscribe')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer-copyright">
            <p class="copyright-text d-block m-auto">@lang('All right reserved') @lang(basicControl()->site_title) &copy; @lang(date("Y"))</p>
        </div>

    </div>
</footer>
<!-- footer -->
