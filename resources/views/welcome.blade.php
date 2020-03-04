<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{env('APP.NAME') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Questrial|Raleway&display=swap" rel="stylesheet">

        <link href="/css/critical.css" rel="stylesheet">
        @if(isset($images))
	@foreach ($images as $img)
		<meta property="og:image" content="{{url($img)}}" />
	@endforeach
        @endif
	@if(isset($title))
	<meta property="og:title" content="{{$title ?? ''}}" />
        @endif
        @if(isset($desc))
	<meta property="og:description" content="{{$desc ?? ''}}" />
	@endif

    </head>
    <body>
        <div id="app">

          <Layout></Layout>


        </div>
        <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
        <link href="/css/app.css" rel="stylesheet">
    </body>
</html>
