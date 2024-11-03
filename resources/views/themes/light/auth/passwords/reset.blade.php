@extends($theme.'layouts.app')

@section('content')
<section class="password-reset-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-md-6 order-2 order-md-1">
                <div class="reset-form">
                    <form action="{{ route('password.update') }}" method="post">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="section-header">
                            <h4>@lang('Reset Password') !</h4>
                            <div class="description">@lang('Regain access with your seamless and secure account retrieval process in just a few clicks')</div>
                        </div>
                        <div class="row g-4">
                            <div class="col-12">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"  name="email" value="{{ $email ?? old('email') }}" required placeholder="@lang('Email address')">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert"> {{ $message }} </span>
                            @enderror
                            <div class="col-12">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="@lang('Password')" required autocomplete="new-password">
                            </div>
                            @error('password')
                            <span class="invalid-feedback d-block" role="alert"> {{ $message }} </span>
                            @enderror
                            <div class="col-12">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="@lang('Confirm Password')" required autocomplete="new-password">
                            </div>
                        </div>
                        <button type="submit" class="btn-1 d-flex justify-content-center align-items-center text-center mt-30 w-100">@lang('Reset Password') <span></span></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
    <style>
        .main-footer-padding{
            padding-top: 60px;
        }
    </style>
@endpush

