<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" type="image/x-icon">
    <title>@yield('title') | @lang(basicControl()->site_title) </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- bootstrap 5 -->

    <!-- font awesome 6 -->
    <link rel="stylesheet" href="{{ asset($themeTrue . 'css/all.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset($themeTrue . 'css/flatpickr-min.css') }}"/>
    <link rel="stylesheet" href="{{ asset($themeTrue . 'css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($themeTrue . 'css/select2.min.css') }}"/>
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset($themeTrue . 'css/user-style.css') }}"/>
    <!-- owl carousel -->
    <link rel="stylesheet" href="{{asset($themeTrue.'css/owl.carousel.min.css')}}"/>
    <link rel="stylesheet" href="{{asset($themeTrue.'css/owl.theme.default.min.css')}}"/>
    <link rel="stylesheet" href="{{asset($themeTrue.'css/apexcharts.css')}}"/>
    <link href="{{asset($themeTrue.'css/fancy-box-carusol.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.7.3/build/css/intlTelInput.css">

    @stack('css-lib')
    @stack('style')
</head>
<body onload="preloaderFunction()" >
<div id="preloader">
    <div class="load">
        <hr/>
        <hr/>
        <hr/>
        <hr/>
    </div>
</div>

<!-- Header section start -->
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <div class="logo-container">
            <a href="{{route('page')}}" class="logo d-flex align-items-center">
                <img src="{{getFile(basicControl()->admin_logo_driver,basicControl()->admin_logo)}}" alt="...">
            </a>
        </div>
        <button onclick="toggleSideMenu()" class="toggle-sidebar toggle-sidebar-btn d-none d-lg-block"><i
                class="fa-light fa-list"></i></button>
    </div>
    <!-- End Logo -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center">
            <input type="search" class="form-control global-search" name="query" placeholder="@lang('Search')"
                   title="Enter search keyword">
            <span class="search-icon" title="Search"><i class="fa-regular fa-magnifying-glass"></i></span>
            <div class="search-result d-none">
                <div class="search-header">
                    @lang('Result')                </div>
                <div class="content"></div>
            </div>
        </form>
    </div>

    <!-- Start Icons Navigation -->

    <nav class="header-nav ms-auto">
        <ul class="nav-icons">
            <li class="nav-item dropdown" id="pushNotificationArea" v-cloak>
                @if(basicControl()->in_app_notification)
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="fa-light fa-bell"></i>
                    <span v-if="items.length > 0" class="badge badge-number" v-cloak>@{{items.length}}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <div class="notifications-items">
                        <li class="notification-item" v-for="(item, index) in items"
                            @click.prevent="readAt(item.id, item.description.link)">
                            <a href="javascript:void(0)">
                                <i class="fa-regular fa-circle-check text-success"></i>
                                <div>
                                    <p>@{{item.description.text}}</p>
                                    <p>@{{ item.formatted_date }}</p>
                                </div>
                            </a>
                        </li>
                        <li class=" d-flex align-items-center justify-content-center h-100" v-if="items.length == 0">
                            <a href="javascript:void(0)"
                               @click.prevent="readAll">@lang('You have no notifications')</a>
                        </li>
                    </div>

                    <div class="dropdown-footer">
                        <li class="">
                            <a href="javascript:void(0)" v-if="items.length > 0" @click.prevent="readAll">@lang('Clear all')</a>
                        </li>
                    </div>
                </ul>
                @endif
            </li>



            <li class="nav-item dropdown">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{getFile(auth()->user()->image_driver,auth()->user()->image)}}"
                         alt="{{auth()->user()->username}}" class="rounded-circle max-w-80">
                    <span class="d-none d-lg-block dropdown-toggle ps-2">{{auth()->user()->firstname.' '.auth()->user()->lastname}} </span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header d-flex justify-content-center text-start">
                        <div class="profile-thum">
                            <img src="{{getFile(auth()->user()->image_driver,auth()->user()->image)}}"
                                 alt="Demo User" class="rounded-circle">
                        </div>
                        <div class="profile-content">
                            <h6>{{auth()->user()->firstname.' '.auth()->user()->lastname}}</h6>
                            <span> {{'@'.auth()->user()->username}}</span>
                        </div>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}">
                            <i class="fa-light fa-user"></i>
                            <span>@lang('Account Settings')</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{route('user.twostep.security')}}">
                            <i class="fal fa-key"></i>
                            <span>@lang('2 FA Security')</span>
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('user.show.user.kyc') }}">
                            <i class="fa-light fa-id-card"></i>
                            <span>@lang('Verification Center')</span>
                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa-regular fa-right-from-bracket"></i>
                            <span>@lang('Sign Out')</span>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav>

    <!-- End Icons Navigation -->

</header>

