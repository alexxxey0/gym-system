<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    <link rel="shortcut icon" href="{{ asset('images/fitlife_icon.png') }}" type="image/x-icon">
    @vite('resources/css/app.css')

    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>
<body>

    <!-- Header -->
    @include('partials.header')

    <!-- Main Content and Navbar -->
    <div class='flex flex-row h-full'>
        <div class="container mx-auto">
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>