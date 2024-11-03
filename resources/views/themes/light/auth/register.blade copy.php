@extends('themes.light.layouts.app')
@section('content')
    <!-- Register -->
    <section class="sign-in">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="sign-in-container">
                        <div class="sign-in-container-inner">

                            <div class="sign-in-title">
                                <h3 class="mb_15">{!! $login_registration['single']['register_heading'] !!}</h3>
                                <p>{!! $login_registration['single']['register_subheading'] !!}</p>
                            </div>
                            <div class="sign-in-form">

                                <form action="{{ route('register') }}" method="post">
                                    @csrf
                                    @if ($referUser != null)
                                        <div class="sign-in-form-group">
                                            <label>@lang('Refer By')</label>
                                            <input type="text" name="referral" class="sign-in-input"
                                                value="{{ $referUser->username }}" readonly>
                                        </div>
                                    @endif
                                    <div class="sign-in-form-name">
                                        <div class="sign-in-form-group">
                                            <label>@lang('First Name')</label>
                                            <input type="text" name="first_name" class="sign-in-input"
                                                value="{{ old('first_name') }}" placeholder="@lang('First Name')" required>
                                            @error('first_name')
                                                <span class="invalid-feedback d-block" role="alert"> {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="sign-in-form-group">
                                            <label>@lang('Last Name')</label>
                                            <input type="text" name="last_name" class="sign-in-input"
                                                value="{{ old('last_name') }}" placeholder="@lang('Last Name')" required>
                                            @error('last_name')
                                                <span class="invalid-feedback d-block" role="alert"> {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label>@lang('Email')</label>
                                        <input type="email" name="email" class="sign-in-input"
                                            value="{{ old('email') }}" placeholder="@lang('Enter your email')" required>
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label>@lang('Username')</label>
                                        <input type="text" name="username" class="sign-in-input"
                                            value="{{ old('username') }}" placeholder="@lang('Enter your username')" required>
                                        @error('username')
                                            <span class="invalid-feedback d-block" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label for="organization" class="form-label">@lang('Phone Number')</label>
                                        <div class="phone-input">
                                            <input type="hidden" name="phone_code" id="phoneCode">
                                            <input type="hidden" name="country_code" id="countryCode">
                                            <input type="hidden" name="country" id="countryName">
                                            <input type="tel" id="telephone" name="phone" value="{{ old('phone') }}"
                                                class="sign-in-input">
                                        </div>
                                        @error('phone')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label>@lang('Password')</label>
                                        <div class="password-box">
                                            <input type="password" name="password" class="sign-in-input password"
                                                placeholder="@lang('Password')" autocomplete="off" required>
                                            <i class="password-icon fa-regular fa-eye"></i>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label>@lang('Confirm Password')</label>
                                        <div class="confirm_password_box password-box">
                                            <input type="password" name="password_confirmation"
                                                class="sign-in-input  confirm_password" placeholder="@lang('Confirm Password')"
                                                autocomplete="off" required>
                                            <i class="confirm_password_icon  fa-regular fa-eye"></i>
                                        </div>
                                    </div>
                                    @if ($basicControl->manual_recaptcha === 1 && $basicControl->reCaptcha_status_registrationuser === 1)
                                        <div class="sign-in-form-group">
                                            <label for="captcha">@lang('Captcha')</label>
                                            <input type="text" name="captcha" id="captcha" class="sign-in-input"
                                                placeholder="@lang('Enter Captcha')">
                                            @error('captcha')
                                                <span class="invalid-feedback d-block" role="alert"> {{ $message }}
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="mt-4">
                                            <div class="input-group captcha-group input-group-merge manualRecaptchaImage"
                                                data-hs-validation-validate-class>
                                                <img src="{{ route('captcha') . '?rand=' . rand() }}" id='captcha_image'>
                                                <a class="input-group-append input-group-text manualRecaptchaIcon"
                                                    href='javascript: refreshCaptcha();'>
                                                    <i class="fa-solid fa-rotate-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    @if (basicControl()->google_user_registration_recaptcha_status && basicControl()->google_recaptcha)
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
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">@lang('Create Account') <span></span></button>
                                    </div>
                                </form>
                                <div class="media-login">
                                    <div class="signup-account">
                                        <p>@lang('Already have an account') ? <a href="{{ route('login') }}">@lang('Log In')</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="sign-in-image text-end">
                        <img src="{{ $login_registration['single']['media']->register_page_image ? getFile($login_registration['single']['media']->register_page_image->driver, $login_registration['single']['media']->register_page_image->path) : '' }}"
                            alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Register -->
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

        $(document).ready(function (){

            // International Telephone Input start
            const input = document.querySelector("#telephone");
            const iti = window.intlTelInput(input, {
                initialCountry: "bd",
                separateDialCode: true,
            });
            input.addEventListener("countrychange", updateCountryInfo);
            updateCountryInfo();
            function updateCountryInfo() {
                const selectedCountryData = iti.getSelectedCountryData();
                const phoneCode = '+' + selectedCountryData.dialCode;
                const countryCode = selectedCountryData.iso2;
                const countryName = selectedCountryData.name;
                $('#phoneCode').val(phoneCode);
                $('#countryCode').val(countryCode);
                $('#countryName').val(countryName);
            }

            const initialPhone = "{{$user->phone??null}}";
            const initialPhoneCode = "{{$user->phone_code??null}}";
            const initialCountryCode = "{{$user->country_code??null}}";
            const initialCountry = "{{$user->country??null}}";
            if (initialPhoneCode) {
                iti.setNumber(initialPhoneCode);
            }
            if (initialCountryCode) {
                iti.setNumber(initialCountryCode);
            }
            if (initialCountry) {
                iti.setNumber(initialCountry);
            }
            if (initialPhone) {
                iti.setNumber(initialPhone);
            }
        })
    </script>
@endpush


