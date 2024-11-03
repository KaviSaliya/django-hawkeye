<!-- header -->
<header class="main-header header-style-two">

    <!-- header top -->
    <div class="header-top">
        <div class="custom-container">
            <div class="header-top-container">
                <div class="header-top-info">
                    <i class="fa-regular fa-location-dot"></i>
                    <p>{!! $top_section['single']['address'] !!}</p>
                </div>
                <div class="header-top-social-container">
                    <div class="header-top-info">
                        <i class="fa-light fa-envelope"></i>
                        <a href="mailto:{{$top_section['single']['email']}}">{{$top_section['single']['email']}}</a>
                    </div>
                    <div class="header-top-info">
                        <div class="language-box">
                            <div class="dropdown">
                                <button class="dropdown-toggle " type="button" data-bs-toggle="dropdown" aria-expanded="true">
                                    <div class="d-flex align-items-center gap-1">
                                        @if(session('language'))
                                            <img src="{{getFile(session('language')->flag_driver,session('language')->flag)}}" alt="">
                                            {{session('language')->name}}
                                        @else
                                            <img src="{{getFile($languages->first()->flag_driver,$languages->first()->flag)}}" alt="">
                                            {{$languages->first()->name}}
                                        @endif

                                    </div>
                                </button>
                                <ul class="dropdown-menu" data-bs-popper="static">
                                    @foreach($languages as $language)
                                        <li>
                                            <a href="{{route('language',$language->short_name)}}">
                                                <button class="dropdown-item" type="button"><img src="{{getFile($language->flag_driver,$language->flag)}}" alt="">
                                                    {{$language->name}}
                                                </button>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- header top -->

    <!-- Header Lower -->
    <div class="header-lower">
        <div class="custom-container">
            <div class="inner-container d-flex align-items-center justify-content-between">

                <div class="left-column">
                    <div class="logo-box">
                        <div class="logo"><a href="{{route('page')}}"><img src="{{getFile(basicControl()->logo_driver,basicControl()->logo)}}" alt="logo"></a></div>
                    </div>
                </div>

                <div class="right-column d-flex align-items-center">
                    <div class="nav-outer">
                        <div class="mobile-nav-toggler"><i class="fa-regular fa-bars-staggered"></i></div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                <ul class="navigation">
                                    {!! renderHeaderMenu(getHeaderMenuData()) !!}
                                </ul>
                            </div>
                        </nav>
                    </div>

                    <div class="header-right-btn">
                        <div class="sign-up">
                            <div class="button-1">
                                @if(auth()->guard('web')->user())
                                    <a href="{{route('user.dashboard')}}" class="btn-1">@lang('Dashboard') <span></span></a>
                                @else
                                    <a href="{{route('login')}}" class="btn-1">@lang('Log In') <span></span></a>
                                @endif
                            </div>
                            @if(!auth()->guard('web')->user())
                                <div class="button-2">
                                    <a href="{{route('register')}}" class="btn-1">@lang('Sign Up') <span></span></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Lower -->


    <!-- sticky header -->
    <div class="sticky-header">
        <div class="header-upper">
            <div class="container">
                <div class="inner-container d-flex align-items-center justify-content-between">
                    <div class="left-column d-flex align-items-center">
                        <div class="logo-box">
                            <div class="logo"><a href="{{route('page')}}"><img src="{{getFile(basicControl()->logo_driver,basicControl()->logo)}}" alt="logo"></a></div>
                        </div>
                    </div>
                    <div class="nav-outer">
                        <div class="mobile-nav-toggler"><img src="{{asset($themeTrue.'images/icons/icon-bar-two.png')}}" alt="icon"></div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sticky header -->


    <!-- mobile menu -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="fal fa-times"></span></div>

        <nav class="menu-box">
            <div class="nav-logo"><a href="{{route('page')}}"><img src="{{getFile(basicControl()->logo_driver,basicControl()->logo)}}" alt="logo"></a></div>
            <div class="menu-outer"></div>
            <div class="d-flex justify-content-between m-3">
                <div class="button-1">
                    @if(auth()->guard('web')->user())
                        <a href="{{route('user.dashboard')}}" class="btn-1">@lang('Dashboard') <span></span></a>
                    @else
                        <a href="{{route('login')}}" class="btn-1 me-2">@lang('Log In') <span></span></a>
                    @endif
                </div>
                @if(!auth()->guard('web')->user())
                    <div class="button-2">
                        <a href="{{route('register')}}" class="btn-1">@lang('Sign Up') <span></span></a>
                    </div>
                @endif
            </div>
        </nav>
    </div>

    <div class="nav-overlay">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>
</header>
<!-- Header eand -->
