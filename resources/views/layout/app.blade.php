<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <link rel="icon" type="text/css" href="{{asset('../public/img/tempologo.png')}}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>FCA</title>
        <title>
            @yield('title')
        </title>
        <!-- Fonts -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('css/materialicon.css')}}">
        {{-- <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css"> --}}
    </head>
    <style type="text/css">
        #nprogress .bar {
            background: yellow !important;
        }

        #nprogress .peg {
            box-shadow: 0 0 10px yellow, 0 0 5px yellow !important;
        }

        #nprogress .spinner-icon {
            border-top-color: yellow !important;
            border-left-color: yellow !important;
        }
    </style>
    <body>
        <div id="app"> </div>
    </body>
    <script src="{{ mix('js/app.js') }}"></script> 
    <!-- <script src="{{ mix('js/app.js') }}"></script> -->
</html>
