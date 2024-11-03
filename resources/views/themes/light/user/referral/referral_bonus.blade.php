@extends($theme.'layouts.user')
@section('title',trans('Referral Bonus'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Referral Bonus')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Referral Bonus')</li>
            </ol>
        </nav>
    </div>

    <div class="card mt-50">
        <div class="card-header btn-area d-flex justify-content-end">
            <button type="button" class="btn-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" aria-controls="offcanvasExample"><i class="fa-light fa-search me-1"></i>@lang('Filter') <span></span> </button>
        </div>
        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th scope="col">@lang('SL')</th>
                            <th scope="col">@lang('Bonus From')</th>
                            <th scope="col">@lang('Amount')</th>
                            <th scope="col">@lang('Remarks')</th>
                            <th scope="col">@lang('Type')</th>
                            <th scope="col">@lang('Date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($referrals as $key => $value)
                            <tr>
                                <td data-label="@lang('SL')">{{$referrals->firstItem()+$key}}</td>
                                <td data-label="@lang('Bonus From')">{{ $value->bonusBy->username}}</td>
                                <td data-label="@lang('Amount')" class="text-success">{{currencyPosition($value->amount)}}</td>
                                <td data-label="@lang('Remarks')">{{trans($value->remarks)}}</td>
                                <td data-label="@lang('Type')">{{trans(snake2Title($value->commission_type))}}</td>
                                <td data-label="@lang('Date')"> {{ dateTime($value->created_at)}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                @if(count($referrals??[]) == 0)
                    <div class="row d-flex text-center justify-content-center">
                        <div class="col-4">
                            <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                            <p>@lang('No data to show')</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <div class="pagination-section">
        <nav aria-label="...">
            {{ $referrals->appends($_GET)->links($theme.'partials.user-pagination') }}
        </nav>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSearch" aria-labelledby="offcanvasExampleLabel" aria-modal="true" role="dialog">
        <div class="offcanvas-header d-flex justify-content-between">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel me-2">@lang('Referral Bonus Filter')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{route('user.referral.bonus')}}" method="GET">
                <div class="row g-4">
                    <div>
                        <label for="Title" class="form-label">@lang('Bonus Type')</label>
                        <input placeholder="Bonus Type" name="type"  type="text" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label for="Title" class="form-label">@lang('Remark')</label>
                        <input placeholder="Remark" name="remark"  type="text" class="form-control form-control-sm">
                    </div>
                    <div>
                        <label for="CreatedAt" class="form-label"> @lang('Date Range') </label>
                        <input type="text" name="date_range" class="form-control date flatpickr-input active"  readonly="readonly" placeholder="@lang('Select Dates')">
                    </div>

                    <div class="btn-area">
                        <button type="submit" class="btn-2">@lang('Filter') <span></span> </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        flatpickr(".date",{
            mode: "range",
        });
    </script>
@endpush



