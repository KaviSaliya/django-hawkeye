<!--Start of Google analytic Script-->
@if(basicControl()->analytic_status)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{basicControl()->measurement_id}}"></script>
    <script>
        "use strict";
        var MEASUREMENT_ID = "{{ basicControl()->measurement_id }}";
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', MEASUREMENT_ID);
    </script>
@endif
<!--End of Google analytic Script-->


<!--Start of Tawk.to Script-->
@if(basicControl()->tawk_status)
    <script type="text/javascript">
        // $(document).ready(function () {
        var Tawk_SRC = 'https://embed.tawk.to/' + "{{ trim(basicControl()->tawk_id) }}";
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = Tawk_SRC;
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
        // });
    </script>
@endif


<!--start of Facebook Messenger Script-->
@if(basicControl()->fb_messenger_status)
    <div id="fb-root"></div>
    <script>
        "use strict";
        var fb_app_id = "{{ basicControl()->fb_app_id }}";
        window.fbAsyncInit = function () {
            FB.init({
                appId: fb_app_id,
                autoLogAppEvents: true,
                xfbml: true,
                version: 'v10.0'
            });
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    <div class="fb-customerchat" page_id="{{ basicControl()->fb_page_id }}"></div>
@endif
<!--End of Facebook Messenger Script-->



@if (basicControl()->cookie_status)
    <div class="cookies-allert" id="cookiesAlert">
        <img src="{{getFile(basicControl()->cookie_driver,basicControl()->cookie_image)}}" class=""
             alt="{{basicControl()->site_title??'FarmTrader'}} cookies">
        <h4 class="mt-2 text-center">{{basicControl()->cookie_title}}</h4>
        <span class="d-block mt-2 text-center">{{basicControl()->cookie_short_text}} <a
                href="{{basicControl()->cookie_button_url}}"
                class="seemoreButton">{{basicControl()->cookie_button_name}}</a></span>
        <a href="javascript:void(0);" class="mt-3 cookieButton btn-1 justify-content-center" type="button"
           onclick="acceptCookiePolicy()">@lang('Accept')<span></span></a>
        <a href="javascript:void(0);" class="mt-2 cookieClose text-center" type="button" onclick="closeCookieBanner()">Close</a>
    </div>

    @push('script')
        <script>
            function setCookie(name, value, days) {
                var expires = "";
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + value + expires + "; path=/";
            }

            // Function to check if the user has accepted the cookie policy
            function hasAcceptedCookiePolicy() {
                return document.cookie.indexOf('{{basicControl()->site_title}}'+"_cookie_policy_accepted=true") !== -1;
            }

            // Function to handle the user accepting the cookie policy
            function acceptCookiePolicy() {
                setCookie('{{basicControl()->site_title}}'+"_cookie_policy_accepted=true", true, 365);
                document.getElementById("cookiesAlert").style.display = "none";
            }

            // Function to handle the user closing the cookie banner without accepting
            function closeCookieBanner() {
                document.getElementById("cookiesAlert").style.display = "none";
            }
            // Display the cookie banner if the user has not accepted the policy
           document.addEventListener('DOMContentLoaded',()=>{
               if (!hasAcceptedCookiePolicy()) {
                   document.getElementById("cookiesAlert").style.display = "block";
               }
           })

        </script>
    @endpush

@endif

<script>
    var root = document.querySelector(':root');
    root.style.setProperty('--primary-color', '{{basicControl()->primary_color??'#60A548'}}');
    root.style.setProperty('--secondary-color', '{{basicControl()->secondary_color??'#033F1B'}}');

    $(document).on('change','#changeLanguage',function (){
        let lang = '{{session()->get('lang')}}';
        if (lang !== $(this).data('lang')){
            let url = $(this).val();
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    // Handle success response
                    window.location.reload();
                },
                error: function () {
                    // Handle error
                    Notiflix.Notify.failure('Something went wrong');
                }
            });
        }
    })

</script>
