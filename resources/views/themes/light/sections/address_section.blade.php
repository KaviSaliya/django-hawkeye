

<!-- address details -->
<section class="address-details">
    <div class="container">
        <div class="address-details-container">
            <div class="address-details-shape-1">
                <img src="{{asset($themeTrue.'images/shape/contact-left-earth.png')}}" alt="shape">
            </div>
            <div class="address-details-shape-2">
                <img src="{{asset($themeTrue.'images/shape/contact-line.png')}}" alt="shape">
            </div>
            <div class="row">
                <div class="col-lg-5 offset-lg-1">
                    <div class="address-details-image">
                        <img src="{{isset($address_section['single']['media']->address_page_image)?getFile($address_section['single']['media']->address_page_image->driver,$address_section['single']['media']->address_page_image->path):getFile('local','image')}}" alt="image">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="address-details-right-container">
                        <div class="inner">
                            <h5>{!! $address_section['single']['heading']??'' !!}</h5>
                            <p>{!! $address_section['single']['short_text']??'' !!}</p>
                            <h6><i class="fa-regular fa-location-dot"></i> {!! $address_section['single']['address']??'' !!}</h6>
                            <h6><i class="fa-light fa-envelope"></i> @lang('Email'): <a href="mailto:{!! $address_section['single']['email']??'' !!}">{!! $address_section['single']['email']??'' !!} </a></h6>
                            <h6><i class="fa-light fa-phone"></i> @lang('Call Us') : <a href="tel:{!! $address_section['single']['media']->phone??'' !!}"> {!! $address_section['single']['media']->phone??'' !!}</a></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="map">
            <iframe src="{{$address_section['single']['media']->location_url??''}}" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>
<!-- address details -->
