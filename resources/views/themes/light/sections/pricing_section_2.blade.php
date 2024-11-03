
<!-- pricing 2 -->
<section class="pricing-two">
    <div class="dot-left">
        <img src="{{asset($themeTrue.'images/shape/dot-left.png')}}" alt="shape">
    </div>
    <div class="line-right">
        <img src="{{asset($themeTrue.'images/shape/line-right.png')}}" alt="shape">
    </div>
    <div class="container">
        <div class="common-title-container">
            <div class="common-title">
                <h3>{!! styleSentence2($pricing_section_2['single']['heading']??null,3) !!}</h3>
                <p>{!! $pricing_section_2['single']['short_text'] !!}</p>
            </div>
        </div>
        <div class="row">
            @forelse($plans as $plan)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="pricing-two-single">
                        <div class="icon">
                            <img src="{{$plan->getImage()}}" alt="icon">
                        </div>
                        <h4>{!! $plan->plan_name !!}</h4>
                        <div class="pricing-list">
                            <ul>
                                {!! $plan->getDescription() !!}
                            </ul>
                        </div>
                        <div class="pricing-two-info">
                            <h3>{{$plan->investAmount()}}</h3>
                            <h6> / ( {{$plan->getPlanPeriod()}}) </h6>
                        </div>
                        <div class="pricing-button">
                            <a href="javascript:void(0)" data-id="{{$plan->id}}" data-plan="{{$plan->plan_name}}"
                               data-invest="{{$plan->investAmount()}}" data-profit="{{$plan->getProfit()}}"
                               data-period="{{$plan->getReturnPeriod()}}" data-return_number="{{$plan->number_of_profit_return??'Lifetime Earning'}}" class="btn-1 myBtn">@lang('Invest Now') <i
                                    class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></a>
                        </div>
                    </div>
                </div>
            @empty
                <p>@lang('No data found')</p>
            @endforelse


        </div>
    </div>
</section>
<!-- pricing 2 -->


<!-- The Modal -->
<div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>@lang('Invest Now')</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body plan-modal">
                <h4 id="PlanName" class="text-center"></h4>
                <p class="price-range text-center mb-1"></p>
                <p class="profit-details text-center mb-1"></p>
                <p class="profit-validity text-center mb-2"></p>
                <p class="number_of_return text-center mb-2"></p>
                <form action="{{route('user.investPlan')}}" method="post">
                    @csrf
                    <div class="row g-3 mt-2">
                        <div class="col-12">
                            <select class="nice-select" aria-label="Default select example" name="balance_type">
                                @auth
                                    <option
                                        value="balance">@lang('Wallet Balance -') {{currencyPosition(auth()->guard('web')->user()->balance)}}</option>
                                    <option
                                        value="profit">@lang('Profit Balance -') {{currencyPosition(auth()->guard('web')->user()->profit_balance)}}</option>
                                @endauth
                                <option value="checkout">@lang('Checkout')</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="input-group">
                                <input type="text" class="form-control invest-amount" name="amount" id="amount"
                                       value="{{old('amount')}}"
                                       onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" autocomplete="off"
                                       placeholder="@lang('Enter amount')"/>
                                <span class="input-group-text show-currency bg-white">{{basicControl()->currency_symbol}}</span>
                            </div>
                        </div>
                        <input type="hidden" name="plan_id" class="plan-id">
                        <button type="submit" class="btn-1 d-flex justify-content-center align-items-center text-center">@lang('Make Payment') <span></span></button>
                    </div>
                </form>

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>

</div>
<!-- pricing 2 -->
