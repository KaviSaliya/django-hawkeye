<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@lang(basicControl()->site_title) | @if(isset($pageSeo['page_title']))@lang($pageSeo['page_title'])@else @yield('title')@endif</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="{{ $pageSeo['meta_keywords']??'' }}" name="keywords">
    <meta content="{{ $pageSeo['meta_description']??'' }}" name="description">
    <meta name="title" content="{{ $pageSeo['meta_title']??'' }}">
    <meta name="author" content="{{ basicControl()->site_title??'' }}">
    <meta property="og:image" content="{{ $pageSeo['image']??'' }}">

    <link rel="shortcut icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" type="image/x-icon">
    <link rel="icon" href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" type="image/x-icon">
    <link href="{{asset($themeTrue.'css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset($themeTrue.'css/style.css')}}" rel="stylesheet">
    
    @stack('style')
</head>
<body>

<div class="page-wrapper">
    <!-- preloader -->
        <div class="loader-wrap">
            <div class="preloader">
                <div class="preloader-close">x</div>
                <div id="handle-preloader" class="handle-preloader">
                    <div class="animation-preloader">
                        <div class="preloader-image">
                            <img src="{{asset($themeTrue.'images/preloader.gif')}}" alt="loader">
                        </div>
                        <div class="txt-loading">
                            @foreach(loader() as $charecter)
                                <span data-text-preloader="{{$charecter}}" class="letters-loading">
                               {{$charecter}}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- preloader end -->
    @include($theme.'partials.navbar')

    @if(!request()->is('/') && !request()->is('home-02'))
        <!-- banner section -->
        @if (isset($pageSeo) && $pageSeo->breadcrumb_status == 1)
            <!-- common banner -->
            <section class="common-banner">
                <div class="bg-layer" style="background: url({{$pageSeo->breadcrumb_image?getFile($pageSeo->breadcrumb_image_driver,$pageSeo->breadcrumb_image):''}});"></div>
                <div class="container">
                    <div class="common-banner-container">
                        <div class="common-banner-title">
                            <h1>{!! $pageSeo['page_title']??'' !!}</h1>
                        </div>
                        <div class="common-banner-link">
                            <a href="{{route('page')}}">@lang('Home')</a>
                            <i class="fa-regular fa-angle-right"></i>
                            <span>{!! $pageSeo['page_title']??'' !!}</span>
                        </div>
                    </div>
                </div>
            </section>
            <!-- common banner -->
        @endif
    @endif

    @yield('content')

    @include($theme.'partials.footer')
</div>


<!-- Scroll to top end -->
<div class="prgoress_indicator">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
    <i class="fa-duotone fa-arrow-up"></i>
</div>
<!-- Scroll to top end -->




<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{asset($themeTrue.'js/bootstrap.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/fancybox.umd.js')}}"></script>
<script src="{{asset($themeTrue.'js/select2.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.nice-select.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/appear.js')}}"></script>
<script src="{{asset($themeTrue.'js/wow.js')}}"></script>
<script src="{{asset($themeTrue.'js/owl.js')}}"></script>
<script src="{{asset($themeTrue.'js/TweenMax.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/odometer.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/swiper.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.ajaxchimp.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/parallax-scroll.js')}}"></script>
<script src="{{asset($themeTrue.'js/jarallax.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.paroller.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/flatpickr-min.js')}}"></script>
<script src="{{asset($themeTrue.'js/jquery.magnific-popup.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/socialSharing.js')}}"></script>
<script src="{{asset($themeTrue.'js/script.js')}}"></script>

@stack('js-lib')
@include('plugins')
@stack('script')
<script>
    $(document).ready(function() {
        // Get the modal
        var $modal = $("#myModal");

        // When the user clicks the button, open the modal
        $(document).on('click', '.myBtn', function (){
            $modal.show();
        });

        // When the user clicks on <span> (x), close the modal
        $(document).on('click', '.close', function() {
            $modal.hide();
        });

        // When the user clicks the close button in the footer, close the modal
        $(document).on('click', '.close-modal', function() {
            $modal.hide();
        });

        // When the user clicks anywhere outside of the modal, close it
        $(window).on('click', function(event) {
            if ($(event.target).is($modal)) {
                $modal.hide();
            }
        });
    });

    $(document).on('click','.myBtn',function (){


        let plan =  $(this).data('plan');
        let invest = $(this).data('invest');
        let profit = $(this).data('profit');
        let period = $(this).data('period');
        $('#PlanName').text(plan);
        $('.price-range').text('Invest : '+invest);
        $('.profit-details').text('Profit : '+profit);
        $('.profit-validity').text('Return Period : '+period)
        $('.plan-id').val($(this).data('id'));
        $('.number_of_return').text('Number of Return : ' + $(this).data('return_number'));

    })
</script>


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

