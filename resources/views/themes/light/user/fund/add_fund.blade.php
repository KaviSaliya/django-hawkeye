@extends($theme.'layouts.user')
@section('title',trans('Deposit'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Deposit')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Deposit')</li>
            </ol>
        </nav>
    </div>
    <div class="main row d-flex justify-content-center">
        <div class="col-md-10 col-sm-12 ">
            <!-- checkout section -->
            <div class="checkout-section">
                <div class="card p-4  padding">
                    <form action="{{ route('payment.request') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4 g-lg-5">
                            <div class="col-md-6 col-lg-7 col-sm-12">

                                <div class="row g-4">
                                    <div class="col-md-12">
                                        <div class="payment-box mb-4">
                                            <h5 class="payment-option-title">@lang('Select Payment')</h5>
                                            <div class="payment-option-wrapper">
                                                <div class="payment-section">
                                                    <ul class="payment-container-list">
                                                        @forelse($gateways as $method)
                                                            <li class="item">
                                                                <input class="form-check-input selectPayment"
                                                                       value="{{ $method->id }}" type="radio"
                                                                       name="gateway_id"
                                                                       id="{{ $method->name }}" >
                                                                <label class="form-check-label"
                                                                       for="{{ $method->name }}">
                                                                    <div class="image-area">
                                                                        <img
                                                                            src="{{ getFile($method->driver,$method->image ) }}"
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
                                <div class="side-bar">
                                    <div class="side-box mt-5">
                                        <div class="col-md-12 input-box mb-3 add-select-field" >
                                            <select class="js-example-basic-single form-control"
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
                                    <div id="paymentSummary">
                                        <div class="row d-flex text-center justify-content-center">
                                            <div class="col-md-12">
                                                <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                                <p>@lang('Waiting for payment preview')</p>
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
@endsection

@push('script')
    <script>
        'use strict';


        $(document).ready(function () {
            let amountField = $('#amount');
            let amountStatus = false;
            let selectedGateway = "";

            function clearMessage(fieldId) {
                $(fieldId).removeClass('is-valid')
                $(fieldId).removeClass('is-invalid')
                $(fieldId).closest('div').find(".invalid-feedback").html('');
                $(fieldId).closest('div').find(".is-valid").html('');
            }

            $(document).on('click', '.selectPayment', function () {
                let id = this.id;
                selectedGateway = $(this).val();
                supportCurrency(selectedGateway);
            });

            function supportCurrency(selectedGateway) {
                if (!selectedGateway) {
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
                    url: "{{ route('supported.currency') }}",
                    data: {gateway: selectedGateway},
                    type: "GET",
                    success: function (response) {

                        $('.add-select-field').empty();

                        if (response.data === "") {
                            let markup = `<option value="USD">USD</option>`;
                            $('#supported_currency').append(markup);
                        }

                        if (response.currencyType == 1) {
                            let select =   `
                                                <select class="js-example-basic-single form-control"
                                                        name="supported_currency"
                                                        id="supported_currency">
                                                        <option value="">`+'{{trans('Select Currency')}}'+`</option>
                                                </select>`;
                            $('.add-select-field').append(select);

                            $(response.data).each(function (index, value) {
                                let markup = `<option value="${value}">${value}</option>`;
                                $('#supported_currency').append(markup);
                            });
                        }

                        let markup2 = '<option value="">'+'{{trans('Select Crypto Currency')}}'+'</option>';
                        $('#supported_crypto_currency').append(markup2);

                        if (response.currencyType == 0){
                            let markup2 = `
                                        <select class="js-example-basic-single form-control"
                                                name="supported_crypto_currency"
                                                id="supported_crypto_currency">
                                              <option value="">`+'{{trans('Select Crypto Currency')}}'+`</option>
                                        </select>`;
                            $('.add-select-field').append(markup2);

                            $(response.data).each(function (index, value) {
                                let markupOption = `<option value="${value}">${value}</option>`;
                                $('#supported_crypto_currency').append(markupOption);
                            });
                        }
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }


            $(document).on('change, input', "#amount, #supported_currency, .selectPayment, #supported_crypto_currency", function (e) {

                let amount = amountField.val();
                let selectedCurrency = $('#supported_currency').val();
                let selectedCryptoCurrency = $('#supported_crypto_currency').val();
                console.log(selectedCryptoCurrency)
                let currency_type = 1;

                if (!isNaN(amount) && amount > 0) {

                    let fraction = amount.split('.')[1];
                    let limit = currency_type == 0 ? 8 : 2;

                    if (fraction && fraction.length > limit) {
                        amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
                        amountField.val(amount);
                    }

                    checkAmount(amount, selectedCurrency, selectedGateway, selectedCryptoCurrency)

                    if (selectedCurrency != null) {

                    }
                } else {
                    clearMessage(amountField)
                    $('#paymentSummary').html(`<div class="row d-flex text-center justify-content-center">
                                            <div class="col-md-12">
                                                <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                                <p>@lang('Waiting for payment preview')</p>
                                            </div>
                                        </div>`)
                }
            });

            function checkAmount(amount, selectedCurrency, selectGateway, selectedCryptoCurrency = null) {
                $.ajax({
                    method: "GET",
                    url: "{{ route('deposit.checkAmount') }}",
                    dataType: "json",
                    data: {
                        'amount': amount,
                        'selected_currency': selectedCurrency,
                        'select_gateway': selectGateway,
                        'selectedCryptoCurrency': selectedCryptoCurrency,
                    }
                }).done(function (response) {

                    console.log(response);
                    let amountField = $('#amount');
                    if (response.status) {
                        clearMessage(amountField);
                        $(amountField).addClass('is-valid');
                        $(amountField).closest('div').find(".valid-feedback").html(response.message);
                        amountStatus = true;
                        let base_currency = "{{basicControl()->base_currency}}"
                        showCharge(response, base_currency);
                    } else {
                        amountStatus = false;
                        $('#paymentSummary').html(`<div class="row d-flex text-center justify-content-center">
                                            <div class="col-md-12">
                                                <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                                                <p>@lang('Waiting for payment preview')</p>
                                            </div>
                                        </div>`);
                        clearMessage(amountField);
                        $(amountField).addClass('is-invalid');
                        $(amountField).closest('div').find(".invalid-feedback").html(response.message);
                    }


                });
            }


            function showCharge(response, currency) {

                let txnDetails =  ` <div class="side-box">
                    <h5>@lang('Deposit Summary')</h5>
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
                            <span>{{ __('Payable Amount') }}</span>
                            <span class=""> ${response.payable_amount} ${response.currency}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('In Base Currency') }}</span>
                            <span class=""> ${response.amount_in_base_currency} ${currency}</span>
                        </li>

                    </ul>
                    </div>
                </div>

                <button type="submit" class="cmn-btn3 ms-2">@lang('Make Payment') <span></span> </button>`
                   ;
                $('#paymentSummary').html(txnDetails)
            }

        });


    </script>
@endpush






