@extends($theme.'layouts.user')
@section('title',trans('Investment Plan'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Investment Plan')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Investment Plan')</li>
            </ol>
        </nav>
    </div>

    <div class="card mt-50">
        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th scope="col">@lang('Plan')</th>
                            <th scope="col">@lang('Price')</th>
                            <th scope="col">@lang('Profit')</th>
                            <th scope="col">@lang('Return Period')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($plans as $key => $plan)
                            <tr>
                                <td data-label="@lang('Plan')">{{$plan->plan_name}}</td>
                                <td data-label="@lang('Price')">{{$plan->investAmount()}}</td>
                                <td data-label="@lang('Profit')">{{ $plan->getProfit() }}</td>
                                <td data-label="@lang('Return Period')"> {{trans('Every').' '.$plan->getReturnPeriod()}} </td>
                                <td data-label="@lang('Action')">
                                    <a href="javascript:void(0)" data-id="{{$plan->id}}"
                                       data-bs-toggle="modal"
                                       data-return="{{$plan->number_of_profit_return?$plan->number_of_profit_return.' '.trans('Times'):trans('Lifetime Earning')}}"
                                       data-maturity="{{$plan->maturity}}"
                                       data-capital="{!! $plan->getUserCapitalBack() !!}"
                                       data-bs-target="#InvestModal"
                                       class="btn-1 investNow">
                                        <i class="fa-sharp fa-light fa-sack-dollar"></i>
                                        @lang('Invest') <span></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                @if(count($plans??[]) == 0)
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
            {{ $plans->appends($_GET)->links($theme.'partials.user-pagination') }}
        </nav>
    </div>

    <div id="InvestModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalCenterTitle">@lang('Invest Now')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.investPlan')}}" method="post">
                    @csrf
                    <div class="modal-body">

                        <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold-500">@lang('Maturity')</div>
                                </div>
                                <span class="maturity"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold-500">@lang('Number of return')</div>
                                </div>
                                <span class="numberOfReturn"></span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold-500">@lang('Capital Back')</div>
                                </div>
                                <span class="capitalBack"></span>
                            </li>

                        </ul>


                        <div class="form-group">
                            <label class="modal-label" for="balance_type">@lang('Select Wallet')</label>
                            <select class="form-control" name="balance_type" data-select2-id="select2-data-1-sjyd" tabindex="-1" aria-hidden="true">
                                <option
                                    value="balance">@lang('Wallet Balance') - {{currencyPosition(auth()->guard('web')->user()->balance + 0)}}</option>
                                <option
                                    value="profit">@lang('Profit Balance') - {{currencyPosition(auth()->guard('web')->user()->profit_balance + 0)}}</option>
                                <option value="checkout">@lang('Checkout')</option>
                            </select>
                        </div>
                        <label class="modal-label" for="balance_type">@lang('Enter Amount')</label>
                        <div class="input-group">
                            <input type="text" class="form-control invest-amount" name="amount" id="amount"
                                   value="{{old('amount')}}"
                                   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" autocomplete="off"
                                   placeholder="@lang('e.g: 100')"/>
                            <span class="input-group-text show-currency bg-white">{{basicControl()->currency_symbol}}</span>
                        </div>
                            <input type="hidden" name="plan_id" class="plan-id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="delete-btn" data-bs-dismiss="modal">@lang('Close')<span></span></button>
                        <button type="submit" class="btn-2">@lang('Make Payment')<span></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('click','.investNow',function (){
            $('.plan-id').val($(this).data('id'));
            let maturity = $(this).data('maturity');
            let numberOfReturn = $(this).data('return');
            let capitalBack = $(this).data('capital');
            $('.maturity').text(`${maturity} `+'{{trans('Days')}}');
            $('.numberOfReturn').text(`${numberOfReturn}`);
            $('.capitalBack').html(capitalBack);
        })

    </script>
@endpush

