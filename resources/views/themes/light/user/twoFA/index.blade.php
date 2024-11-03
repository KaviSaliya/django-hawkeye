@extends($theme.'layouts.user')
@section('title',__('2 Step Security'))

@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('2 Step Security')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('2 Step Security')</li>
            </ol>
        </nav>
    </div>
    <div class="container-fluid">

        <div class="row ms-1 mt-5">
            @if(auth()->user()->two_fa == 1)
                <div class="col-lg-6 col-md-6 mb-3 coin-box-wrapper">
                    <div class="card text-center  two-factor-authenticator">
                        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-content-center py-3">
                            <h5>@lang('Two Factor Authenticator')</h5>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#regenerateModal" class="btn-1">@lang("Re-Generate") <span></span></button>
                        </div>
                        <div class="card-body">
                            <div class="box refferal-link">
                                <div class="input-group mt-0">
                                    <input
                                        type="text"
                                        value="{{$secret}}"
                                        class="form-control bg-white"
                                        id="referralURL"
                                        readonly
                                    />
                                    <span class="input-group-text" id="copyBoard"  onclick="copyFunction()"><i
                                            class="fa fa-copy"></i></span>
                                </div>
                            </div>

                            <div class="form-group mx-auto text-center py-4">

                                <img class="w-40 mx-auto qrCodeImage"
                                     src="https://quickchart.io/chart?cht=qr&chs=150x150&chl={{($qrCodeUrl)}}">


                            </div>

                            <div class="form-group mx-auto text-center">
                                <a href="javascript:void(0)" class="btn-1"
                                   data-bs-toggle="modal" data-bs-target="#disableModal">@lang('Disable Two Factor Authenticator') <span></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-lg-6 col-md-12 col-sm-12 mb-3 coin-box-wrapper">
                    <div class="card text-center  two-factor-authenticator">
                        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-content-center align-items-center py-3">
                            <h5 class="card-header-title">@lang('Two Factor Authenticator')</h5>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#regenerateModal" class="btn-1">@lang("Re-Generate")<span></span></button>
                        </div>
                        <div class="card-body">
                            <div class="box refferal-link ">

                                <div class="input-group mt-0">
                                    <input
                                        type="text"
                                        value="{{$secret}}"
                                        class="form-control bg-white"
                                        id="referralURL"
                                        readonly
                                    />
                                    <span class="input-group-text"  id="copyBoard" onclick="copyFunction()"><i
                                            class="fa fa-copy"></i></span>
                                </div>
                            </div>

                            <div class="form-group mx-auto text-center py-4">


                                <img class="w-40 mx-auto qrCodeImage"
                                     src="https://quickchart.io/chart?cht=qr&chs=150x150&chl={{($qrCodeUrl)}}">

                            </div>

                            <div class="form-group mx-auto text-center">
                                <a href="javascript:void(0)" class="btn-1"
                                   data-bs-toggle="modal"
                                   data-bs-target="#enableModal">@lang('Enable Two Factor Authenticator') <span></span></a>
                            </div>
                        </div>

                    </div>
                </div>

            @endif
            <div class="col-lg-6 col-md-12 col-sm-12 mb-3">
                <div class="card text-center   two-factor-authenticator h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-header-title ">@lang('Google Authenticator')</h5>
                    </div>
                    <div class="card-body">

                        <h6 class="text-uppercase my-3">@lang('Use Google Authenticator to Scan the QR code  or use the code')</h6>

                        <p class="py-3">@lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')</p>
                        <a class="btn-2"
                           href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en"
                           target="_blank">@lang('DOWNLOAD APP') <span></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Enable Modal -->
    <div class="modal fade" id="enableModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Verify Your OTP')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twoStepEnable')}}" method="POST">
                    @csrf
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="input-box col-12">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="number" class="form-control" name="code" placeholder="@lang('Enter Google Authenticator Code')">

                            @error("code")
                            <span class="invalid-feedback d-block" role="alert">
                                     {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-1" data-bs-dismiss="modal">@lang('Close') <span></span></button>
                    <button type="submit" class="btn-2">@lang('Verify')<span></span></button>
                </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Disable Modal -->

    <div class="modal fade" id="disableModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Verify Your OTP to Disable')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twoStepDisable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="input-box col-12">
                                <input type="password" class="form-control" name="password" placeholder="@lang('Enter Your Password')">
                                @error("password")
                                <span class="invalid-feedback d-block" role="alert">
                                     {{ $message }}
                                </span>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-1" data-bs-dismiss="modal">@lang('Close')<span></span></button>
                        <button type="submit" class="btn-2">@lang('Verify') <span></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Regenerate Modal -->


    <div class="modal fade" id="regenerateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Re-generate Confirmation')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twoStepRegenerate')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @lang("Are you want to Re-generate Authenticator") ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-1" data-bs-dismiss="modal">@lang('Close')<span></span></button>
                        <button type="submit" class="btn-2">@lang('Yes') <span></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection


@push('style')
    <style>
        .qrCodeImage{
            max-width: 100%;
            height: auto;
        }

        #copyBoard{
            cursor: pointer !important;
        }
    </style>
@endpush
@push('script')
    <script>
        function copyFunction() {
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }
    </script>
@endpush

