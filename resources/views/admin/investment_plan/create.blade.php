@extends('admin.layouts.app')
@section('page_title', __('Investment Plan Create'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                    href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Investment Plan Create')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Investment Plan Create')</h1>
                </div>
            </div>
        </div>
        <form action="{{ route('admin.investment.plan.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3 mb-lg-5">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title">@lang('Plan Information')</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="planName" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control @error('plan_name') is-invalid @enderror"
                                            value="{{ old('plan_name') }}" name="plan_name" id="planName"
                                            placeholder="e.g : basic plan">
                                        @error('plan_name')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="plan_period_fixed">
                                    <label for="plan_period" class="form-label">@lang('Plan Period')</label>
                                    <i class="bi bi-info-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        aria-label="The total duration of the investment plan, from start to end."
                                        data-bs-original-title="The total duration of the investment plan, from start to end."></i>
                                    <div class="input-group mb-4">
                                        <input type="number" name="plan_period" value="{{ old('plan_period') }}"
                                            id="plan_period" class="form-control @error('plan_period') is-invalid @enderror"
                                            placeholder="e.g : 2 month">
                                        <!-- Select -->
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="plan_period_type" autocomplete="off"
                                                data-hs-tom-select-options='{
                                                  "placeholder": "Select a person...",
                                                  "hideSearch": true
                                             }'>
                                                <option value="Month" @selected(old('plan_period_type') == 'Month')>@lang('Month')
                                                </option>
                                                <option value="Year" @selected(old('plan_period_type') == 'Year')>@lang('Year')
                                                </option>
                                                <option value="Day" @selected(old('plan_period_type') == 'Day')>@lang('Day')
                                                </option>

                                            </select>
                                        </div>
                                        <!-- End Select -->
                                        @error('plan_period')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="minimum_invest_field">
                                    <label for="minimum_invest" class="form-label">@lang('Minimum Invest')</label>
                                    <div class="input-group mb-4">
                                        <input type="number"
                                            class="form-control @error('minimum_invest') is-invalid @enderror"
                                            value="{{ old('minimum_invest') }}" id="minimum_invest" name="minimum_invest"
                                            placeholder="e.g : 2000" step="0.01">
                                        <span class="input-group-text"
                                            id="priceCurrency">{{ basicControl()->currency_symbol }}</span>
                                        @error('minimum_invest')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="maximum_invest_field">
                                    <label for="maximum_invest" class="form-label">@lang('Maximum Invest')</label>
                                    <div class="input-group mb-4">
                                        <input type="number"
                                            class="form-control @error('maximum_invest') is-invalid @enderror"
                                            value="{{ old('maximum_invest') }}" id="minimum_invest" name="maximum_invest"
                                            placeholder="e.g : 5000" step="0.01">
                                        <span class="input-group-text"
                                            id="priceCurrency">{{ basicControl()->currency_symbol }}</span>
                                        @error('maximum_invest')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="fixed_invest_amount">
                                    <label for="plan_price" class="form-label">@lang('Fixed Amount')</label>
                                    <div class="input-group mb-4">
                                        <input type="number" class="form-control @error('plan_price') is-invalid @enderror"
                                            value="{{ old('plan_price') }}" id="plan_price" name="plan_price"
                                            placeholder="e.g : 8000" step="0.01">
                                        <span class="input-group-text"
                                            id="priceCurrency">{{ basicControl()->currency_symbol }}</span>
                                        @error('plan_price')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="return_period" class="form-label">@lang('Return Period')</label>
                                    <i class="bi bi-info-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        aria-label="The duration over which the returns are calculated for this investment plan."
                                        data-bs-original-title="The duration over which the returns are calculated for this investment plan."></i>
                                    <div class="input-group mb-4">
                                        <input type="number"
                                            class="form-control @error('return_period') is-invalid @enderror"
                                            id="return_period" value="{{ old('return_period') }}" name="return_period"
                                            placeholder="e.g : 5 days">
                                        <!-- Select -->
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="return_period_type"
                                                autocomplete="off"
                                                data-hs-tom-select-options='{
                                                  "placeholder": "Select a person...",
                                                  "hideSearch": true
                                             }'>
                                                <option value="Hour" @selected(old('return_period') == 'Hour')>@lang('Hour')
                                                </option>
                                                <option value="Day" @selected(old('return_period') == 'Day')>@lang('Day')
                                                </option>
                                                <option value="Month" @selected(old('return_period') == 'Month')>@lang('Month')
                                                </option>
                                                <option value="Year" @selected(old('return_period') == 'Year')>@lang('Year')
                                                </option>

                                            </select>
                                        </div>
                                        <!-- End Select -->

                                        @error('return_period')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="profit" class="form-label">@lang('Return')</label>
                                    <i class="bi bi-info-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        aria-label="Indicates the expected return rate for this specific investment plan."
                                        data-bs-original-title="Indicates the expected return rate for this specific investment plan."></i>
                                    <div class="input-group mb-4">
                                        <input type="number" name="profit" id="profit" value="{{ old('profit') }}"
                                            class="form-control @error('profit') is-invalid @enderror"
                                            placeholder="e.g : 2%" step="0.01">
                                        <!-- Select -->
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="profit_type" autocomplete="off"
                                                data-hs-tom-select-options='{
                                                  "hideSearch": true
                                             }'>
                                                <option value="Fixed" @selected(old('profit_type') == 'Fixed')>
                                                    {{ basicControl()->currency_symbol }}</option>
                                                <option value="Percentage" @selected(old('profit_type') == 'Percentage')>%</option>

                                            </select>
                                        </div>
                                        <!-- End Select -->
                                        @error('profit')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="number_of_return">
                                    <label for="number_of_return" class="form-label">@lang('Number Of Return')</label>

                                    <i class="bi bi-info-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        aria-label="Indicate how many times returns are expected or have occurred."
                                        data-bs-original-title="Indicate how many times returns are expected or have occurred."></i>
                                    <div class="mb-4">
                                        <input type="number" name="number_of_return"
                                            value="{{ old('number_of_return') }}" id="number_of_return"
                                            class="form-control @error('number_of_return') is-invalid @enderror"
                                            placeholder="e.g : 12 times">
                                        @error('number_of_return')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="profit" class="form-label">@lang('Maturity')</label>
                                    <i class="bi bi-info-circle text-body ms-1" data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        aria-label="The time period after which the investment begins to yield returns."
                                        data-bs-original-title="The time period after which the investment begins to yield returns."></i>
                                    <div class="input-group mb-4">
                                        <input type="number" name="maturity" value="{{ old('maturity') }}"
                                            class="form-control @error('maturity') is-invalid @enderror"
                                            placeholder="e.g : 60 days">
                                        <!-- Select -->
                                        <span class="input-group-text">@lang('Days')</span>
                                        @error('maturity')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label mb-3" for="">@lang('Image')</label>
                                    <label class="form-check form-check-dashed" for="logoUploader" id="content_img">
                                        <img id="contentImg"
                                            class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                            src="{{ asset('assets/admin/img/oc-browse-file-light.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="default">
                                        <img id="contentImg"
                                            class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                            src="{{ asset('assets/admin/img/oc-browse-file.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="dark">
                                        <span class="d-block">@lang('Browse your file here')</span>
                                        <input type="file" name="image" class="js-file-attach form-check-input"
                                            id="logoUploader"
                                            data-hs-file-attach-options='{
                                                                      "textTarget": "#contentImg",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                                   }'>
                                    </label>
                                    @error('image')
                                        <span class="invalid-feedback d-block" role="alert">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- Body -->
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h4 class="card-header-title">@lang(' Status')</h4>
                                </div>
                                <div class="card-body">
                                    <div class="list-group-item mb-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h5 class="mb-0">@lang('Invest amount has fixed ?')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang('Invest amount has fixed then turn on this button')</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="has_amount_fixed" value="0">
                                                            <input class="form-check-input" name="has_amount_fixed"
                                                                type="checkbox" id="has_amount_fixed" value="1"
                                                                @checked(old('has_amount_fixed') == 1) checked>
                                                            <label class="form-check-label"
                                                                for="has_amount_fixed"></label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 ms-3 mb-4">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h5 class="mb-0">@lang('Capital Back')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang('If you want to return of the original amount of money invested at the end of the investment period , then please turn on this button')</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="capital_back" value="0">
                                                            <input class="form-check-input" name="capital_back"
                                                                type="checkbox" id="capital_back" value="1"
                                                                @checked(old('capital_back') == 1)>
                                                            <label class="form-check-label" for="capital_back"></label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item mb-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h5 class="mb-0">@lang('Return Type')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang('If return type has lifetime then turn on this button.')</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="number_of_return_type"
                                                                value="0">
                                                            <input class="form-check-input" name="number_of_return_type"
                                                                type="checkbox" id="number_of_return_type" value="1"
                                                                @checked(old('number_of_return_type') == 1)>
                                                            <label class="form-check-label"
                                                                for="number_of_return_type"></label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item mb-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h5 class="mb-0">@lang('Plan Period Type')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang('If plan period has unlimited then turn on this button.')</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="unlimited_period" value="0">
                                                            <input class="form-check-input" name="unlimited_period"
                                                                type="checkbox" id="unlimited_period" value="1"
                                                                @checked(old('unlimited_period') == 1)>
                                                            <label class="form-check-label"
                                                                for="unlimited_period"></label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('unlimited_period')
                                            <span class="ms-4 invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h4 class="card-header-title">@lang('Publish')</h4>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <button class="btn btn-primary" type="submit" name="status"
                                            value="1">@lang('Save & Publish')</button>
                                        <button class="btn btn-info ms-3" type="submit" name="status"
                                            value="0">@lang('Save & Draft')</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>


    </div>



