<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Infinety Pizzerie</title>
    </head>
    <body class="antialiased">
        <div id="app">
            <App></App>
            {{-- <ingredients-component></ingredients-component> --}}
        </div>
        <script src="{{asset('js/app.js')}}"></script>
    </body>
</html>
