<!-- any question -->
<section class="contact-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="any-question-right-container">
                    <h2>@lang('Contact With team')</h2>
                    <div class="leave-comments-form">
                        <form action="{{route('sent.contact.info')}}" method="post">
                            @csrf
                            <div class="form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="leave-comments-input">
                                            <input type="text" name="name" class="cmn-input" value="{{old('name')}}" placeholder="@lang('Your Name')" required>
                                            <i class="fa-light fa-user"></i>
                                            @error('name')
                                            <span class="d-block text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <div class="leave-comments-input">
                                            <input type="number" name="phone" class="cmn-input" value="{{old('phone')}}" placeholder="@lang('Phone Number')" required>
                                            @error('phone')
                                            <span class="d-block text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="leave-comments-input">
                                            <input type="email" name="email" class="cmn-input" value="{{old('email')}}" placeholder="@lang('Email Address')" required>
                                            <i class="fa-light fa-envelope"></i>
                                            @error('email')
                                            <span class="d-block text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                        <div class="leave-comments-input">
                                            <input type="text" name="invest_type" class="cmn-input" value="{{old('invest_type')}}" placeholder="@lang('Invest type')">
                                            @error('invest_type')
                                            <span class="d-block text-danger">{{$message}}</span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="col-lg-12 ">
                                        <textarea name="message" class="cmn-input" placeholder="@lang('Write your message')" required>{{old('message')}}</textarea>

                                        @error('message')
                                        <span class="d-block text-danger">{{$message}}</span>
                                        @enderror
                                        <button type="submit" class="btn-1 mt-2"> @lang('Submit Now')<i class="fa-sharp fa-solid fa-arrow-right"></i> <span></span></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="any-question-left-image">
                    <img src="{{isset($contact_section['single']['media']->contact_image)?getFile($contact_section['single']['media']->contact_image->driver,$contact_section['single']['media']->contact_image->path):getFile('local','image')}}" alt="image">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- any question -->