@endsection
@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-add-field.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
@endpush
@push('script')
    <script>
        (function() {
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
        })();
        (function() {
            // INITIALIZATION OF ADD FIELD
            // =======================================================
            new HSAddField('.js-add-field')
        })();
        (function() {
            // INITIALIZATION OF SELECT
            // =======================================================
            HSCore.components.HSTomSelect.init('.js-select')
        })();
        $(document).on('click', '.deleteInputField', function() {
            $(this).closest('.row').remove();
        });



        if ($('#has_amount_fixed').is(':checked')) {
            $('#minimum_invest_field').hide();
            $('#maximum_invest_field').hide();
            $('#fixed_invest_amount').show();
        } else {
            $('#minimum_invest_field').show();
            $('#maximum_invest_field').show();
            $('#fixed_invest_amount').hide();
        }
        if ($('#number_of_return_type').is(':checked')) {
            $('#number_of_return').hide()
        } else {
            $('#number_of_return').show()
        }

        if ($('#unlimited_period').is(':checked')) {
            $('#plan_period_fixed').hide()
        } else {
            $('#plan_period_fixed').show()
        }

        $(document).on('change', '#unlimited_period', function() {
            if ($(this).is(':checked')) {
                $('#plan_period_fixed').hide()
            } else {
                $('#plan_period_fixed').show()
            }
        })

        $(document).on('change', '#number_of_return_type', function() {
            if ($(this).is(':checked')) {
                $('#number_of_return').hide()
            } else {
                $('#number_of_return').show()
            }
        })

        $(document).on('change', '#has_amount_fixed', function() {
            if ($(this).is(':checked')) {
                $('#minimum_invest_field').hide();
                $('#maximum_invest_field').hide();
                $('#fixed_invest_amount').show();
            } else {
                $('#minimum_invest_field').show();
                $('#maximum_invest_field').show();
                $('#fixed_invest_amount').hide();
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const capitalBackCheckbox = document.getElementById('capital_back');
            const returnTypeCheckbox = document.getElementById('number_of_return_type');

            function updateCheckboxes() {
                if (capitalBackCheckbox.checked) {
                    returnTypeCheckbox.checked = false;
                    returnTypeCheckbox.disabled = true;
                } else if (returnTypeCheckbox.checked) {
                    capitalBackCheckbox.checked = false;
                    capitalBackCheckbox.disabled = true;
                } else {
                    returnTypeCheckbox.disabled = false;
                    capitalBackCheckbox.disabled = false;
                }
            }
            capitalBackCheckbox.addEventListener('change', updateCheckboxes);
            returnTypeCheckbox.addEventListener('change', updateCheckboxes);
            updateCheckboxes();
        });
    </script>
@endpush
