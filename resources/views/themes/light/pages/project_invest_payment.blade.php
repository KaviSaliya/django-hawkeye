@extends($theme.'layouts.app')
@section('title',trans('Payment'))
@section('content')
    <section class="payment">
        <div class="container">
            <form action="{{route('user.invest.request')}}" method="post">
                @csrf
                <h4 class="payment-title">@lang('Select Payment')</h4>
                <div class="row">
                    <div class="col-7">
                        <div class="payment-box">
                            <ul class="payment-list">
                                @foreach($gateways as $method)
                                    <li class="item">
                                        <label class="form-check-label" for="{{ $method->name }}">
                                            <input class="form-check-input selectPayment"
                                                   value="{{ $method->id }}" type="radio"
                                                   name="gateway_id"
                                                   id="{{ $method->name }}" >
                                            <span class="payment-list-content">
                                                <span class="payment-list-image">
                                                    <img src="{{ getFile($method->driver,$method->image ) }}" alt="image">
                                                </span>
                                                <span class="payment-list-info">
                                                    <span class="payment-list-title">{{$method->name}}</span>
                                                    <span class="payment-list-text">{{$method->description}}</span>
                                                </span>
                                            </span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="payment-side-bar">
                            <div class="payment-side-box" id="GatewayForm">
                                <label>@lang('Select Currency')</label>
                                <select class="selectpicker nice-select" name="supported_currency" id="supported_currency">

                                </select>

                                <span class="d-block text-danger errorMessage"></span>
                            </div>
                            <div class="payment-side-box payment-summary">
                                <h5>@lang('Payment Summery')</h5>
                                <div class="payment-summary-list">
                                    <ul class="showCharge">

                                    </ul>
                                </div>
                            </div>
                            <button type="submit" class="btn-1">@lang('Make Payment') <span></span></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- payment -->

@endsection


@push('script')
    <script>

        var gateway_id = null;
        $(document).on('click','.selectPayment',function (){
            let id  = $(this).val();
            gateway_id = id;
            supportCurrency(id)
        })

        function supportCurrency(selectedGateway){
            if (!selectedGateway) {
                console.error('Selected Gateway is undefined or null.');
                return;
            }
            $.ajax({
                url : "{{ route('supported.currency') }}",
                data: {gateway: selectedGateway},
                type: "GET",
                success : function (response){
                    $('#supported_currency').empty();
                    if (response.data === "") {
                        $('#supported_currency').attr('name', 'supported_currency');
                        let markup = `<option value="USD">USD</option>`;
                        $('#supported_currency').append(markup);
                    }
                    if (response.currencyType == 1) {
                        $('#supported_currency').attr('name', 'supported_currency');
                        let markup = '<option value="">Selected Currency</option>';
                        $('#supported_currency').append(markup);
                        $(response.data).each(function (index, value) {
                            let markup = `<option value="${value}">${value}</option>`;
                            $('#supported_currency').append(markup);
                        });
                    }
                    if (response.currencyType == 0){
                        $('#supported_currency').attr('name', 'supported_crypto_currency');
                        let markup2 = '<option value="">Select Crypto Currency</option>';
                        $('#supported_currency').append(markup2);
                        $(response.data).each(function (index, value) {
                            let markupOption = `<option value="${value}">${value}</option>`;
                            $('#supported_currency').append(markupOption);
                        });
                    }
                    $('.nice-select').niceSelect('update');

                },
                error: function (error) {
                    console.error('AJAX Error:', error);
                }
            })
        }

        $(document).on('change','#supported_currency',function (){

            checkAmount( $(this).val())

        })

        function checkAmount(currency){
            let amount = '{{$amount}}';
            $.ajax({
                method : 'GET',
                url: "{{ route('payment.checkAmount') }}",
                dataType: "json",
                data: {
                    'amount': amount,
                    'selected_currency': currency,
                    'select_gateway': gateway_id,
                    'selectedCryptoCurrency': '',
                },
                success : function (response){
                    $('.errorMessage').text('')
                    if (response.status) {
                        let base_currency = "{{basicControl()->base_currency}}"
                        showCharge(response, base_currency);
                    }else {
                        $('.errorMessage').text(response.message)

                    }
                }
            })
        }

        function showCharge(response, currency){
            let txnDetails = `
						<li>
							{{ __('Amount In') }} ${response.currency}
							<span> ${response.amount} ${response.currency}</span>
						</li>

						<li >
							{{ __('Charge') }}
            <span>  ${response.charge} ${response.currency}</span>
						</li>


						<li >
							{{ __('Payable Amount') }}
            <span> ${response.payable_amount} ${response.currency}</span>
						</li>

						<li >
							<span>{{ __('Exchange Rate') }}</span>
							<span > 1 ${currency} <i class="fa-light fa-arrow-right-arrow-left fa-sm"></i>  ${response.conversion_rate} ${response.currency}</span>
						</li>


						<li >
							<span>{{ __('Payable Amount') }} <sub>In Base Currency</sub></span>
							<span> ${response.amount_in_base_currency} ${currency}</span>
						</li>

					</ul>`;
            $('.showCharge').html(txnDetails)
        }


    </script>
@endpush
