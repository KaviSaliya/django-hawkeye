@extends($theme.'layouts.user')
@section('title',trans('Payout'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Payout')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Payout')</li>
            </ol>
        </nav>
    </div>
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <!-- checkout section -->
                <div class="container px-0 px-lg-3">
                    <div class="checkout-section">
                        <div class="card p-4 padding-payout">
                            <form action="{{ route('user.payout.request') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4 g-lg-5">
                                    <div class="col-md-6 col-lg-7">
                                        <div class="row g-4">
                                            <div class="col-md-12">
                                                <div class="payment-box mb-4">
                                                    <h5 class="payment-option-title">@lang('Select Payment')</h5>
                                                    <div class="payment-option-wrapper">
                                                        <div class="payment-section">
                                                            <ul class="payment-container-list">
                                                                @forelse($payoutMethod as $method)
                                                                    <li class="item">
                                                                        <input class="form-check-input selectPayoutMethod"
                                                                               value="{{ $method->id }}" type="radio"
                                                                               name="payout_method_id"
                                                                               id="{{ $method->name }}" >
                                                                        <label class="form-check-label"
                                                                               for="{{ $method->name }}">
                                                                            <div class="image-area">
                                                                                <img
                                                                                    src="{{ getFile($method->driver,$method->logo ) }}"
                                                                                    alt="">
                                                                            </div>
                                                                            <div class="content-area">
                                                                                <h5>{{$method->name}}</h5>
                                                                                <span>{{$method->description}}</span>
                                                                            </div>
                                                                        </label>

                                                                    </li>
                                                                @empty
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-lg-5">
                                        <div class="side-bar mt-5">
                                            <div class="side-box mt-2">
                                                <div class="col-md-12 input-box mb-3">
                                                    <select class="js-example-basic-single form-select  "
                                                            name="balance_type"
                                                           >
                                                        <option value="balance">@lang('Wallet Balance') - {{currencyPosition(auth()->guard('web')->user()->balance)}}</option>
                                                        <option value="profit_balance">@lang('Profit Balance') - {{currencyPosition(auth()->guard('web')->user()->profit_balance)}}</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12 input-box mb-3">
                                                    <select class="js-example-basic-single form-select  "
                                                            name="supported_currency"
                                                            id="supported_currency">
                                                        <option value="">@lang('Select Currency')</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-12 input-box">
                                                    <div class="input-group">
                                                        <input class="form-control @error('amount') is-invalid @enderror"
                                                               name="amount"
                                                               type="text" id="amount"
                                                               placeholder="@lang('Enter Amount')" autocomplete="off"/>
                                                        <div class="invalid-feedback">
                                                            @error('amount') @lang($message) @enderror
                                                        </div>
                                                        <div class="valid-feedback"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="payoutSummary">
                                                <div class="row d-flex text-center justify-content-center">
                                                    <div class="col-md-12">
                                                        <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                                        <p>@lang('Waiting for payout preview')</p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';


        $(document).ready(function () {
            let amountField = $('#amount');
            let amountStatus = false;
            let selectedPayoutMethod = "";

            function clearMessage(fieldId) {
                $(fieldId).removeClass('is-valid')
                $(fieldId).removeClass('is-invalid')
                $(fieldId).closest('div').find(".invalid-feedback").html('');
                $(fieldId).closest('div').find(".is-valid").html('');
            }

            $(document).on('click', '.selectPayoutMethod', function () {
                let id = this.id;

                selectedPayoutMethod = $(this).val();
                supportCurrency(selectedPayoutMethod);
            });

            function supportCurrency(selectedPayoutMethod) {
                if (!selectedPayoutMethod) {
                    console.error('Selected Gateway is undefined or null.');
                    return;
                }

                $('#supported_currency').empty();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('user.payout.supported.currency') }}",
                    data: {gateway: selectedPayoutMethod},
                    type: "GET",
                    success: function (data) {

                        if (data === "") {
                            let markup = `<option value="USD">USD</option>`;
                            $('#supported_currency').append(markup);
                        }

                        let markup = '<option value="">Selected Currency</option>';
                        $('#supported_currency').append(markup);

                        $(data).each(function (index, value) {

                            let markup = `<option value="${value}">${value}</option>`;
                            $('#supported_currency').append(markup);
                        });
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }


            $(document).on('change, input', "#amount, #supported_currency, .selectPayoutMethod", function (e) {

                let amount = amountField.val();
                let selectedCurrency = $('#supported_currency').val();
                let currency_type = 1;

                if (!isNaN(amount) && amount > 0) {

                    let fraction = amount.split('.')[1];
                    let limit = currency_type == 0 ? 8 : 2;


                    if (fraction && fraction.length > limit) {
                        amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
                        amountField.val(amount);
                    }

                    checkAmount(amount, selectedCurrency, selectedPayoutMethod)


                } else {
                    clearMessage(amountField)
                    $('#payoutSummary').html(`<div class="row d-flex text-center justify-content-center">
                                                    <div class="col-md-12">
                                                        <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                                        <p>@lang('Waiting for payout preview')</p>
                                                    </div>
                                                </div>`)
                }
            });


            function checkAmount(amount, selectedCurrency, selectedPayoutMethod) {

                $.ajax({
                    method: "GET",
                    url: "{{ route('user.payout.checkAmount') }}",
                    dataType: "json",
                    data: {
                        'amount': amount,
                        'selected_currency': selectedCurrency,
                        'selected_payout_method': selectedPayoutMethod,
                    }
                }).done(function (response) {
                    console.log(response)
                    let amountField = $('#amount');
                    if (response.status) {
                        clearMessage(amountField);
                        $(amountField).addClass('is-valid');
                        $(amountField).closest('div').find(".valid-feedback").html(response.message);
                        amountStatus = true;
                        let base_currency ="{{basicControl()->base_currency}}"
                        showCharge(response, base_currency);
                    } else {
                        amountStatus = false;
                        // submitButton();
                        $('#payoutSummary').html(`<div class="row d-flex text-center justify-content-center">
                                                    <div class="col-md-12">
                                                        <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                                        <p>@lang('Waiting for payout preview')</p>
                                                    </div>
                                                </div>`);
                        clearMessage(amountField);
                        $(amountField).addClass('is-invalid');
                        $(amountField).closest('div').find(".invalid-feedback").html(response.message);
                    }


                });
            }


            function showCharge(response, currency) {

                let txnDetails =   `<div class="side-box">
                    <h5>@lang('Payout Summary')</h5>
                    <div class="showCharge">
                        <ul class="list-group">
						<li class="list-group-item d-flex justify-content-between">
							<span>{{ __('Amount In') }} ${response.currency} </span>
							<span class="text-success"> ${response.amount} ${response.currency}</span>
						</li>

						<li class="list-group-item d-flex justify-content-between">
							<span>{{ __('Charge') }}</span>
							<span class="text-danger">  ${response.charge} ${response.currency}</span>
						</li>


						<li class="list-group-item d-flex justify-content-between">
							<span>{{ __('Payout Amount') }}</span>
							<span class=""> ${response.net_payout_amount} ${response.currency}</span>
						</li>


						<li class="list-group-item d-flex justify-content-between">
							<span>{{ __('In Base Currency') }}</span>
							<span class=""> ${response.amount_in_base_currency} ${currency}</span>
						</li>
					</ul>
                    </div>
                </div>
                <button type="submit" class="cmn-btn3">@lang('Withdraw') <span></span></button>`;
                $('#payoutSummary').html(txnDetails)
            }



        });


    </script>
@endpush







