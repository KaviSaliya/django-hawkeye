@extends($theme.'layouts.user')
@section('title',trans('Profile'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Profile')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Profile')</li>
            </ol>
        </nav>
    </div>
    <div class="section dashboard">
        <div class="row">
            <div id="skltbsResponsive" class="skltbs fade-scale has-animation account-settings-navbar " aria-live="polite">
                <ul role="tablist" class="skltbs-tab-group d-flex nav">
                    <li  class="skltbs-tab-item nav-item">
                        <button type="button" class="skltbs-tab nav-link {{$errors->all() || session('kycForm')?$errors->has('profile') || $errors->has('profileImage') ?'active':'':'active'}}"   aria-selected="true" data-target="skltbsResponsive1" data-skeletabs-index="0"><i class="fa-light fa-user"></i>@lang('Profile')</button>
                    </li>
                    <li  class="skltbs-tab-item nav-item">
                        <button type="button"  class="skltbs-tab nav-link {{$errors->has('password')? 'active':''}}"    aria-selected="false" data-target="skltbsResponsive2" data-skeletabs-index="1"><i class="fa-light fa-lock"></i>@lang('Password')</button>
                    </li>
                    @foreach($allKyc as $key => $value)
                        <li  class="skltbs-tab-item nav-item ">
                            <button type="button"  class="skltbs-tab nav-link {{$errors->has($value->name) || session('kycForm') ==$value->name? 'active':''}} {{session($value->name)?'active':''}}"
                                    data-target="skltbsResponsive{{ 2 + $loop->index + 1}}">
                                <i class="fa-light fa-id-card"></i>
                                @lang(ucfirst($value->name))
                            </button>
                        </li>
                    @endforeach

                </ul>
                <div class="skltbs-panel-group mt-30" >
                    <div role="tabpanel" id="skltbsResponsive1" class="skltbs-panel {{$errors->all() || session('kycForm')?$errors->has('profile')|| $errors->has('profileImage')?'d-block':'d-none':'d-block'}}"  aria-hidden="false" aria-labelledby="skltbsResponsive1Tab"  >
                        <form method="post" action="{{ route('user.profile.update')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="account-settings-profile-section">
                                <div class="card overflow-hidden">
                                    <div class="card-header">
                                        <h5 class="card-title">@lang('Profile Details')</h5>
                                        <div class="profile-details-section">
                                            <div class="d-flex gap-3 align-items-center">
                                                <div class="image-area">
                                                    <img src="{{getFile($user->image_driver,$user->image)}}" id="profile" alt="" class="img-profile-view">
                                                </div>
                                                <div class="btn-area">
                                                    <div class="btn-area-inner d-flex">
                                                        <div class="cmn-file-input me-2">
                                                            <label for="formFile" class="form-label">@lang('Upload Photo')</label>
                                                            <input class="form-control file-upload-input" name="profile_picture" type="file" id="formFile">
                                                        </div>
                                                    </div>
                                                    <small>@lang('Allowed JPG or PNG. Max size of 3 MB')</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body pt-0">

                                        <div class="profile-form-section">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="firstname" class="form-label">@lang('Firstname')</label>
                                                    <input type="text" name="first_name" placeholder="e.g : alex"  value="{{old('first_name',$user->firstname)}}" class="form-control" id="firstname" required >
                                                    @error('first_name')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="lastname" class="form-label">@lang('Lastname')</label>
                                                    <input type="text" name="last_name" placeholder="e.g : tom" value="{{old('last_name',$user->lastname)}}" class="form-control" id="lastname" required>
                                                    @error('last_name')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="e-mail" class="form-label">@lang('Username')</label>
                                                    <input type="text" name="username" placeholder="alextom" value="{{old('username',$user->username)}}" class="form-control" id="e-mail" required>
                                                    @error('username')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="organization" class="form-label">@lang('Email Address')</label>
                                                    <input type="text" value="{{old('email',$user->email)}}" name="email" class="form-control" placeholder="e.g : alextom@example.com" id="organization" required>
                                                    @error('email')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="organization" class="form-label">@lang('Phone Number')</label>
                                                   <div>
                                                       <input type="hidden" name="phone_code" id="phoneCode" >
                                                       <input type="hidden" name="country_code" id="countryCode" >
                                                       <input type="hidden" name="country" id="countryName" >
                                                       <input type="tel" id="telephone" name="phone" value="{{old('phone',$user->phone)}}" class="form-control" placeholder="e.g : 1976547587" required>
                                                   </div>
                                                    @error('phone')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="firstname" class="form-label">@lang('Language')</label>
                                                    <select class="cmn-select2" name="language" data-select2-id="select2-data-1-sjyd" tabindex="-1" aria-hidden="true" required>
                                                       @foreach($languages as $language)
                                                           <option value="{{$language->id}}" @selected($user->language_id ==  $language->id)>{{$language->name}}</option>
                                                       @endforeach
                                                    </select>
                                                    @error('language')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="address" class="form-label">@lang('Address')</label>
                                                    <textarea name="address" class="form-control" id="address" placeholder="e.g : crystalridge ">{{old('address',$user->address_one)}}</textarea>
                                                    @error('address')
                                                    <div class="text-danger">{{$message}}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="btn-area d-flex g-3">
                                                <button type="submit" class="btn-2">@lang('save changes') <span></span></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div  id="skltbsResponsive2" class="skltbs-panel {{$errors->has('password')?'d-block':'d-none'}}" tabindex="-1" aria-hidden="true" aria-labelledby="skltbsResponsive2Tab" data-skeletabs-index="1" >
                            <form action="{{ route('user.updatePassword') }}" method="post">
                            @csrf
                            <div class="account-settings-profile-section">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">@lang('Update Password')</h5>
                                        <div class="card-body pt-0">
                                            <div class="profile-form-section">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label for="currentPassword" class="col-form-label">@lang('Current password')</label>
                                                        <input type="password" name="current_password" value="" placeholder="Enter your current password" class="form-control" autocomplete="off" required>
                                                        @error('current_password')
                                                        <div class="text-danger">{{$message}}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="password" class="col-form-label">@lang('Password')</label>
                                                        <input type="password" name="password" value="" placeholder="Enter new password" class="form-control" autocomplete="off" required>
                                                        @error('password')
                                                        <div class="text-danger">{{$message}}</div>
                                                        @enderror

                                                    </div>
                                                    <div class="col-md-12">
                                                        <label for="password_confirmation" class="col-form-label">@lang('Repeat password')</label>
                                                        <input type="password" name="password_confirmation" value="" class="form-control form-control-sm" placeholder="Repeat password" autocomplete="off" required>
                                                    </div>
                                                    <div class="btn-area d-flex g-3">
                                                        <button type="submit" class="btn-2">@lang('Change Password') <span></span> </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    @forelse($allKyc as $key => $item)
                        @php($check = checkUserKyc($item->id))
                        <div  id="skltbsResponsive{{ 2 + $loop->index + 1 }}" class="skltbs-panel {{$errors->has($item->name) || session('kycForm') ==$item->name ?'d-block':'d-none'}}" tabindex="-1" aria-hidden="true" aria-labelledby="skltbsResponsive2Tab" data-skeletabs-index="1" >
                            @if($check == 'pending')
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h4 class="card-header-title">@lang('KYC Information')</h4>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <div class="img">
                                            <img src="{{asset($themeTrue.'images/pending.jpg')}}" id="verifiedImg" alt="" srcset="">
                                        </div>
                                        <div class="text">
                                            <span class="KycverifiedMessage text-center text-primary">@lang('Your KYC is Pending')</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @elseif($check == true)
                                <div class="card">
                                    <div class="card-header bg-white">
                                        <h4 class="card-header-title">@lang('KYC Information')</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="">
                                            <div class="img">
                                                <img src="{{asset($themeTrue.'images/verified.jpg')}}" id="verifiedImg" alt="" srcset="">
                                            </div>
                                            <div class="text">
                                                <span class="KycverifiedMessage text-center text-success">@lang('Your KYC is Verified')</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                            <form action="{{ route('user.kyc.verification.submit') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{$item->name}}</h5>
                                        <div class="card-body pt-3">
                                            <div class="profile-form-section">
                                                <div class="row g-3">
                                                    <input type="hidden" name="type" value="{{ $item->id }}">
                                                    @foreach($item->input_form as $k => $value)
                                                        @if($value->type == "text")
                                                            <div class="col-md-12">
                                                                <label for="" class="col-form-label">{{ $value->field_label }}</label>
                                                                <input type="text" name="{{ $value->field_name }}" value="" placeholder="{{ $value->field_label }}" class="form-control" required>
                                                                @if($errors->has($value->field_name) && $errors->has($item->name))
                                                                    <div class="text-danger">@lang($errors->first($value->field_name)) </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if($value->type == "number")
                                                            <div class="col-md-12">
                                                                <label for="" class="col-form-label">{{ $value->field_label }}</label>
                                                                <input type="number" name="{{ $value->field_name }}" value="" placeholder="{{ $value->field_label }}" class="form-control" required>
                                                                @if($errors->has($value->field_name) && $errors->has($item->name))
                                                                    <div class="text-danger">@lang($errors->first($value->field_name)) </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                        @if($value->type == "date")
                                                            <div class="col-md-12">
                                                                <label for="" class="col-form-label">{{ $value->field_label }}</label>
                                                                <input type="date" name="{{ $value->field_name }}" value="" placeholder="{{ $value->field_label }}" class="form-control" required>
                                                                @if($errors->has($value->field_name) && $errors->has($item->name))
                                                                    <div class="text-danger">@lang($errors->first($value->field_name)) </div>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        @if($value->type == "textarea")
                                                            <div class="col-md-12">
                                                                <label for="" class="form-label">{{ $value->field_label }}</label>
                                                                <textarea name="{{ $value->field_name }}" class="form-control"  cols="30" rows="10" ></textarea>
                                                                @if($errors->has($value->field_name) && $errors->has($item->name))
                                                                    <div class="text-danger">@lang($errors->first($value->field_name)) </div>
                                                                @endif
                                                            </div>
                                                        @endif

                                                        @if($value->type == "file")
                                                                <div class="col-md-12">
                                                                    <label for="" class="col-form-label">{{ $value->field_label }}</label>
                                                                    <input type="file" name="{{ $value->field_name }}"  class="form-control ">
                                                                    @if($errors->has($value->field_name) && $errors->has($item->name))
                                                                        <div class="text-danger">@lang($errors->first($value->field_name)) </div>
                                                                    @endif
                                                                </div>
                                                        @endif
                                                    @endforeach
                                                    <div class="input-box col-12">
                                                        <button type="submit" class="btn-2">@lang('submit') <span></span> </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            @endif
                        </div>
                    @empty
                    @endforelse


                </div>
            </div>



        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function (){
            $('#formFile').on('change', function(event) {
                let imageFile = event.target.files[0];
                let url = '{{route('user.profile.update.image')}}';
                $('#profile').attr('src', URL.createObjectURL(event.target.files[0]));
                let formData = new FormData();
                formData.append('image', imageFile);
                axios.post(url,formData,{
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                })
                    .then(function (res) {
                        if(res.data.err){
                            Notiflix.Notify.failure(res.data.err);
                        }else {
                            Notiflix.Notify.success(res.data);
                        }

                    })
                    .catch(function (error) {

                    });
            });
        })
        $(document).on('click','.reset',function (){
            $('#profile').attr('src', '{{asset('assets/admin/img/default.png')}}');
        })


        $(document).ready(function (){

            // International Telephone Input start
            const input = document.querySelector("#telephone");
            const iti = window.intlTelInput(input, {
                initialCountry: "bd",
                separateDialCode: true,
            });
            input.addEventListener("countrychange", updateCountryInfo);
            updateCountryInfo();
            function updateCountryInfo() {
                const selectedCountryData = iti.getSelectedCountryData();
                const phoneCode = '+' + selectedCountryData.dialCode;
                const countryCode = selectedCountryData.iso2;
                const countryName = selectedCountryData.name;
                $('#phoneCode').val(phoneCode);
                $('#countryCode').val(countryCode);
                $('#countryName').val(countryName);
            }

            const initialPhone = "{{$user->phone??null}}";
            const initialPhoneCode = "{{$user->phone_code??null}}";
            const initialCountryCode = "{{$user->country_code??null}}";
            const initialCountry = "{{$user->country??null}}";
            if (initialPhoneCode) {
                iti.setNumber(initialPhoneCode);
            }
            if (initialCountryCode) {
                iti.setNumber(initialCountryCode);
            }
            if (initialCountry) {
                iti.setNumber(initialCountry);
            }
            if (initialPhone) {
                iti.setNumber(initialPhone);
            }
        })

    </script>
@endpush

@push('style')
    <style>
        #verifiedImg{
            height: 200px;
            width: 200px;
            display: block;
            margin: auto;
        }
        .KycverifiedMessage{
            font-size: 20px;
            display: block;
            margin: auto;
        }
    </style>
@endpush


