@extends($theme.'layouts.user')
@section('title')
	{{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">{{ __('Pay with ').__(optional($deposit->gateway)->name) }}</li>
            </ol>
        </nav>
    </div>
<div class="container-fluid mt-lg-5">
    <div class="main row">
        <div class="col-12">
            <div class="row g-4 justify-content-center">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="card p-0 mt-30">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-3">
                                    <img src="{{getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image)}}"
                                         class="card-img-top gateway-img">
                                </div>
                                <div class="col-md-6">
                                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                                    <form action="{{$data->url}}" method="{{$data->method}}">
                                        <script src="{{$data->checkout_js}}"
                                                @foreach($data->val as $key=>$value)
                                                    data-{{$key}}="{{$value}}"
                                            @endforeach >
                                        </script>
                                        <input type="hidden" custom="{{$data->custom}}" name="hidden">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('script')
	<script>
		$(document).ready(function () {
			$('input[type="submit"]').addClass("btn-2 border-0");
		})
	</script>
@endpush
