<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Questrial|Raleway&display=swap&subset=latin-ext" rel="stylesheet">
        <link href="/css/critical.css" rel="stylesheet">
    </head>
    <body>
        <div id="app">
          <Layout></Layout>
        </div>
        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
        <link href="/css/app.css" rel="stylesheet">
    </body>
</html>
