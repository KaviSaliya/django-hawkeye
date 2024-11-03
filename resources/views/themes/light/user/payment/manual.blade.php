@extends($theme.'layouts.user')
@section('title')
    {{ 'Pay with '.optional($deposit->gateway)->name ?? '' }}
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
    <section class="feature-section">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="title text-center">{{trans('Please follow the instruction below')}}</h3>
                            <p class="text-center mt-2 ">{{trans('You have requested to Payment')}} <b
                                    class="text--base">{{getAmount($deposit->amount)}} {{$deposit->payment_method_currency}}
                                    {{config('basic.base_currency')}}</b> , {{trans('Please Pay')}}
                                <b class="text--base">{{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</b> {{trans('for successful payment')}}
                            </p>

                            <p class=" mt-2 ">
                                <?php echo optional($deposit->gateway)->note; ?>
                            </p>

                            <form action="{{route('addFund.fromSubmit',$deposit->trx_id)}}" method="post"
                                  enctype="multipart/form-data"
                                  class="form-row  preview-form">
                                @csrf
                                @if(optional($deposit->gateway)->parameters)
                                    @foreach($deposit->gateway->parameters as $k => $v)
                                        @if($v->type == "text")
                                            <div class="col-md-12 mt-2">
                                                <div class="">
                                                    <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                            <span class="text-danger">*</span>
                                                        @endif </label>
                                                    <input type="text" name="{{$k}}"
                                                           class="form-control bg-transparent"
                                                           @if($v->validation == "required") required @endif>
                                                    @if ($errors->has($k))
                                                        <span
                                                            class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($v->type == "number")
                                            <div class="col-md-12 mt-2">
                                                <div class="  ">
                                                    <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                            <span class="text--danger">*</span>
                                                        @endif </label>
                                                    <input type="text" name="{{$k}}"
                                                           class="form-control bg-transparent"
                                                           @if($v->validation == "required") required @endif>
                                                    @if ($errors->has($k))
                                                        <span
                                                            class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($v->type == "textarea")
                                            <div class="col-md-12 mt-2">
                                                <div class="">
                                                    <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                            <span class="text--danger">*</span>
                                                        @endif </label>
                                                    <textarea name="{{$k}}" class="form-control bg-transparent"
                                                              rows="3"
                                                              @if($v->validation == "required") required @endif></textarea>
                                                    @if ($errors->has($k))
                                                        <span
                                                            class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @elseif($v->type == "file")
                                            <div class="col-md-3 ">
                                                <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                        <span class="text--danger">*</span>
                                                    @endif </label>
                                                <div class="image-input">
                                                    <label for="image-upload" id="image-label">
                                                        <i class="fa-regular fa-upload"></i>
                                                    </label>

                                                    <input type="file" name="{{$k}}" id="image" accept="image/*"
                                                           @if($v->validation == "required") required @endif>
                                                    <img class="w-100 preview-image" id="image_preview_container"
                                                         src="{{getFile(config('filelocation.default'))}}"
                                                         alt="@lang('Upload Image')">
                                                </div>
                                                @error($k)
                                                <span class="text-danger">@lang($message)</span>
                                                @enderror
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="col-md-12 ">
                                    <div class=" form-group">
                                        <button type="submit" class="btn-2  mt-3">
                                            @lang('Confirm Now')<span></span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        'use strict'
        $(document).on("change",'#image',function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#image_preview_container').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
    </script>
@endpush

@push('style')
    <style>
        .feature-box .form-group {
            margin: 20px 0;
        }

        .image-input {
            position: relative;
            width: 70%;
            padding: 20px;
            border: 2px dashed #ddd;
            cursor: pointer;
        }

        .image-input input#image {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .image-input #image-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .image-input #image-label i {
            font-size: 24px;
            color: var(--primary-color);
            opacity: 0.5;
        }
    </style>
@endpush
