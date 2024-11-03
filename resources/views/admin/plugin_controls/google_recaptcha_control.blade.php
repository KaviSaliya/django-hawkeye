@extends('admin.layouts.app')
@section('page_title', __('Plugin Controls'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Plugin Controls')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Plugin Controls')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.plugin'), 'suffix' => ''])
            </div>
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card h-100">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang('Google Recaptcha Configuration')</h4>
                        </div>
                        <!-- Body -->
                        <div class="card-body">
                            <form action="{{ route('admin.google.recaptcha.Configuration.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                @include('errors.error')

                                <div class="row mb-4">
                                    <label for="GoogleRecaptchaSiteKey"
                                           class="col-sm-3 col-form-label form-label">@lang("Google Recaptcha Site Key")</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('google_recaptcha_site_key') is-invalid @enderror"
                                               name="google_recaptcha_site_key" id="GoogleRecaptchaSiteKey"
                                               placeholder="@lang("Google Recaptcha Site Key")"
                                               value="{{ old('google_recaptcha_site_key', $googleRecaptchaSiteKey) }}"
                                               autocomplete="off">
                                        @error('google_recaptcha_site_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="googleRecaptchaSecretKey"
                                           class="col-sm-3 col-form-label form-label">@lang("Google Recaptcha Secret Key")</label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('google_recaptcha_secret_key') is-invalid @enderror"
                                               name="google_recaptcha_secret_key" id="googleRecaptchaSecretKey"
                                               placeholder="@lang("Google Recaptcha Secret Key")"
                                               value="{{ old('google_recaptcha_secret_key', $googleRecaptchaSecretKey) }}"
                                               autocomplete="off">
                                        @error('google_recaptcha_secret_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label for="googleRecaptchaSiteVerifyUrl"
                                           class="col-sm-3 col-form-label form-label">@lang("Site Verify Url") <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="@lang("Please put your Google Recaptcha Site Verify Url.")"></i></label>
                                    <div class="col-sm-9">
                                        <input type="text"
                                               class="form-control @error('google_recaptcha_site_verify_url') is-invalid @enderror"
                                               name="google_recaptcha_site_verify_url" id="googleRecaptchaSiteVerifyUrl"
                                               placeholder="@lang("google Recaptcha Site Verify Url")"
                                               value="{{ old('google_recaptcha_secret_key', $googleRecaptchaSiteVerifyUrl) }}"
                                               autocomplete="off">
                                        @error('google_recaptcha_site_verify_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-4">
                                    <div class="col-sm-12">
                                        <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                                            <!-- List Item -->
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img class="avatar avatar-xs" src="{{ asset('assets/admin/img/user-login.svg') }}" alt="Image Description">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <h4 class="mb-0">@lang("Admin Login")</h4>
                                                                <p class="fs-5 text-body mb-0">@lang("Manual reCAPTCHA enhances admin login security by verifying human interaction.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" name="google_admin_login_recaptcha_status" value="0">
                                                                    <input class="form-check-input" name="google_admin_login_recaptcha_status" type="checkbox" id="adminLoginRecaptcha" value="1"
                                                                        {{ $basicControl->google_admin_login_recaptcha_status	 == 1 ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="adminLoginRecaptcha"></label>
                                                                    @error('admin_login_recaptcha')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End List Item -->

                                            <!-- List Item -->
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img class="avatar avatar-xs" src="{{ asset('assets/admin/img/user-login.svg') }}" alt="Image Description">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <h4 class="mb-0">@lang("User Login")</h4>
                                                                <p class="fs-5 text-body mb-0">@lang("Manual reCAPTCHA enhances login security by verifying human interaction.")</p>
                                                            </div>

                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" name="google_user_login_recaptcha_status" value="0">
                                                                    <input class="form-check-input" name="google_user_login_recaptcha_status" type="checkbox" id="userLoginRecaptcha" value="1"
                                                                        {{ $basicControl->google_user_login_recaptcha_status == 1 ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="userLoginRecaptcha"></label>
                                                                    @error('user_login_recaptcha')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End List Item -->
                                            <!-- List Item -->
                                            <div class="list-group-item">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <img class="avatar avatar-xs" src="{{ asset('assets/admin/img/user-login.svg') }}" alt="Image Description">
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <h4 class="mb-0">@lang('User Registration')</h4>
                                                                <p class="fs-5 text-body mb-0">@lang("Manual reCAPTCHA enhances registration security by verifying human interaction.")</p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="form-check form-switch">
                                                                    <input type="hidden" name="google_user_registration_recaptcha_status" value="0">
                                                                    <input class="form-check-input" type="checkbox" name="google_user_registration_recaptcha_status" id="userRegistrationRecaptcha" value="1"
                                                                        {{ $basicControl->google_user_registration_recaptcha_status == 1 ? 'checked' : ''}}>
                                                                    <label class="form-check-label" for="userRegistrationRecaptcha"></label>
                                                                    @error('user_registration_recaptcha')
                                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End List Item -->

                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