<!-- Bottom Mobile Tab nav section start -->
<ul class="nav bottom-nav fixed-bottom d-lg-none">
    <li class="nav-item">
        <a onclick="toggleSideMenu()" class="nav-link toggle-sidebar" aria-current="page">
            <i class="fa-light fa-list"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{menuActive(['user.plans'])}} " href="{{route('user.plans')}}">
            <i class="fa-brands fa-codepen"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{menuActive(['user.dashboard'])}}" href="{{route('user.dashboard')}}"><i
                class="fa-regular fa-house"></i></a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{menuActive(['user.projects'])}}" href="{{route('user.projects')}}">
            <i class="fa-brands fa-slack"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{menuActive(['user.profile'])}}" href="{{route('user.profile')}}"><i
                class="fa-light fa-user"></i></a>
    </li>
</ul>
<!-- Bottom Mobile Tab nav section end -->

@include($theme.'partials/sidebar')

<main id="main" class="main">
    @yield('content')
</main>


<!-- Footer section start -->
<footer id="footer" class="footer">
    <div class="copyright">
        &copy; @lang('Copyright') <strong><span>{{basicControl()->site_title}}</span></strong>. @lang('All Rights Reserved')    </div>
</footer>
<!-- Footer section end -->

<!-- bootstrap -->
<script src="{{asset($themeTrue.'js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/flatpickr-min.js')}}"></script>
<!-- jquery cdn -->
<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{asset($themeTrue.'js/apexcharts.min.js')}}"></script>
<script src="{{ asset('assets/global/js/Chart.min.js') }}"></script>
<script src="{{asset($themeTrue.'js/user-script.js')}}"></script>
<script src="{{ asset($themeTrue . 'js/select2.min.js') }}"></script>
<script src="{{asset($themeTrue.'js/owl.carousel.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/waypoints.min.js')}}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{asset($themeTrue.'js/custom_share.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.7.3/build/js/intlTelInput.min.js"></script>




@stack('js-lib')
@include('plugins')
@stack('script')
<script>
    function currencyPosition(amount) {
        var currencyPosition = @json(basicControl()->is_currency_position);
        var has_space_between_currency_and_amount = @json(basicControl()->has_space_between_currency_and_amount);
        var currency_symbol = @json(basicControl()->currency_symbol);
        var base_currency = @json(basicControl()->base_currency);
        amount = parseFloat(amount).toFixed(2);
        if (currencyPosition === 'left' && has_space_between_currency_and_amount) {
            return currency_symbol + '  ' + amount;
        } else if (currencyPosition === 'left' && !has_space_between_currency_and_amount) {
            return currency_symbol + ' ' + amount;
        } else if (currencyPosition === 'right' && has_space_between_currency_and_amount) {
            return amount + '  ' + base_currency;
        } else {
            return amount + '  ' + base_currency;
        }
    }
</script>







<script>
    'use strict';
    $(document).on('input', '.global-search', function() {
        var search = $(this).val().toLowerCase();

        if (search.length == 0) {
            $('.search-result').find('.content').html('');
            $(this).siblings('.search-backdrop').addClass('d-none');
            $(this).siblings('.search-result').addClass('d-none');
            return false;
        }

        $('.search-result').find('.content').html('');
        $(this).siblings('.search-backdrop').removeClass('d-none');
        $(this).siblings('.search-result').removeClass('d-none');

        var match = $('.sidebar-nav li').filter(function(idx, element) {
            if (!$(element).find('a').hasClass('has-dropdown') && !$(element).hasClass('menu-header'))
                return $(element).text().trim().toLowerCase().indexOf(search) >= 0 ? element : null;
        }).sort();

        if (match.length == 0) {
            $('.search-result').find('.content').append(
                `<div class="search-item"><a href="javascript:void(0)">No result found</a></div>`);
            return false;
        }

        match.each(function(index, element) {
            var item_text = $(element).text().replace(/(\d+)/g, '').trim();
            var item_url = $(element).find('a').attr('href');
            if (item_url != '#') {
                $('.search-result').find('.content').append(
                    `<div class="search-item"><a href="${item_url}">${item_text}</a></div>`);
            }
        });
    });
</script>
@if(basicControl()->in_app_notification)
    <script>
        'use strict';
        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: {
                items: [],
            },
            mounted() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('user.push.notification.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAt', 0) }}";
                    url = url.replace(/.$/, id);
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.getNotifications();
                                if (link !== '#') {
                                    window.location.href = link
                                }
                            }
                        })
                },
                readAll() {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAll') }}";
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.items = [];
                            }
                        })
                },
                pushNewItem() {
                    let app = this;
                    Pusher.logToConsole = false;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });
                    let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserNotification', function (data) {
                        app.items.unshift(data.message);
                    });
                    channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                        app.getNotifications();
                    });
                }
            }
        });
    </script>
@endif

@if (session()->has('success'))
    <script>
        Notiflix.Notify.success("@lang(session('success'))");
    </script>
@endif

@if (session()->has('error'))
    <script>
        Notiflix.Notify.failure("@lang(session('error'))");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        Notiflix.Notify.warning("@lang(session('warning'))");
    </script>
@endif



</body>
</html>

