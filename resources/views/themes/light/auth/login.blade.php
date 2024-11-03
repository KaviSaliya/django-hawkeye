@extends($theme.'layouts.app')
@section('title',trans('Login'))
@section('content')

    <!-- sign-in -->
    <section class="sign-in">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="sign-in-container">
                        <div class="sign-in-container-inner">
                            <div class="sign-in-logo mb_30">
                                <a href="{{route('page')}}"><img src="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" alt="logo"></a>
                            </div>
                            <div class="sign-in-title">
                                <h3 class="mb_15">{!! $login_registration['single']['login_heading'] !!}</h3>
                                <p>{!! $login_registration['single']['login_subheading'] !!}</p>
                            </div>
                            <div class="sign-in-form">
                                <form action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="sign-in-form-group">
                                        <label>@lang('Username or Email')</label>
                                        <input type="text" name="username" value="{{old('username')}}" class="sign-in-input" placeholder="@lang('username or email')" required>
                                        @error('username')
                                        <span class="invalid-feedback d-block" role="alert">{{ $message }}<strong></strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label>@lang('Password')</label>
                                        <div class="password-box">
                                            <input type="password" name="password" value="{{old('password')}}" class="sign-in-input password" placeholder="@lang('Password')" autocomplete="off" required>
                                            <i class="password-icon fa-regular fa-eye"></i>

                                            @error('password')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }} </span>
                                            @enderror
                                        </div>
                                    </div>

                                    @if($basicControl->manual_recaptcha === 1 && $basicControl->reCaptcha_status_login === 1)
                                        <div class="sign-in-form-group">
                                            <label>@lang('Captcha')</label>
                                            <input type="text"   name="captcha" id="captcha" class="sign-in-input" placeholder="@lang('enter captcha')">
                                            @error('captcha')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mt-4">
                                            <div class="input-group input-group-merge manualRecaptchaImage" data-hs-validation-validate-class>
                                                <img src="{{route('captcha').'?rand='. rand()}}" id='captcha_image' >
                                                <a class="input-group-append input-group-text manualRecaptchaIcon"
                                                   href='javascript: refreshCaptcha();'>
                                                    <i class="fa-solid fa-rotate-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if(basicControl()->google_user_login_recaptcha_status && basicControl()->google_recaptcha)
                                        <div class="row mt-4">
                                            <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                                 data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                            @error('g-recaptcha-response')
                                            <span class="invalid-feedback d-block text-danger" role="alert">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                    @endif
                                    <div class="rember">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                   id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label>@lang('Remember me')</label>
                                        </div>
                                        <div class="rember-password">
                                            <a href="{{ route('password.request') }}">@lang('Forget Password')?</a>
                                        </div>
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">@lang('Log In') <span></span></button>
                                    </div>
                                </form>
                                <div class="media-login">
                                    <div class="media-login-border"><h5>@lang('OR')</h5></div>
                                    <ul>
                                        @if(config('socialite.facebook_status'))
                                            <li><a href="{{route('socialiteLogin','facebook')}}"><img src="{{asset($themeTrue.'images/icons/facebook.png')}}" alt="icon"></a></li>
                                        @endif
                                            @if(config('socialite.google_status'))
                                                <li><a href="{{route('socialiteLogin','google')}}"><img src="{{asset($themeTrue.'images/icons/google.png')}}" alt="icon"></a></li>
                                            @endif
                                        @if(config('socialite.github_status'))
                                              <li><a href="{{route('socialiteLogin','github')}}"><img src="{{asset($themeTrue.'images/icons/github.png')}}" alt="icon"></a></li>
                                        @endif

                                    </ul>
                                    <div class="signup-account">
                                        <p>@lang('Don’t have an account') ? <a href="{{ route('register') }}">@lang('Sign Up')</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="sign-in-image text-end">
                        <img src="{{$login_registration['single']['media']->login_page_image?getFile($login_registration['single']['media']->login_page_image->driver,$login_registration['single']['media']->login_page_image->path):''}}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- sign-in -->

@endsection

@push('js-lib')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

@push('style')
    <style>
        .main-footer-padding{
            padding-top: 60px;
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        function refreshCaptcha(){
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0,img.src.lastIndexOf("?")
            )+"?rand="+Math.random()*1000;
        }
    </script>
@endpush

