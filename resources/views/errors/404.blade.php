<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">
    <title>@lang('Not Found') | @lang(basicControl()->site_title) </title>
    <link href="{{asset($themeTrue.'css/bootstrap.css')}}" rel="stylesheet">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset($themeTrue . 'css/user-style.css') }}"/>
    <!-- owl carousel -->
    <style>
        .error-section{
            margin: 10px;
        }
        @media (max-width: 768px) {
            .error-section{
                margin-top: 130px;
            }
        }
        .content p{
            font-size: 20px;
        }

    </style>

</head>
<body>
<section class="error-section">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <img src="{{asset($themeTrue.'images/404.jpg')}}"  alt="Error Image" srcset="">
            </div>
            <div class="col-md-6">
                <div class="content">
                    <h1>@lang('Oops! Not found')</h1>
                    <p>@lang('The page you’re looking for doesn’t exist. It might have been moved , removed , renamed or might never existed. If you typed the URL directly, please make sure the spelling is correct.')</p>
                    <a href="{{route('page')}}" class="cmn-btn3">@lang('Back To Home')</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{asset($themeTrue.'js/bootstrap.min.js')}}"></script>
<script src="{{asset($themeTrue.'js/user-script.js')}}"></script>
</body>
</html>
