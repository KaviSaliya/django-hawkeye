<!DOCTYPE html>
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/x-icon" href="{{ getFile(basicControl()->favicon_driver,basicControl()->favicon) }}"/>
    <title>{{basicControl()->site_title}}</title>

    <link href="{{asset($themeTrue.'css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset($themeTrue.'css/style.css')}}" rel="stylesheet">

</head>

<body class="">
    @yield('content')
    <script src="{{ asset($themeTrue . 'js/bootstrap.min.js')}}"></script>
</body>
</html>
