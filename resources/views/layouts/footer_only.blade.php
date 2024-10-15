<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Laravel App')</title>
    @vite('resources/css/app.css')
</head>
<body>

    <!-- Main Content -->
    <div class="container mx-auto">
        @yield('content')
    </div>

    <!-- Footer -->
    @include('partials.footer')

</body>
</html>