@extends($theme.'layouts.app')
@section('content')

    <section class="password-reset-page">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5 col-md-6 order-2 order-md-1">
                    <div class="reset-form">
                        <form action="{{ route('password.email') }}" method="post">
                            @csrf
                            <div class="section-header">
                                <h4>@lang('Recover Password')!</h4>
                                <div class="description">@lang('Regain access with your seamless and secure account retrieval process in just a few clicks')!</div>
                            </div>
                            <div class="row g-4">
                                <div class="col-12">
                                    <input type="email" name="email" value="{{old('email')}}"  class="form-control cmn-input @error('email') is-invalid @enderror" id="exampleInputEmail1" placeholder="@lang('Email address')" required>
                                </div>
                                @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                 </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn-1 d-flex justify-content-center align-items-center text-center mt-30 w-100">@lang('Send Link') <span></span></button>
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
