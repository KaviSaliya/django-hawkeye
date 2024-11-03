@extends('admin.layouts.app')
@section('page_title', __('Cookie'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('GDPR Cookie')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('GDPR Cookie')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="shadow p-3 mb-5 alert-soft-blue mb-4 mb-lg-7" role="alert">
                    <div class="alert-box d-flex flex-wrap align-items-center">
                        <div class="flex-shrink-0">
                            <img class="avatar avatar-xl"
                                 src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="default">
                            <img class="avatar avatar-xl"
                                 src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                                 alt="Image Description" data-hs-theme-appearance="dark">
                        </div>

                        <div class="flex-grow-1 ms-3">
                            <h3 class="alert-heading text-info mb-1">@lang("Attention!")</h3>
                            <div class="d-flex align-items-center">
                                <p class="mb-0 text-info"> @lang(" If you get 500(server error) for some reason, please turn on `Debug Log` and try again. Then you can see what was missing in your system. ")</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-8" id="basic_control">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Basic Controls')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.update.cookie') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label for="siteTitleLabel" class="form-label">@lang('Title')</label>
                                        <input type="text"
                                               class="form-control  @error('cookie_title') is-invalid @enderror"
                                               name="cookie_title" id="siteTitleLabel"
                                               placeholder="@lang("Title")" aria-label="@lang("Title")" autocomplete="off"
                                               value="{{ old('cookie_title',$basicControl->cookie_title) }}">
                                        @error('cookie_title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="CurrencySymbolLabel"
                                               class="form-label">@lang('Button Name')</label>
                                        <input type="text"
                                               class="form-control @error('cookie_button_name') is-invalid @enderror"
                                               name="cookie_button_name"
                                               id="CurrencySymbolLabel" autocomplete="off"
                                               placeholder="@lang("Button Name")" aria-label="@lang("Button Name")"
                                               value="{{ old('cookie_button_name',$basicControl->cookie_button_name) }}">
                                        @error('cookie_button_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <label for="baseCurrencyLabel" class="form-label">@lang('Button Url')</label>
                                        <input type="text"
                                               class="form-control  @error('cookie_button_url') is-invalid @enderror"
                                               name="cookie_button_url"
                                               id="baseCurrencyLabel" autocomplete="off"
                                               placeholder="@lang("Button Url")" aria-label="@lang("Button Url")"
                                               value="{{ old('cookie_button_url',$basicControl->cookie_button_url) }}">
                                        @error('cookie_button_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex mt-4">
                                            <div class="flex-grow-1">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <h5 class="mb-0">@lang('Cookie Status')</h5>
                                                        <span
                                                            class="d-block fs-6 text-body">@lang('Enable or Disable Cookie')</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <label class="row form-check form-switch mb-3" for="cookie_status">
                                                            <span class="col-4 col-sm-3 text-end">
                                                                <input type='hidden' value='0' name='cookie_status'>
                                                                <input
                                                                    class="form-check-input @error('cookie_status') is-invalid @enderror"
                                                                    type="checkbox" name="cookie_status" @checked($basicControl->cookie_status == 1)
                                                                    id="cookie_status"
                                                                    value="1">
                                                            </span>
                                                            @error('cookie_status')
                                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                                            @enderror
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <label for="baseCurrencyLabel" class="form-label">@lang('Short Text')</label>
                                        <input type="text"
                                               class="form-control  @error('cookie_short_text') is-invalid @enderror"
                                               name="cookie_short_text"
                                               id="baseCurrencyLabel" autocomplete="off"
                                               placeholder="@lang("Short Text")" aria-label="@lang("Short Text")"
                                               value="{{ old('cookie_short_text',$basicControl->cookie_short_text) }}">
                                        @error('cookie_short_text')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="col-form-label">@lang('Cookie Image')</label>
                                        <label class="form-check form-check-dashed" for="logoUploader">
                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{  getFile($basicControl->cookie_driver, $basicControl->cookie_image, true) }}"
                                                 alt="@lang("Logo")"
                                                 data-hs-theme-appearance="default">

                                            <img id="logoImg"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2 object-fit-contain"
                                                 src="{{ getFile($basicControl->cookie_driver, $basicControl->cookie_image, true) }}"
                                                 alt="@lang("Logo")" data-hs-theme-appearance="dark">
                                            <span class="d-block mb-3">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach-logo form-check-input"
                                                   name="cookie_image" id="logoUploader"
                                                   data-hs-file-attach-options='{
                                                      "textTarget": "#logoImg",
                                                      "mode": "image",
                                                      "targetAttr": "src",
                                                      "allowTypes": [".png", ".jpeg", ".jpg", ".svg"]
                                                   }'>
                                        </label>
                                        @error("cookie_image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start">
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

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 500
            })
        })();

        $(document).ready(function () {

            new HSFileAttach('.js-file-attach-logo', {
                textTarget: "#logoImg"
            });


        })

    </script>
@endpush
