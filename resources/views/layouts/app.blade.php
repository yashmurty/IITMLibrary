<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IITM Library</title>

    <meta name="description" content="IIT Madras Library Portal">
    <meta name="keywords" content="IITM,IITMLibrary,IIT Madras,IIT Madras Library">
    <meta name="author" content="Yash Murty">

    <link rel="icon" href="{{ URL::asset('img/IIT_Madras_Logo_30.png') }}" type="image/x-icon" />
    <!-- Fonts -->
    <link href="{{ URL::asset('css/font-awesome.min.css') }}" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">

    @include('layouts.topnavbar')

    @yield('content')

    <!-- JavaScripts -->
    <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

    <script type="text/javascript" src="{{ URL::asset('js/notify.min.js') }}"></script>


    @if(Session::has('globalalertmessage'))
            <script type="text/javascript">
                $.notify("{{ Session::get('globalalertmessage') }}", "{{ Session::get('globalalertclass') }}");
            </script>
    @endif

    @yield('jscontent')


</body>
</html>
