<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">
    <title>@lang('Access Forbidden') | @lang(basicControl()->site_title) </title>
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
        .content h1{
            font-size: 150px;
            font-weight: 600;
            line-height: 1;
            margin: 0;
        }
        .content p{
            font-size: 40px;
            line-height: 1.3
        }

        .text-gradient {
            background-image: linear-gradient(90deg, #47beb9, #ddcd86);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

    </style>

</head>
<body>
<section class="error-section">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <img src="{{asset($themeTrue.'images/error.png')}}"  alt="Error Image" srcset="">
            </div>
            <div class="col-md-6">
                <div class="content">
                    <h1>@lang('403')</h1>
                    <p >@lang('Access') <span class="text-gradient"> @lang('Forbidden')</span></p>
                    <a href="{{route('page')}}" class="cmn-btn3">@lang('Back To Home')</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="{{asset($themeTrue.'js/bootstrap.min.js')}}"></script>
</body>
</html>
