@extends($theme.'layouts.user')
@section('title',trans('Project Investment'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Project Investment')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Project Investment')</li>
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
                            <th scope="col">@lang('Project')</th>
                            <th scope="col">@lang('No. of Unit')</th>
                            <th scope="col">@lang('Price') <i class="fa-sharp fa-thin fa-circle-info ms-1"
                                                              data-bs-toggle="tooltip" data-bs-placement="top"
                                                              aria-label="Per Unit"
                                                              data-bs-original-title="Per Unit"></i></th></th>
                            <th scope="col">@lang('Return Period')</th>
                            <th scope="col">@lang('Received Amount')</th>
                            <th scope="col">@lang('Upcoming Payment')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($projectInvestment as $key => $value)
                            <tr>
                                <td data-label="@lang('SL')">{{$projectInvestment->firstItem()+$key}}</td>
                                <td data-label="@lang('Project')">{!!  $value->getUserProject() !!}</td>
                                <td data-label="@lang('No. of Unit')">
                                    <span class="badge bg-secondary rounded-pill  badge-unit">{{$value->unit}}</span>
                                </td>
                                <td data-label="@lang('Price')">{{currencyPosition($value->per_unit_price)}}</td>
                                <td data-label="@lang('Return Period')">{{trans('Every').' '. $value->return_period.' '.trans($value->return_period_type)}}</td>
                                <td data-label="@lang('Received Amount')">{!! $value->receivedAmount() !!}</td>
                                <td data-label="@lang('Upcoming Payment')">{!! $value->userNextPayment() !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                @if(count($projectInvestment??[]) == 0)
                    <div class="row d-flex text-center justify-content-center">
                        <div class="col-4">
                            <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image"
                                 class="no-data-image" alt="" srcset="">
                            <p>@lang('No data to show')</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <div class="pagination-section">
        <nav aria-label="...">
            {{ $projectInvestment->appends($_GET)->links($theme.'partials.user-pagination') }}
        </nav>
    </div>


    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSearch" aria-labelledby="offcanvasExampleLabel" aria-modal="true" role="dialog">
        <div class="offcanvas-header d-flex justify-content-between">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel me-2">@lang('Project Investment Filter')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{route('user.project.investment')}}" method="GET">
                <div class="row g-4">
                    <div>
                        <label for="Title" class="form-label">@lang('Project Name')</label>
                        <input placeholder="Project Name" name="name"  type="text" class="form-control form-control-sm">
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
    @php
    $basic = App\Models\BasicControl::first();
    $timeZone = $basic->time_zone;
@endphp

@endsection

@push('script')
    <script>
        $(document).ready(function (){
            const serverTimeZone = '{{ $timeZone }}';

            function updateTime() {
                $('.next-payment').each(function() {
                    const nextPaymentTime = new Date($(this).data('payment'));

                    if (nextPaymentTime){
                        const now = new Date();

                        // Convert current time to server timezone
                        const serverTime = new Date(now.toLocaleString("en-US", {timeZone: serverTimeZone}));

                        let timeDifference = nextPaymentTime - serverTime;

                        if (timeDifference > 0) {
                            const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                            timeDifference -= days * 1000 * 60 * 60 * 24;
                            const hours = Math.floor(timeDifference / (1000 * 60 * 60));
                            timeDifference -= hours * 1000 * 60 * 60;
                            const minutes = Math.floor(timeDifference / (1000 * 60));
                            timeDifference -= minutes * 1000 * 60;
                            const seconds = Math.floor(timeDifference / 1000);

                            $(this).text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                        } else {
                            $(this).html(`<span class="text-danger">{{trans('Time has passed')}}</span>`)
                        }
                    }
                });
            }

            // Initial call to set up the time
            updateTime();
            // Update every second
            setInterval(updateTime, 1000);
        })
    </script>
@endpush


@push('script')
    <script>
        'use strict';
        flatpickr(".date",{
            mode: "range",
        });
    </script>
@endpush

