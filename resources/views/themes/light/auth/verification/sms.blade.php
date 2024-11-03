@extends($theme.'layouts.app')
@section('title',$page_title)

@section('content')
    <section  class="password-reset-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-md-6 order-2 order-md-1">
                    <div class="reset-form">
                        <form class="login-form" action="{{route('user.smsVerify')}}"  method="post">
                            @csrf
                            <div class="section-header">
                                <h4>@lang($page_title)</h4>
                            </div>
                            <div class="signin">
                                <div class="form-group mb-30">
                                    <input class="form-control" type="text" name="code" value="{{old('code')}}" placeholder="@lang('Code')" autocomplete="off" required>
                                </div>
                                @error('code')<span class="text-danger d-block  mt-2">{{ $message }}</span>@enderror

                                <div class="btn-area">
                                    <button type="submit" class="btn-1 d-flex justify-content-center align-items-center text-center mt-30 w-100">@lang('Submit') <span></span></button>
                                </div>

                                <div class="login-query mt-30 text-center">
                                    <p>@lang("Didn't get Code? Click to") <a href="{{route('user.resendCode')}}?type=mobile"  class="text-info"> @lang('Resend code')</a></p>
                                    @error('resend')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror

                                </div>
                            </div>
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
