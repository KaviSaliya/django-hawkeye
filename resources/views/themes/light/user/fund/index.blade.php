@extends($theme.'layouts.user')
@section('title',trans('Deposit History'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Deposit History')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Deposit History')</li>
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
                            <th scope="col">@lang('Method')</th>
                            <th scope="col">@lang('Transaction ID')</th>
                            <th scope="col">@lang('Amount')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Created time')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($funds as $key => $value)
                            <tr>
                                <td data-label="@lang('Method')">{{ __(optional($value->gateway)->name) ?? __('N/A') }}</td>
                                <td data-label="@lang('Transaction ID')">{{ __($value->trx_id) }}</td>
                                <td data-label="@lang('Requested Amount')">{{ getAmount($value->amount).' '.$value->payment_method_currency }}</td>
                                <td data-label="@lang('Status')">
                                    {!! $value->getStatus() !!}
                                </td>
                                <td data-label="@lang('Created time')"> {{ dateTime($value->created_at)}} </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                @if(count($funds??[]) == 0)
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
            {{ $funds->appends($_GET)->links($theme.'partials.user-pagination') }}
        </nav>
    </div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasSearch" aria-labelledby="offcanvasExampleLabel" aria-modal="true" role="dialog">
        <div class="offcanvas-header d-flex justify-content-between">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel me-2">@lang('Deposit Filter')</h5>
            <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fa-light fa-arrow-right"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            <form action="{{route('user.fund.index')}}" method="GET">
                <div class="row g-4">
                    <div>
                        <label for="Title" class="form-label">@lang('Transaction ID')</label>
                        <input placeholder="Transaction ID" name="trx_id"  type="text" class="form-control form-control-sm">
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


