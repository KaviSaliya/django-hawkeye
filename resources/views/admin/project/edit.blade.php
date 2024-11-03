@extends('admin.layouts.app')
@section('page_title',__('Edit Project'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit Project')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Project')</h1>
                </div>
            </div>
        </div>

        <div class="alert alert-soft-dark mb-5" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="dark">
                </div>

                <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">@lang("You are edit project for `$language->name` version ")</p>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{route('admin.project.update',[$project->id,$language->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('put')
            <input type="hidden"  name="language_id" value="{{$language->id}}">
            <div class="row">
                <div class=" col-lg-8 col-md-12 mb-5">
                    <div class="card mb-3 mb-lg-5">
                        <!-- Header -->
                        <div class="card-header">
                            <h4 class="card-header-title">@lang('Project Information')</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="projectTitle" class="form-label">@lang('Project Title')</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror" value="{{old('title',optional($project->details)->title)}}" name="title" id="projectTitle" placeholder="e.g : green project">
                                        @error("title")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="project_location" class="form-label">@lang('Location')</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" value="{{old('location',$project->location)}}" id="project_location" name="location" placeholder="e.g : ironclad city">
                                        @error("location")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="">
                                        <label for="permalinkLabel" class="form-label">@lang("Permalink")</label>
                                        <div class="d-inline-flex">
                                            <div class="default-slug d-flex justify-content-end align-items-center">
                                                <span class="ps-3">{{ url('/') }}</span>
                                                <input type="text" class="form-control set-slug" name="slug"
                                                       id="newSlug" placeholder="@lang("Slug")" autocomplete="off" value="{{ old("slug",optional($project->details)->slug) }}">
                                            </div>
                                        </div>
                                    </div>
                                    @error("slug")
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="project_duration" class="form-label">@lang('Project Duration')</label>
                                    <i class="bi bi-info-circle text-body ms-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       aria-label="The total duration of the project, from start to end."
                                       data-bs-original-title="The total duration of the project, from start to end."
                                    ></i>
                                    <div class="input-group mb-4">
                                        <input type="number" name="project_duration" value="{{old('project_duration',$project->project_duration)}}" id="project_duration" class="form-control @error('project_cycle') is-invalid @enderror" placeholder="e.g : 5 years" step="0.1">
                                        <!-- Select -->
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="project_duration_type" id="project_duration_type"  autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                  "hideSearch": true
                                             }'>
                                                <option value="Month" @selected(old('project_duration_type',$project->project_duration_type) == 'Month')>@lang('Month')</option>
                                                <option value="Year" @selected(old('project_duration_type',$project->project_duration_type) == 'Year')>@lang('Year')</option>
                                                <option value="Day" @selected(old('project_duration_type',$project->project_duration_type) == 'Day')>@lang('Day')</option>

                                            </select>
                                        </div>
                                        <!-- End Select -->
                                        @error("project_duration")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="return" class="form-label">@lang('Return')</label>
                                    <i class="bi bi-info-circle text-body ms-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       aria-label="Indicates the expected return rate for this project."
                                       data-bs-original-title="Indicates the expected return rate for this project."
                                    ></i>
                                    <div class="input-group mb-4">
                                        <input type="number" name="return" id="return"  value="{{old('return',$project->return)}}" class="form-control @error('return') is-invalid @enderror" placeholder="e.g : 5%" step="0.01">
                                        <!-- Select -->
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="return_type" autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                  "hideSearch": true
                                             }'>
                                                <option value="Fixed" @selected(old('return_type',$project->return_type) == 'Fixed')>{{basicControl()->currency_symbol}}</option>
                                                <option value="Percentage" @selected(old('return_type',$project->return_type) == 'Percentage')>%</option>

                                            </select>
                                        </div>
                                        <!-- End Select -->
                                        @error("return")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="return_period" class="form-label">@lang('Return Period')</label>
                                    <i class="bi bi-info-circle text-body ms-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       aria-label="The duration over which the returns are calculated for this project."
                                       data-bs-original-title="The duration over which the returns are calculated for this project."
                                    ></i>
                                    <div class="input-group mb-4">
                                        <input type="number" class="form-control @error('return_period') is-invalid @enderror" id="return_period" value="{{old('return_period',$project->return_period)}}" name="return_period" placeholder="e.g : 1 month" step="0.1">
                                        <!-- Select -->
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="return_period_type" autocomplete="off"
                                                    data-hs-tom-select-options='{
                                                  "placeholder": "Select a person...",
                                                  "hideSearch": true
                                             }'> <option value="Hour" @selected(old('return_period_type',$project->return_period_type) == 'Hour')>@lang('Hour')</option>
                                                <option value="Day" @selected(old('return_period_type',$project->return_period_type) == 'Day')>@lang('Day')</option>
                                                <option value="Month" @selected(old('return_period_type',$project->return_period_type) == 'Month')>@lang('Month')</option>
                                                <option value="Year" @selected(old('return_period_type',$project->return_period_type) == 'Year')>@lang('Year')</option>

                                            </select>
                                        </div>
                                        <!-- End Select -->

                                        @error("return_period")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="number_of_return" class="form-label">@lang('Number Of Return')</label>
                                    <i class="bi bi-info-circle text-body ms-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       aria-label="Indicate how many times returns are expected or have occurred."
                                       data-bs-original-title="Indicate how many times returns are expected or have occurred."
                                    ></i>
                                    <div class="mb-4">
                                        <input type="number" name="number_of_return" value="{{old('number_of_return',$project->number_of_return)}}" id="number_of_return" class="form-control @error('number_of_return') is-invalid @enderror" placeholder="e.g : 12 times">
                                        @error("number_of_return")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">@lang('Project Starting Date')</label>
                                    <div class="mb-4">
                                        <!-- Flatpickr -->
                                        <input type="text" class="js-flatpickr form-control flatpickr-custom" id="start_date" name="start_date" value="{{old('start_date',$project->start_date)}}" placeholder="Select dates"
                                               data-hs-flatpickr-options='{
                                             "dateFormat": "Y/m/d"
                                           }'>
                                        <!-- End Flatpickr -->
                                        @error("start_date")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="start_date" class="form-label">@lang('Investment last date')</label>
                                    <div class="mb-4">
                                        <!-- Flatpickr -->
                                        <input type="text" class="js-flatpickr form-control flatpickr-custom" id="start_date" name="invest_last_date" value="{{old('invest_last_date',$project->invest_last_date)}}" placeholder="Select dates"
                                               data-hs-flatpickr-options='{
                                             "dateFormat": "Y/m/d"
                                           }'>
                                        <!-- End Flatpickr -->
                                        @error("invest_last_date")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="minimum_invest_field">
                                    <label for="minimum_invest" class="form-label">@lang('Minimum Invest')</label>
                                    <div class="input-group mb-4">
                                        <input type="number" class="form-control @error('minimum_invest') is-invalid @enderror" value="{{old('minimum_invest',$project->minimum_invest)}}" id="minimum_invest" name="minimum_invest" placeholder="e.g : 15000" step="0.01">
                                        <span class="input-group-text" id="priceCurrency">{{basicControl()->currency_symbol}}</span>
                                        @error("minimum_invest")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="maximum_invest_field">
                                    <label for="maximum_invest" class="form-label">@lang('Maximum Invest')</label>
                                    <div class="input-group mb-4">
                                        <input type="number" class="form-control @error('maximum_invest') is-invalid @enderror" value="{{old('maximum_invest',$project->maximum_invest)}}" id="minimum_invest" name="maximum_invest" placeholder="e.g : 20000" step="0.01">
                                        <span class="input-group-text" id="priceCurrency">{{basicControl()->currency_symbol}}</span>
                                        @error("maximum_invest")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="fixed_invest_amount">
                                    <label for="invest_amount" class="form-label">@lang('Invest amount')</label>
                                    <div class="input-group mb-4">
                                        <input type="number" class="form-control @error('invest_amount') is-invalid @enderror" value="{{old('invest_amount',$project->fixed_invest)}}" id="invest_amount" name="invest_amount" placeholder="e.g : 25000" step="0.01">
                                        <span class="input-group-text" id="priceCurrency">{{basicControl()->currency_symbol}}</span>
                                        @error("invest_amount")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="TotalUnit" class="form-label">@lang('Total Units')</label>
                                    <div class="mb-4">
                                        <input type="number" class="form-control @error('total_units') is-invalid @enderror" value="{{old('total_units',$project->total_units)}}" id="TotalUnit" name="total_units" placeholder="e.g : 500">
                                        @error("total_units")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="profit" class="form-label">@lang('Maturity')</label>
                                    <i class="bi bi-info-circle text-body ms-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       aria-label="The time period after which the investment begins to yield returns."
                                       data-bs-original-title="The time period after which the investment begins to yield returns."
                                    ></i>
                                    <div class="input-group mb-4">
                                        <input type="number" name="maturity"   value="{{old('maturity',$project->maturity)}}" class="form-control @error('maturity') is-invalid @enderror" placeholder="e.g : 60 days">
                                        <!-- Select -->
                                        <span class="input-group-text">@lang('Days')</span>
                                        @error("maturity")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Body -->
                    </div>


                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-header-title">@lang('Short Description')</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <textarea class="summernote" name="short_description"> {{old('short_description',optional($project->details)->short_description)}}</textarea>
                                    @error('short_description')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card ">
                        <div class="card-header">
                            <h5 class="card-header-title">@lang('Description')</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <textarea class="summernote" name="description"> {{old('description',optional($project->details)->description)}}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 mb-lg-5 ">
                        <!-- Header -->
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang('Thumbnail')</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <div class="col-12">
                                <label class="form-check form-check-dashed"
                                       for="logoUploader" id="content_img">
                                    <img id="contentImg"
                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                         src="{{getFile($project->thumbnail_image_driver,$project->thumbnail_image)}}"
                                         alt="Image Description"
                                         data-hs-theme-appearance="default">
                                    <img id="contentImg"
                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                         src="{{getFile($project->thumbnail_image_driver,$project->thumbnail_image)}}"
                                         alt="Image Description"
                                         data-hs-theme-appearance="dark">
                                    <span
                                        class="d-block">@lang("Browse your file here")</span>
                                    <input type="file" name="thumbnail"
                                           class="js-file-attach form-check-input"
                                           id="logoUploader"
                                           data-hs-file-attach-options='{
                                                                      "textTarget": "#contentImg",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                                   }'>
                                </label>
                                @error("image")
                                <span class="invalid-feedback d-block" role="alert">
                                            {{ $message }}
                                            </span>
                                @enderror
                            </div>

                            @error("images")
                            <span class="invalid-feedback d-block" role="alert">
                                            {{ $message }}
                                            </span>
                            @enderror
                        </div>

                        <!-- Body -->
                    </div>
                    <div class="card mb-3 mb-lg-5 ">
                        <!-- Header -->
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">@lang('Images')</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <div class="input-field">
                                <div class="input-images-1" ></div>
                            </div>

                            @error("images")
                            <span class="invalid-feedback d-block" role="alert">
                                            {{ $message }}
                                            </span>
                            @enderror
                        </div>

                        <!-- Body -->
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h4 class="card-header-title">@lang('Capital Back Status')</h4>
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
                                                                   type="checkbox" id="has_amount_fixed" value="1" @checked(old('has_amount_fixed',$project->amount_has_fixed) == true)>
                                                            <label class="form-check-label"
                                                                   for="capital_back"></label>
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
                                                        <h5 class="mb-0">@lang('Capital Back')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang('If you want to return of the original amount of money invested at the end of the investment period , then please turn on this button')</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="capital_back" value="0">
                                                            <input class="form-check-input" name="capital_back"
                                                                   type="checkbox" id="capital_back" value="1" @checked(old('capital_back',$project->capital_back) == 1)>
                                                            <label class="form-check-label"
                                                                   for="capital_back"></label>
                                                        </div>

                                                    </div>
                                                </div>
                                                @error("capital_back")
                                                <span class="invalid-feedback d-block" role="alert">
                                            {{ $message }}
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-group-item mb-4">
                                        <div class="d-flex">
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h5 class="mb-0">@lang('Number of return has unlimited ?')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang('number of return has unlimited then turn on this button')</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="number_of_return_has_unlimited" value="0">
                                                            <input class="form-check-input" name="number_of_return_has_unlimited"
                                                                   type="checkbox" id="number_of_return_has_unlimited" value="1" @checked(old('number_of_return_has_unlimited',$project->number_of_return_has_unlimited) == 1)>
                                                            <label class="form-check-label"
                                                                   for="capital_back"></label>
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
                                                        <h5 class="mb-0">@lang('Project duration has unlimited ?')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang('project duration has unlimited then turn on this button')</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="project_duration_has_unlimited" value="0">
                                                            <input class="form-check-input" name="project_duration_has_unlimited"
                                                                   type="checkbox" id="project_duration_is_fixed" value="1" @checked(old('project_duration_has_unlimited',$project->project_duration_has_unlimited) == 1)>
                                                            <label class="form-check-label"
                                                                   for="capital_back"></label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('project_duration_has_unlimited')
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
                                    <div >
                                        <button class="btn btn-primary mb-3" type="submit" name="status" value="1">@lang('Save & Publish')</button>
                                        <button class="btn btn-info ms-3 mb-3" type="submit" name="status" value="0">@lang('Save & Draft')</button>
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
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/image-uploader.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-add-field.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/image-uploader.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush
@push('script')
    <script>

        (function() {
            // INITIALIZATION OF FLATPICKR
            // =======================================================
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
        })();

        (function () {
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
        $(document).ready(function (){
            let preloaded = [];
            let images = @json($projectImages);
            $(images).each(function(index, element) {
                preloaded.push({id: index, src: element})
            });
            $('.input-images-1').imageUploader({
                preloaded: preloaded,
                imagesInputName: 'images',
                preloadedInputName: 'old',
                maxSize: 2 * 1024 * 1024,
                maxFiles: 10
            });
            $('.summernote').summernote({
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });

            if ($('#number_of_return_has_unlimited').is(':checked')){
                $('#number_of_return').prop('disabled', true);
            }else {
                $('#number_of_return').prop('disabled', false);
            }

            $(document).on('change','#number_of_return_has_unlimited',function (){
                if ($(this).is(':checked')) {
                    $('#number_of_return').prop('disabled', true);
                } else {
                    $('#number_of_return').prop('disabled', false);
                }
            });





            if ($('#project_duration_is_fixed').is(':checked')){
                $('#project_duration').prop('disabled', true);
                $('#project_duration_type').prop('disabled', true);
            }else {
                $('#project_duration').prop('disabled', false);
                $('#project_duration_type').prop('disabled', false);
            }

            $(document).on('change','#project_duration_is_fixed',function (){
                if ($(this).is(':checked')) {
                    $('#project_duration').prop('disabled', true);
                    $('#project_duration_type').prop('disabled', true);
                } else {
                    $('#project_duration').prop('disabled', false);
                    $('#project_duration_type').prop('disabled', false);
                }
            });




            if ( $('#has_amount_fixed').is(':checked')){
                $('#minimum_invest_field').hide();
                $('#maximum_invest_field').hide();
                $('#fixed_invest_amount').show();
            }else {
                $('#minimum_invest_field').show();
                $('#maximum_invest_field').show();
                $('#fixed_invest_amount').hide();
            }

            $(document).on('change','#has_amount_fixed',function (){
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

        })

        $(document).on('click', '.deleteInputField', function () {
            $(this).closest('.row').remove();
        });
    </script>
@endpush



@push('css')
    <style>

        .image-uploader {
            height: 15rem;
            border: .125rem dashed rgba(231,234,243,.7);
            border-radius: 10px;
            position: relative;
            overflow: auto;
        }

        .input-images-1{
            padding-top: .5rem !important;
        }

    </style>
@endpush
